<?php

$connections = array();

function getDbConnection()
{
    if ($_SERVER['HTTP_HOST'] == 'localhost') {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "access_database";
        $port="3307";
    }
    else {
        $servername = 'dspathwaysorg.ipagemysql.com';
        $username='jeff';
        $password='PathwaysDS20!7';
        $port="3306";
        // $dbname='access_database';
        $dbname='kpaam_cam';
    }
    error_log("Connecting to  ".$dbname." as user ".$username, 0);
    $conn = null;
    global $connections;
    // Create connection
    try {
        //echo("trying connection");
        $conn = new PDO("mysql:host=".$servername.":".$port.";dbname=".$dbname.";charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        array_push($connections, $conn);
        return $conn;
    }
    catch (Exception $e) {
        echo "connection error ".$servername .$dbname .$username .$password;
        error_log("Error Connecting to  ".$dbname." as user ".$username. " ".$e, 0);
    }
}

function getOldDbConnection()
{
    if ($_SERVER['HTTP_HOST'] == 'localhost') {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "old_access_database";
        $port="3307";
    }
    else {
    }
    error_log("Connecting to  ".$dbname." as user ".$username, 0);
    $conn = null;
    try {
        //echo("trying connection");
        $conn = new PDO("mysql:host=".$servername.":".$port.";dbname=".$dbname.";charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        array_push($connections, $conn);
        return $conn;
    }
    catch (Exception $e) {
        echo "connection error ".$servername .$dbname .$username .$password;
        error_log("Error Connecting to  ".$dbname." as user ".$username. " ".$e, 0);
    }
}

function closeConnections()
{
    global $connections;
    foreach ($connections as $conn) {
        $conn = null;
    }
    $connections = array();
}