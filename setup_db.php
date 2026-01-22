<?php
include 'includes/db.php';

$sqlFile = 'db/sms_upgrade.sql';
if (file_exists($sqlFile)) {
    $sql = file_get_contents($sqlFile);
    if (mysqli_multi_query($con, $sql)) {
        echo "Database upgraded successfully.<br>";
        do {
            if ($result = mysqli_store_result($con)) {
                mysqli_free_result($result);
            }
        } while (mysqli_next_result($con));
    } else {
        echo "Error upgrading database: " . mysqli_error($con);
    }
} else {
    echo "Upgrade script not found.";
}
?>
