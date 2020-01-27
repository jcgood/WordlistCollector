<?php
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
$error = false;
error_reporting(E_ALL);
if($_POST['query'] == ''){
	$error = true;
	$result['status'] = 'error';
	$result['message'] = 'Please enter the query !!';
	echo json_encode($result);
	exit;
}

// pagination
if (! (isset($_POST['pageNumber']))) {
    $pageNumber = 1;
} else {
    $pageNumber = $_POST['pageNumber'];
}
$perPageCount = 20;


$link = mysqli_connect($server, $username, $password, $db_name);
$link ->set_charset("utf8");
$query_string = $_POST['query'];

try{
	$query_result = mysqli_query($link, $query_string);
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

// pagination
$rowCount = mysqli_num_rows($query_result);
mysqli_free_result($query_result);
$pagesCount = ceil($rowCount / $perPageCount);
$lowerLimit = ($pageNumber - 1) * $perPageCount;
$sqlQuery = $query_string." limit " . ($lowerLimit) . " ,  " . ($perPageCount) . " ";
$query_result = mysqli_query($link, $sqlQuery);
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
	$final_result['query_string'] = $query_string;
	$final_result['message'] = $result;
	$final_result['page_no'] = $pageNumber;
	$final_result['total_pages'] = $pagesCount;
	$final_result['status'] = 'success';
	$result = $final_result;
}
echo json_encode($result);
exit;
?>