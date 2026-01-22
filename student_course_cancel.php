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
        // Verify ownership
        $check = mysqli_prepare($con, "SELECT r_id FROM tb_registration WHERE r_id = ? AND r_student_id = ?");
        mysqli_stmt_bind_param($check, "ii", $r_id, $student_id);
        mysqli_stmt_execute($check);
        $res = mysqli_stmt_get_result($check);
        if (mysqli_num_rows($res) == 0) {
            throw new Exception("Invalid registration.");
        }

        // Delete Registration (or set status to Cancelled)
        $del = mysqli_prepare($con, "DELETE FROM tb_registration WHERE r_id = ?");
        mysqli_stmt_bind_param($del, "i", $r_id);
        mysqli_stmt_execute($del);

        // Decrement course count
        $update = mysqli_prepare($con, "UPDATE tb_course SET c_current_students = c_current_students - 1 WHERE c_code = ?");
        mysqli_stmt_bind_param($update, "s", $c_code);
        mysqli_stmt_execute($update);

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
