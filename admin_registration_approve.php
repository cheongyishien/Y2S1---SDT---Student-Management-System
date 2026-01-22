<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['01']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $r_id = $_POST['r_id'];
    
    mysqli_begin_transaction($con);
    
    try {
        // Update registration status to Approved
        $sql = "UPDATE tb_registration SET r_status = 'Approved' WHERE r_id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $r_id);
        mysqli_stmt_execute($stmt);
        
        mysqli_commit($con);
        
        echo "<script>alert('Registration approved successfully'); window.history.back();</script>";
        
    } catch (Exception $e) {
        mysqli_rollback($con);
        echo "<script>alert('Error: " . htmlspecialchars($e->getMessage()) . "'); window.history.back();</script>";
    }
} else {
    header("Location: admin_dashboard.php");
}
?>
