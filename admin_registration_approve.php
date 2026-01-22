<?php
include 'includes/db.php';
include 'includes/email.php';
checkSession();
checkRole(['01']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $r_id = $_POST['r_id'];
    
    mysqli_begin_transaction($con);
    
    try {
        // Get registration details with student and course info for the email
        $sql_info = "SELECT r.r_course_code, r.r_section, r.r_status, u.u_name, u.u_email, c.c_name
                     FROM tb_registration r
                     INNER JOIN tb_user u ON r.r_student_id = u.u_id
                     INNER JOIN tb_course c ON r.r_course_code = c.c_code AND r.r_section = c.c_section
                     WHERE r.r_id = ?";
        $stmt_info = mysqli_prepare($con, $sql_info);
        mysqli_stmt_bind_param($stmt_info, "i", $r_id);
        mysqli_stmt_execute($stmt_info);
        $res_info = mysqli_stmt_get_result($stmt_info);
        $reg = mysqli_fetch_assoc($res_info);
        
        if (!$reg) {
            throw new Exception("Registration not found.");
        }
        
        if ($reg['r_status'] == 'Approved') {
            throw new Exception("Registration is already approved.");
        }

        // Update registration status to Approved
        $sql = "UPDATE tb_registration SET r_status = 'Approved' WHERE r_id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $r_id);
        mysqli_stmt_execute($stmt);
        
        // Increment course enrollment
        $sql_inc = "UPDATE tb_course 
                    SET c_current_students = c_current_students + 1 
                    WHERE c_code = ? AND c_section = ?";
        $stmt_inc = mysqli_prepare($con, $sql_inc);
        mysqli_stmt_bind_param($stmt_inc, "ss", $reg['r_course_code'], $reg['r_section']);
        mysqli_stmt_execute($stmt_inc);
        
        mysqli_commit($con);
        
        // Send approval email
        sendApprovalEmail(
            $reg['u_email'],
            $reg['u_name'],
            $reg['r_course_code'],
            $reg['c_name'],
            $reg['r_section']
        );
        
        echo "<script>alert('Registration approved and email notification sent'); window.history.back();</script>";
        
    } catch (Exception $e) {
        mysqli_rollback($con);
        echo "<script>alert('Error: " . htmlspecialchars($e->getMessage()) . "'); window.history.back();</script>";
    }
} else {
    header("Location: admin_dashboard.php");
}
?>
