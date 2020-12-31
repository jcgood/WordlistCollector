<?php

// DB connection
require_once 'connect.php';
$conn = getDbConnection();

$query_string = "Select distinct upper(Language) as Language FROM `language_table` order by Language";
$statement = $conn->prepare($query_string);
$statement->setFetchMode(PDO::FETCH_ASSOC);
$statement->execute();
$query_result = $statement->fetchAll();
if(!$query_result){
	$error = true;
	$result['status'] = 'error';
	$result['message'] = 'No Languages found. Please contact Administrator!!';
	echo json_encode($result);
	exit;
}
echo json_encode($query_result);
exit;
?>