<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['01']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sem_id = $_POST['sem_id'] ?? '';
    $sem_faculty = $_POST['sem_faculty'];
    $sem_year = $_POST['sem_year'];
    $sem_name = $_POST['sem_name'];
    $sem_status = $_POST['sem_status'];
    
    if (empty($sem_id)) {
        // Create new semester
        $sql = "INSERT INTO tb_semester (sem_faculty, sem_year, sem_name, sem_status) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $sem_faculty, $sem_year, $sem_name, $sem_status);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Semester created successfully'); window.location.href='admin_faculty_detail.php?fid=" . urlencode($sem_faculty) . "';</script>";
        } else {
            echo "<script>alert('Error creating semester'); window.history.back();</script>";
        }
    } else {
        // Update existing semester
        $sql = "UPDATE tb_semester SET sem_year = ?, sem_name = ?, sem_status = ? WHERE sem_id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $sem_year, $sem_name, $sem_status, $sem_id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Semester updated successfully'); window.location.href='admin_faculty_detail.php?fid=" . urlencode($sem_faculty) . "';</script>";
        } else {
            echo "<script>alert('Error updating semester'); window.history.back();</script>";
        }
    }
} else {
    header("Location: admin_dashboard.php");
}
?>
