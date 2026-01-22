<?php
$servername = "localhost";
$username = "root";
$password = "";

// 1. Connect to MySQL server
$con = mysqli_connect($servername, $username, $password);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// 2. Create Database
$sql = "CREATE DATABASE IF NOT EXISTS db_sms";
if (mysqli_query($con, $sql)) {
    echo "Database db_sms created successfully.<br>";
} else {
    echo "Error creating database: " . mysqli_error($con) . "<br>";
}

// 3. Select Database
mysqli_select_db($con, "db_sms");

// 4. Run Legacy SQL (db/db_sms.sql)
$sqlFile = 'db/db_sms.sql';
if (file_exists($sqlFile)) {
    $sqlContent = file_get_contents($sqlFile);
    // Remove comments to avoid issues with multi_query sometimes
    // But mostly multi_query handles it. However, if there are errors, it stops.
    // Let's try to run it.
    
    // We need to disable foreign key checks temporarily because of the weird circular/order constraints
    mysqli_query($con, "SET FOREIGN_KEY_CHECKS = 0");
    
    if (mysqli_multi_query($con, $sqlContent)) {
        echo "Legacy DB imported.<br>";
        do {
            if ($res = mysqli_store_result($con)) { mysqli_free_result($res); }
        } while (mysqli_next_result($con));
    } else {
        echo "Error importing legacy DB: " . mysqli_error($con) . "<br>";
    }
    
    mysqli_query($con, "SET FOREIGN_KEY_CHECKS = 1");
} else {
    echo "Legacy SQL file not found.<br>";
}

// 5. Run Upgrade SQL (db/sms_upgrade.sql)
$upgradeFile = 'db/sms_upgrade.sql';
if (file_exists($upgradeFile)) {
    $upgradeContent = file_get_contents($upgradeFile);
     if (mysqli_multi_query($con, $upgradeContent)) {
        echo "DB Upgraded.<br>";
        do {
             if ($res = mysqli_store_result($con)) { mysqli_free_result($res); }
        } while (mysqli_next_result($con));
    } else {
        echo "Error upgrading DB: " . mysqli_error($con) . "<br>";
    }
} else {
    echo "Upgrade SQL file not found.<br>";
}

mysqli_close($con);
?>
