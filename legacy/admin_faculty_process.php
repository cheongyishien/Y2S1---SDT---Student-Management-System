<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['01']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $f_id = $_POST['f_id'];
    $f_name = $_POST['f_name'];
    
    if ($action == 'add') {
        $sql = "INSERT INTO tb_faculty (f_id, f_name) VALUES (?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $f_id, $f_name);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Faculty registered successfully'); window.location.href='admin_dashboard.php';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($con) . "'); window.history.back();</script>";
        }
    } elseif ($action == 'update') {
        $sql = "UPDATE tb_faculty SET f_name = ? WHERE f_id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $f_name, $f_id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Faculty updated successfully'); window.location.href='admin_dashboard.php';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($con) . "'); window.history.back();</script>";
        }
    }
} else {
    header("Location: admin_dashboard.php");
}
?>
