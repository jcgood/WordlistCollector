<?php
function filterData(&$str)
{
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}


$local_db = false;
// $local_db = true;
if($local_db){
	$server = 'localhost';
	$username = 'root';
	$password = '';
	$db_name = 'test';
}
else{
	$server = 'dspathwaysorg.ipagemysql.com';
	$username = 'jeff';
	$password = 'PathwaysDS20!7';
	$db_name = 'access_database';
}
$link = mysqli_connect($server, $username, $password, $db_name);
$link ->set_charset("utf8");
$data = $_POST;
$query_string = $data['excel_query'];
$query_result = mysqli_query($link, $query_string);

$result = fetchQueryResult($query_result);
if($data['excel_query_type'] == 'speaker_query'){
    $result[0]['LanguageName'] = $result[0]['LanguageNames'];
    unset($result[0]['LanguageNames']);
    unset($result[0]['LanguageIDs']);
}
if($data['transpose_result'] == 'true'){
    $tmp_result = $result;
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
}


// Get speakerwise result
if($data['excel_query_type'] == 'language_citation'){
    $concepts_list = array_unique(array_column($result, 'concept_name'));
    $speakers_list = mysqli_query($link, "select distinct UserName from User_Citation where LANGUAGE = '".$data['language']."'");
    if(!$speakers_list){
        $error = true;
        $result['status'] = 'error';
        $result['message'] = 'Query returned zero results. Please check !!';
        echo json_encode($result);
        exit;
    }
    $speakers_list = array_unique(array_column(fetchQueryResult($speakers_list),'UserName'));
    $speakers_concepts_query = "select concept_name as Concept, Citation, UserName as Speaker from User_Citation where concept_name in ('".implode('\',\'', $concepts_list)."') and UserName in ('".implode('\',\'', $speakers_list)."')";
    $speakers_concepts_list = fetchQueryResult(mysqli_query($link, $speakers_concepts_query));

    $concept_speaker_combo = array();
    foreach ($speakers_concepts_list as $key => $value) {
        $concept_speaker_combo[$value['Concept']][$value['Speaker']] = $value['Citation'];
    }

    foreach ($result as $result_key => $result_value) {
        foreach ($speakers_list as $speaker_key => $speaker_value) {
            if(isset($concept_speaker_combo[$result_value['concept_name']][$speaker_value])){
                $result[$result_key][$speaker_value] = $concept_speaker_combo[$result_value['concept_name']][$speaker_value];
            }
            else{
                $result[$result_key][$speaker_value] = '';
            }
        }
    }
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
?>