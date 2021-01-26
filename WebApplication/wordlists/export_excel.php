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
$query_result = $statement->fetchAll();

if($data['excel_query_type'] == 'speaker_query'){
    $query_result[0]['LanguageName'] = $query_result[0]['LanguageNames'];
    unset($query_result[0]['LanguageNames']);
    unset($query_result[0]['LanguageIDs']);
}
if($data['transpose_result'] == 'true'){
    $query_result = transposeResult($query_result);
}


// Language Citation
if($data['excel_query_type'] == 'language_citation'){
    // Get all concepts and number them
    $concept_sr_no = getConceptSrList($query_result);

    // Order result according to each concept-speaker
    $query_result_concept_speaker_wise = array();
    foreach ($query_result as $key => $value) {
        $query_result_concept_speaker_wise[$value['concept']]['sr_no'] = $concept_sr_no[$value['concept']]['sr_no'];
        $query_result_concept_speaker_wise[$value['concept']]['concept'] = $value['concept'];
        $query_result_concept_speaker_wise[$value['concept']][$value['speaker_name']] = $value['word'];
    }

    // Get a list of speakers
    $speakers_list = array_unique(array_column($query_result,'speaker_name'));

    // Add missing record (blank) for each concept-speaker
    foreach ($speakers_list as $speaker_key => $speaker_value) {
        foreach ($concept_sr_no as $key => $value) {
            if (!isset($query_result_concept_speaker_wise[$key][$speaker_value])){
                $query_result_concept_speaker_wise[$key][$speaker_value] = '';
            }
        }
    }

    // Renumber the result
    $query_result = array();
    $count = 0;
    foreach ($query_result_concept_speaker_wise as $concept_key => $value) {
        $query_result[$count] = $value;
        $count += 1;
    }
}

if($error){
    $query_result['status'] = 'error';
    $query_result['message'] = $error_message;
    echo json_encode($query_result);
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
foreach($query_result as $row) {
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
    $total_concepts_list = array_unique(array_column($total_concepts_list,'concept'));
    $result = array();
    $count = 0;
    foreach ($total_concepts_list as $key => $value) {
        $result[$value]['sr_no'] = $count+1;
        $count += 1;
    }
    return $result;
}
?>