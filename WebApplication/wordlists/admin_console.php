<?php
include_once 'util.php';
include_once 'menu.php';
if (!isset($_SESSION)){
    my_session_start();
}
// check that the user is logged in - if not, redirect to login.
if (!isset($_SESSION["user_email_address"]) || !(isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "Admin")) {
    $location = "login.php";
    echo("<script>location.href='$location'</script>");
    exit;
}
// DB connection
require_once 'connect.php';

$conn = getDbConnection();

$query_string = "SELECT * FROM user_profile;";
$statement = $conn->prepare($query_string);
$statement->setFetchMode(PDO::FETCH_ASSOC);
$statement->execute();
$query_result = $statement->fetchAll();

if(!$query_result){
    $error = true;
    $result['status'] = 'error';
    $result['message'] = 'No users found. Please contact Administrator!!';
}
else{
	$result['status'] = 'success';
    $result['query_result'] = $query_result;
    $result['session_data'] = $_SESSION;
}


require_once 'h2o-php/h2o.php';
$template = new H2o('templates/admin_console.html', array(
    'cache_dir' => dirname(__FILE__)
));
echo $template->render(compact('result'));
?>