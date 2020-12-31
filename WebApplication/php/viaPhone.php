<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = $_POST['contributor-fName'] . ' ' . $_POST['contributor-lName'];
    $email    = filter_var($_POST['contributor-email'], FILTER_VALIDATE_EMAIL);
    $phone    = $_POST['contributor-phone'];
    $timePref = $_POST['preferred-time'];

    date_default_timezone_set('Etc/UTC');
    require './lib/PHPMailer/PHPMailerAutoload.php';
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Debugoutput = 'html';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = "dcl.buffalo.dev@gmail.com";
    $mail->Password = "e5d4c3b2a1";
    $mail->setFrom($email, $name);
    $mail->addReplyTo($email, $name);
    $mail->addAddress('dcl.buffalo.dev@gmail.com', 'DCL');
    $mail->Subject = 'Phone Request -- ' . $name;
    $mail->IsHTML(false);
    $mail->Body = "\r\nName: " . $name .
                  "\r\nEmail: " . $email .
                  "\r\nPhone: " . $phone .
                  "\r\nTime Preference: " . $timePref;
    // $mail->AltBody = $mail->Body;
}

if (isset($mail) && $mail->send()) {
    echo 'success';
} else {
    echo 'error';
}

?>