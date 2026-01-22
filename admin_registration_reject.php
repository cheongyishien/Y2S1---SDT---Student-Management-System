<?php
include 'includes/db.php';
include 'includes/auth.php';
include 'includes/email.php';
checkSession();
checkRole(['01']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $r_id = $_POST['r_id'];
    $rejection_reason = $_POST['rejection_reason'];
    $send_email = isset($_POST['send_email']);
    $student_email = $_POST['student_email'];
    $course_code = $_POST['course_code'];
    $course_name = $_POST['course_name'];
    $section = $_POST['section'];
    $admin_id = $_SESSION['u_id'];
    
    mysqli_begin_transaction($con);
    
    try {
        // Get student info
        $sql_student = "SELECT u.u_name, r.r_course_code, r.r_section
                        FROM tb_registration r
                        INNER JOIN tb_user u ON r.r_student_id = u.u_id
                        WHERE r.r_id = ?";
        $stmt = mysqli_prepare($con, $sql_student);
        mysqli_stmt_bind_param($stmt, "i", $r_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $student = mysqli_fetch_assoc($result);
        
        if (!$student) {
            throw new Exception("Registration not found");
        }
        
        // Update registration status
        $sql_update = "UPDATE tb_registration 
                       SET r_status = 'Rejected', 
                           r_rejection_reason = ?, 
                           r_rejected_by = ?,
                           r_rejected_at = NOW()
                       WHERE r_id = ?";
        $stmt = mysqli_prepare($con, $sql_update);
        mysqli_stmt_bind_param($stmt, "sii", $rejection_reason, $admin_id, $r_id);
        mysqli_stmt_execute($stmt);
        
        // Decrement course enrollment
        $sql_decrement = "UPDATE tb_course 
                          SET c_current_students = c_current_students - 1 
                          WHERE c_code = ? AND c_section = ? AND c_current_students > 0";
        $stmt = mysqli_prepare($con, $sql_decrement);
        mysqli_stmt_bind_param($stmt, "ss", $student['r_course_code'], $student['r_section']);
        mysqli_stmt_execute($stmt);
        
        mysqli_commit($con);
        
        // Send email notification
        if ($send_email) {
            $emailSent = sendRejectionEmail(
                $student_email,
                $student['u_name'],
                $course_code,
                $course_name,
                $section,
                $rejection_reason
            );
            
            if ($emailSent) {
                echo "<script>alert('Registration rejected and email sent to student'); window.location.href='admin_manage_section_registrations.php?cid=" . urlencode($course_code) . "&section=" . urlencode($section) . "';</script>";
            } else {
                echo "<script>alert('Registration rejected but email failed to send'); window.location.href='admin_manage_section_registrations.php?cid=" . urlencode($course_code) . "&section=" . urlencode($section) . "';</script>";
            }
        } else {
            echo "<script>alert('Registration rejected successfully'); window.location.href='admin_manage_section_registrations.php?cid=" . urlencode($course_code) . "&section=" . urlencode($section) . "';</script>";
        }
        
    } catch (Exception $e) {
        mysqli_rollback($con);
        echo "<script>alert('Error: " . htmlspecialchars($e->getMessage()) . "'); window.history.back();</script>";
    }
} else {
    header("Location: admin_dashboard.php");
}
?>
