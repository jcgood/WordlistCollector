<?php
$local_db = false;
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
$error = false;
error_reporting(E_ALL);
if($_POST['query'] == ''){
	$error = true;
	$result['status'] = 'error';
	$result['message'] = 'Please enter the query !!';
	echo json_encode($result);
	exit;
}
$link = mysqli_connect($server, $username, $password, $db_name);
try{
	$query_result = mysqli_query($link, $_POST['query']);
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

// while ($row = mysqli_fetch_array($query_result,MYSQLI_NUM))
$count = 0;
while ($row = mysqli_fetch_array($query_result,MYSQLI_ASSOC))  
{
    foreach ($row as $key => $value) {
        $result[$count][$key] = $value;
    }
    $count++;
}
if(!$error){
	$final_result['message'] = $result;
	$final_result['status'] = 'success';
	$result = $final_result;
}
echo json_encode($result);
exit;
?>