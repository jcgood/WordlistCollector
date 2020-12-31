<?php
include_once 'util.php';
include_once 'menu.php';
if (!isset($_SESSION)){
    my_session_start();
}
// DB connection
require_once 'connect.php';

if(isset($_POST)){
	$user_email_address =  $_POST['user_email_address'];

	$conn = getDbConnection();
	$query_string = "SELECT * FROM user_profile
		WHERE user_email_address='$user_email_address'";
	$statement = $conn->prepare($query_string);
	$statement->setFetchMode(PDO::FETCH_ASSOC);
	$statement->execute();
	$query_result = $statement->fetchAll();
	$count=$statement->rowCount();

    if ($count==0) {
    	$_SESSION["forgot_password_message"] = "We were not able to find a profile with that email address. Please check your email address and try again!</a>";
        header("Location: login.php");
    }
    else {
    	$user_one_time_password = rand(100000, 999999);
    	$_SESSION["user_one_time_password"] = $user_one_time_password;

    	include 'php/lib/PHPMailer/PHPMailerAutoload.php';
		$mail = new PHPMailer;
		if ($_SERVER['HTTP_HOST'] == 'localhost'){
			$mail->isSMTP();                         // Set mailer to use SMTP
			$mail->SMTPAuth = false;                 // Enable SMTP authentication
			$mail->Host = 'hobbes.cse.buffalo.edu';  // Specify main and backup SMTP servers
		}
		else{
			$mail->Host = 'smtp.ipage.com';  // Specify main and backup SMTP servers
		}
		$mail->Port = 587;                       // TCP port to connect to

		$mail->setFrom('no-reply@buffalo.edu', 'Access Database');
		$mail->isHTML(true);
	    $mail->addReplyTo('yashniti@buffalo.edu', 'Yash Mantri');
	    $mail->addCustomHeader('MIME-Version: 1.0');
	    $mail->addCustomHeader('Content-Type: text/html; charset=ISO-8859-1');
    	$mail->Subject = 'Access Database Password Change Request';

		$message = "We have received your password change request. This e-mail contains the information that you need to change your password. <br/><br/>One Time Password: $user_one_time_password<br/><br/>Thank you,<br/>Jeff Good Team";
		$mail->addAddress($user_email_address);
		$mail->Body = $message;
		$mail->send();

		$query_string = "UPDATE user_profile
		SET user_one_time_password='$user_one_time_password'
		WHERE user_email_address='$user_email_address'";
		$statement = $conn->prepare($query_string);
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		$statement->execute();
		$count = $statement->rowCount();
		$_SESSION["set_user_password"] = "Check your email for a one-time password";
		$_SESSION["email"] = $user_email_address;
		$_SESSION["forgot_user_password"] = "Change Password";
        header("Location: set_user_password.php");
    }
}
else{
    $_SESSION["forgot_password_message"] = "There is some error. Please contact Administrator.";
    header("Location: forgot_user_password.php");
	closeConnections();
}