<?php

// DB connection
require_once 'connect.php';
$conn = getDbConnection();

// Read query from request
$error = false;
error_reporting(E_ALL);
$data = $_POST;
if($data['query'] == ''){
	$error = true;
	$result['status'] = 'error';
	$result['message'] = 'Please enter the query !!';
	echo json_encode($result);
	exit;
}
$query_string = $data['query'];

// get query result
try{
	$statement = $conn->prepare($query_string);
	$statement->setFetchMode(PDO::FETCH_ASSOC);
	$statement->execute();
	$query_result = $statement->fetchAll();
}
catch(Exception $e){
	$error = true;
	$result['status'] = 'error';
	$result['message'] = 'Query is invalid. Please check !!';
	echo json_encode($result);
	exit;
}
if(!$query_result){
	$error = true;
	$result['status'] = 'error';
	$result['message'] = 'Query returned zero results. Please check !!';
	echo json_encode($result);
	exit;
}

// New wordlist
// 1 - Language Citation
if($data['query_type'] == 'language_citation'){
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
$result['query_string'] = $query_string;
$result['message'] = $query_result;	
$result['status'] = 'success';
echo json_encode($result);
exit;

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