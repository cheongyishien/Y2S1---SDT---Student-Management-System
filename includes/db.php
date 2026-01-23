<?php
// Database configuration for InfinityFree
$servername = "sql310.infinityfree.com";
$username = "if0_40444242";
$password = "f7eca53b3e";
$dbname = "if0_40444242_db_sms";

$con = mysqli_connect($servername, $username, $password, $dbname);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
