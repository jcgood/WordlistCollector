<?php
function filterData(&$str)
{
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}


$local_db = false;
if($local_db){
    $server = 'localhost';
    $username = 'root';
    $password = '';
    $port = '3307';
    $db_name = 'access_database';
}
else{
    $server = 'dspathwaysorg.ipagemysql.com';
    $username = 'jeff';
    $password = 'PathwaysDS20!7';
    $port = '3306';
    $db_name = 'access_database';
}
$link = mysqli_connect($server.':'.$port, $username, $password, $db_name);
$link ->set_charset("utf8");
$data = $_POST;
$query_string = $data['excel_query'];
$query_result = mysqli_query($link, $query_string);
$error = false;

$result = fetchQueryResult($query_result);
if($data['excel_query_type'] == 'speaker_query'){
    $result[0]['LanguageName'] = $result[0]['LanguageNames'];
    unset($result[0]['LanguageNames']);
    unset($result[0]['LanguageIDs']);
}
if($data['transpose_result'] == 'true'){
    $result = transposeResult($result);
}


// Language Citation
if($data['excel_query_type'] == 'language_citation'){
    // SR no for concepts (words)
    $total_concepts_list = mysqli_query($link, "Select distinct Concept as concept_name FROM `ConceptList` order by Concept");
    if(!$total_concepts_list){
        $error = true;
        $error_message = 'No Words found. Please contact Administrator!!';
    }
    else{
        $concept_sr_no = getConceptSrList($total_concepts_list);
        $concepts_list = array_unique(array_column($result, 'concept_name'));

        // Speakers list
        $speakers_list = mysqli_query($link, "select distinct UserName from User_Citation where LANGUAGE = '".$data['language']."'");
        if(!$speakers_list){
            $error = true;
            $error_message = 'No Speakers found. Please contact Administrator!!';
        }
        else{
            $speakers_list = array_unique(array_column(fetchQueryResult($speakers_list),'UserName'));
            $speaker_order_list = array();
            foreach ($speakers_list as $key => $value) {
                $speaker_order_list[explode('-', $value)[2]] = $value;
            }
            ksort($speaker_order_list);

            $speakers_concepts_query = "select concept_name as Concept, Citation, UserName as Speaker from User_Citation where concept_name in ('".implode('\',\'', $concepts_list)."') and UserName in ('".implode('\',\'', $speakers_list)."')";
            $speakers_concepts_list = fetchQueryResult(mysqli_query($link, $speakers_concepts_query));

            // Conceptwise speakers
            $concept_speaker_combo = array();
            foreach ($speakers_concepts_list as $key => $value) {
                $concept_speaker_combo[$value['Concept']][$value['Speaker']] = $value['Citation'];
            }

            // SR no, Concept and Speakers
            $result = updateLanguageCitationResult($result, $speaker_order_list, $concept_sr_no, $concept_speaker_combo);
        }
    }
}

if($error){
    $result['status'] = 'error';
    $result['message'] = $error_message;
    echo json_encode($result);
    exit;
}



// file name for download
$fileName = "Query_result_" . date('Ymd') . ".xls";

header("Content-Disposition: attachment; filename=\"$fileName\"");
header("Content-Type: application/vnd.ms-excel; charset=utf-8;");
// header("Pragma: no-cache");
// header("Expires: 0");
echo "\xEF\xBB\xBF"; //UTF-8 BOM

$flag = false;
foreach($result as $row) {
    if(!$flag) {
        // display column names as first row
        echo implode("\t ,", array_keys($row)) . "\n";
        $flag = true;
    }
    // filter data
    array_walk($row, 'filterData');
    echo implode("\t ,", preg_replace('/,/',';',array_values($row))). "\n";
}


function fetchQueryResult($query_result){
    $count = 0;
    $result = array();
    // while ($row = mysqli_fetch_array($query_result,MYSQLI_NUM))
    while ($row = mysqli_fetch_array($query_result,MYSQLI_ASSOC))  
    {
        foreach ($row as $key => $value) {
            $result[$count][$key] = $value;
        }
        $count++;
    }
    return $result;
}

function transposeResult($tmp_result){
    $result = array();
    $count = 0;
    foreach ($tmp_result as $index => $value) {
        $keys = array_keys($value);
        $values = array_values($value);
        $key_zero = $keys[0];
        $value_zero = $values[0];
        for($row = 1; $row < count($keys); $row++){
            $result[$row-1][$key_zero] = $keys[$row];
            $result[$row-1][$value_zero] = $values[$row];
        }
    }
    return $result;
}

function getConceptSrList($total_concepts_list){
    $total_concepts = fetchQueryResult($total_concepts_list);
    $total_concepts_list = array_unique(array_column($total_concepts,'concept_name'));
    // print_r($total_concepts_list);
    $result = array();
    foreach ($total_concepts_list as $key => $value) {
        $result[$value]['sr_no'] = $key+1;
    }
    return $result;
}

function updateLanguageCitationResult($result, $speakers_list, $concept_sr_no, $concept_speaker_combo){
    $result_m = array();
    foreach ($result as $total_concepts_key => $total_concepts_value) {
        foreach ($speakers_list as $speaker_key => $speaker_value) {
            $result_m[$total_concepts_key]['sr_no'] = $concept_sr_no[$total_concepts_value['concept_name']]['sr_no'];
            $result_m[$total_concepts_key]['concept_name'] = $total_concepts_value['concept_name'];
            if(isset($concept_speaker_combo[$total_concepts_value['concept_name']][$speaker_value])){
                $result_m[$total_concepts_key][$speaker_value] = $concept_speaker_combo[$total_concepts_value['concept_name']][$speaker_value];
            }
            else{
                $result_m[$total_concepts_key][$speaker_value] = '';
            }
        }
    }
    return $result_m;
}
?>