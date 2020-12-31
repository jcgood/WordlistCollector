<?php
// ini_set('display_errors', 1);
include_once 'util.php';
include_once 'connect.php';
if (!isset($_SESSION)){
    my_session_start();
}

if(isset($_POST)){
    $user_email_address =  $_POST['user_email_address'];
    $user_password =  md5($_POST['user_password']);
    $query = "SELECT * FROM user_profile WHERE user_email_address=? and user_password=?";

    $conn = getDbConnection();
    $statement = $conn->prepare($query);
    $statement->setFetchMode(PDO::FETCH_ASSOC);
    $statement->execute([$user_email_address,$user_password]);
    $result = $statement->fetchAll();

    if ($result[0]) {
        $_SESSION["user_email_address"] = $user_email_address;
        $firstrow = $result[0];

        $_SESSION["user_firstname"] = $firstrow["user_first_name"];
        $_SESSION["user_lastname"] = $firstrow["user_last_name"];
        $_SESSION["user_id"] = $firstrow["user_id"];
        $_SESSION["user_type"] = $firstrow["user_type"];
        //echo "Logged in as: ".$user_email_address;
        if ($firstrow['user_type']=='User') {
            $location = "accessdb.php";
        } else {
            $_SESSION["user_type"] = 'Admin';
            $location = "accessdb.php";
        }
        header("Location: ".$location);
    } else {
        $_SESSION["login_message"] = "Incorrect credentials!";
        error_log('invalid login for '.$user_email_address." - ".$user_password);
        header("Location: login.php");
    }
}
else{
    $_SESSION["login_message"] = "There is some error. Please contact Administrator.";
    header("Location: login.php");
}
closeConnections();