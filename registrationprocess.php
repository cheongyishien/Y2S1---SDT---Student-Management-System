<?php
include 'includes/db.php';

$fname = $_POST['fname'];
$fpwd = $_POST['fpwd'];
$fpwd_confirm = $_POST['fpwd_confirm'];
$femail = $_POST['femail'];
$foperator = $_POST['foperator'];
$fphone = $_POST['fphone'];
$fgender = $_POST['fgender'];
$fprogramme = $_POST['fprogramme'];
$fcollege = $_POST['fcollege'];

// Password Check
if ($fpwd !== $fpwd_confirm) {
    echo "<script>alert('Passwords do not match'); window.history.back();</script>";
    exit();
}

// Hash Password
$hashed_pwd = password_hash($fpwd, PASSWORD_DEFAULT);

// SQL Injection Prevention (Prepared Statements)
$sql = "INSERT INTO tb_user(u_pwd, u_name, u_phone_operator, u_phone_no, u_email, u_gender, u_programme, u_residential, u_type)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, '03')";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "ssiissss", $hashed_pwd, $fname, $foperator, $fphone, $femail, $fgender, $fprogramme, $fcollege);

if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('Registration Successful. Please Login.'); window.location.href='login.php';</script>";
} else {
    echo "Error: " . mysqli_error($con);
}

mysqli_stmt_close($stmt);
mysqli_close($con);
?>
