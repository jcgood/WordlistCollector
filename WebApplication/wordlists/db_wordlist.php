<?php

// DB connection
require 'connect.php';
$conn = getDbConnection();

$query_string = "show tables where tables_in_access_database like 'wordlist_%'";
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
$result = array_column($query_result, 'Tables_in_access_database');

foreach ($result as $key => $value) {
	$table_name = explode('_', $value)[1];
	$table_query = "select * from ".$value.";";
	$statement = $conn->prepare($table_query);
	$statement->setFetchMode(PDO::FETCH_ASSOC);
	$statement->execute();
	$table_result = $statement->fetchAll();
	$wordlist_total_data = array();
	
	foreach ($table_result as $table_key => $table_value) {
		$row_data = array();
		$row_data['wordlist'] = $table_name;
		$row_data['wordlist_id'] = $table_value['id'];
		$row_data['concept_id'] = $table_value['concept_id'];
		$row_data['concept'] = $table_value['concept'];
		$row_data['speaker_name'] = $table_value['speaker_name'];
		$row_data['word'] = $table_value['citation'];
		$row_data['noun_class'] = $table_value['noun_class'];
		$row_data['concept_speaker_name'] = $table_value['concept_speaker_name'];
		$wordlist_total_data[] = $row_data;

		$query = "INSERT INTO master_word_list (wordlist, wordlist_id, concept_id, concept, speaker_name, word, noun_class, concept_speaker_name) Values (?, ?, ?, ?, ?, ?, ?, ?);";
	    $statement = $conn->prepare($query);
		$statement->setFetchMode(PDO::FETCH_ASSOC);

		$args = array();
		array_push($args, $table_name);
		array_push($args, $table_value['id']);
		array_push($args, $table_value['concept_id']);
		array_push($args, $table_value['concept']);
		array_push($args, $table_value['speaker_name']);
		array_push($args, $table_value['citation']);
		array_push($args, $table_value['noun_class']);
		array_push($args, $table_value['concept_speaker_name']);
		$statement->execute($args);
		$last_id = $conn->lastInsertId();
	}
	echo '<pre>';
	print_r($table_name);
	print_r(" ");
	print_r(count($wordlist_total_data));
}
?>