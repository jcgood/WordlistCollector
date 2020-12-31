<?php
include 'util.php';
my_session_start();
my_session_destroy();

$location = "login.php";
echo("<script>location.href='$location'</script>");