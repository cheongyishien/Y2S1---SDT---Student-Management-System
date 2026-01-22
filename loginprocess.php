<?php
include 'includes/db.php';
include 'includes/auth.php';

$email = $_POST['femail'];
$pwd = $_POST['fpwd'];

if (verifyLogin($email, $pwd, $con)) {
    // Redirect based on role
    // '01' => 'IT Staff', '02' => 'Lecturer', '03' => 'Student'
    $type = $_SESSION['u_type'];
    if ($type == '01') {
        header('Location: admin_dashboard.php');
    } elseif ($type == '02') {
        header('Location: lecturer_dashboard.php');
    } elseif ($type == '03') {
        header('Location: student_dashboard.php');
    } else {
        header('Location: index.php');
    }
} else {
    // Login failed
    echo "<script>alert('Invalid Email or Password'); window.location.href='login.php';</script>";
}
?>
