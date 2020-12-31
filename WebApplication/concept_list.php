<?php

// DB connection
require_once 'connect.php';
$conn = getDbConnection();

$query_string = "Select distinct concept as Concept FROM `concept_list_new` order by Concept";
$statement = $conn->prepare($query_string);
$statement->setFetchMode(PDO::FETCH_ASSOC);
$statement->execute();
$query_result = $statement->fetchAll();
if(!$query_result){
	$error = true;
	$result['status'] = 'error';
	$result['message'] = 'No Words found. Please contact Administrator!!';
	echo json_encode($result);
	exit;
}
echo json_encode($query_result, JSON_UNESCAPED_UNICODE);
exit;
?>