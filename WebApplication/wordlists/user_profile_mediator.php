<?php
include_once 'util.php';
include_once 'connect.php';
if (!isset($_SESSION)){
    my_session_start();
}

if(isset($_POST)){
    if($_POST['form_action'] == "register_user"){
        $first_name = $_POST['first_name'];
        $last_name =  $_POST['last_name'];
        $user_email_address =  $_POST['user_email_address'];
        $user_password =  md5($_POST['user_password']);

        $query = "SELECT * FROM user_profile
        WHERE user_email_address=?";

        $conn = getDbConnection();
        $statement_id = $conn->prepare($query);
        $statement_id->setFetchMode(PDO::FETCH_ASSOC);
        $statement_id->execute([$user_email_address]);
        $result = $statement_id->fetchAll();
        $count=$statement_id->rowCount();

        if ($count==0) {
            $query_string = "INSERT INTO user_profile (user_first_name,user_last_name, user_email_address, user_password)
                            VALUES (?, ?, ?, ?);";
            try {
                $statement_id = $conn->prepare($query_string);
                $statement_id->execute([$first_name,$last_name,$user_email_address,$user_password]);
                $last_id = $conn->lastInsertId();
                // print_r($last_id);

                $result['status'] = 'success';
                $result['message'] = "User added Successfully.";
            }
            catch (Exception $e) {
                $result['status'] = 'error';
                $result['message'] = "Error in adding user. Please contact Administrator.";
                // $result['message'] = "Error in adding user. Please contact Administrator. <br> $e";
            }
            $_SESSION["add_user_profile"] = $result['message'];
            $_SESSION["add_user_profile_status"] = $result['status'];
            header("Location: login.php");
        }
        else {
            $_SESSION["add_user_profile_status"] = 'error';
            $_SESSION["add_user_profile"] = "A user with that email already exists in our system.";
            header("Location: login.php");
        }
    }
    else if($_POST['form_action'] == "update_user"){
        $user_email_address =  $_POST['user_email_address'];
        $user_password =  md5($_POST['user_new_password']);

        $query = "SELECT * FROM user_profile
        WHERE user_email_address=?";

        $conn = getDbConnection();
        $statement_id = $conn->prepare($query);
        $statement_id->setFetchMode(PDO::FETCH_ASSOC);
        $statement_id->execute([$user_email_address]);
        $result = $statement_id->fetchAll();
        $count=$statement_id->rowCount();

        if ($count == 0) {
            $_SESSION["add_user_profile_status"] = 'error';
            $_SESSION["add_user_profile"] = "A user with that email does not exist in our system.";
            header("Location: login.php");
        }
        else{
            $user_id = $result[0]["user_id"];
            $query_string = "UPDATE user_profile SET user_password = ?, updated_on = ?, updated_by = ? Where user_id = ?;";
            try {
                $statement = $conn->prepare($query_string);
                $statement->execute([$user_password, date("d M Y h:m:i"), $user_id, $user_id]);
                $count = $statement->rowCount();
                $_SESSION['login_message_success'] = "Password updated Successfully.";
            }
            catch (Exception $e) {
                $_SESSION['add_user_profile_status'] = 'error';
                $_SESSION['add_user_profile'] = "Error in updating password. Please contact Administrator.";
            }
            header("Location: login.php");
        }
    }
    else{
        $_SESSION["add_user_profile"] = "There is some error. Please contact Administrator.";
        header("Location: login.php");
    }
}
else{
    $_SESSION["add_user_profile"] = "There is some error. Please contact Administrator.";
    header("Location: login.php");
}
closeConnections();