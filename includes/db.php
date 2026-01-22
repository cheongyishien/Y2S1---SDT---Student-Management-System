<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_sms";

$con = mysqli_connect($servername, $username, $password, $dbname);

if (!$con) {
    echo "Connection failed: " . mysqli_connect_error();
    exit();
}
?>
