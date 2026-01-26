<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['03']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['r_id'])) {
    $r_id = $_POST['r_id'];
    $c_code = $_POST['c_code'];
    $student_id = $_SESSION['u_id'];

    mysqli_begin_transaction($con);

    try {
        // Get registration status and section
        $sql_info = "SELECT r_status, r_section FROM tb_registration WHERE r_id = ? AND r_student_id = ?";
        $stmt_info = mysqli_prepare($con, $sql_info);
        mysqli_stmt_bind_param($stmt_info, "ii", $r_id, $student_id);
        mysqli_stmt_execute($stmt_info);
        $res_info = mysqli_stmt_get_result($stmt_info);
        $reg = mysqli_fetch_assoc($res_info);

        if (!$reg) {
            throw new Exception("Registration not found.");
        }

        $should_decrement = ($reg['r_status'] == 'Approved');
        $section = $reg['r_section'];

        // Delete Registration (or set status to Cancelled)
        $del = mysqli_prepare($con, "DELETE FROM tb_registration WHERE r_id = ?");
        mysqli_stmt_bind_param($del, "i", $r_id);
        mysqli_stmt_execute($del);

        // Decrement course count if it was approved
        if ($should_decrement) {
            $update = mysqli_prepare($con, "UPDATE tb_course SET c_current_students = c_current_students - 1 WHERE c_code = ? AND c_section = ? AND c_current_students > 0");
            mysqli_stmt_bind_param($update, "ss", $c_code, $section);
            mysqli_stmt_execute($update);
        }

        mysqli_commit($con);
        echo "<script>alert('Registration Cancelled.'); window.location.href='student_courses.php';</script>";

    } catch (Exception $e) {
        mysqli_rollback($con);
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='student_courses.php';</script>";
    }
} else {
    header("Location: student_courses.php");
}
?>
