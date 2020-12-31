<?php
function filterData(&$str)
{
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}

// DB connection
require 'connect.php';
$conn = getDbConnection();

$data = $_POST;
$error = false;
$query_string = $data['excel_query'];
$statement = $conn->prepare($query_string);
$statement->setFetchMode(PDO::FETCH_ASSOC);
$statement->execute();
$result = $statement->fetchAll();

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
    $statement = $conn->prepare("Select distinct Concept as concept_name FROM `ConceptList` order by Concept");
    $statement->setFetchMode(PDO::FETCH_ASSOC);
    $statement->execute();
    $total_concepts_list = $statement->fetchAll();
    if(!$total_concepts_list){
        $error = true;
        $error_message = 'No Words found. Please contact Administrator!!';
    }
    else{
        $concept_sr_no = getConceptSrList($total_concepts_list);
        $concepts_list = array_unique(array_column($result, 'concept_name'));

        // Speakers list
        $statement = $conn->prepare("select distinct UserName from User_Citation where LANGUAGE = '".$data['language']."'");
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $statement->execute();
        $speakers_list = $statement->fetchAll();

        if(!$speakers_list){
            $error = true;
            $error_message = 'No Speakers found. Please contact Administrator!!';
        }
        else{
            $speakers_list = array_unique(array_column($speakers_list,'UserName'));
            $speaker_order_list = array();
            foreach ($speakers_list as $key => $value) {
                $speaker_order_list[explode('-', $value)[2]] = $value;
            }
            ksort($speaker_order_list);

            $speakers_concepts_query = "select concept_name as Concept, Citation, UserName as Speaker from User_Citation where concept_name in ('".implode('\',\'', $concepts_list)."') and UserName in ('".implode('\',\'', $speakers_list)."')";
            $statement = $conn->prepare($speakers_concepts_query);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $statement->execute();
            $speakers_concepts_list = $statement->fetchAll();

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
    $total_concepts_list = array_unique(array_column($total_concepts_list,'concept_name'));
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