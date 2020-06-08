<?php
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
$query_string = "Select distinct SpeakerID FROM `SpeakerMetaData` order by SpeakerID";
$query_result = mysqli_query($link, $query_string);

if(!$query_result){
	$error = true;
	$result['status'] = 'error';
	$result['message'] = 'No Speakers found. Please contact Administrator!!';
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
echo json_encode($result);
exit;
?>