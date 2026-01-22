<?php
include 'includes/db.php';
$r = mysqli_query($con, "SHOW TABLES");
while ($row = mysqli_fetch_row($r)) {
    echo $row[0] . "\n";
}
?>
