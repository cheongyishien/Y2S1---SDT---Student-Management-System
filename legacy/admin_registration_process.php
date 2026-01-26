<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['01']);

checkRole(['01']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $r_id = $_POST['r_id'];

    if ($action == 'update') {
        $old_code = $_POST['old_course_code'];
        $new_code = $_POST['new_course_code'];
        $status = $_POST['r_status'];

        mysqli_begin_transaction($con);
        try {
            if ($old_code != $new_code) {
                // 1. Check capacity of new course
                $stmt = mysqli_prepare($con, "SELECT c_current_students, c_max_students FROM tb_course WHERE c_code = ? FOR UPDATE");
                mysqli_stmt_bind_param($stmt, "s", $new_code);
                mysqli_stmt_execute($stmt);
                $res = mysqli_stmt_get_result($stmt);
                $course = mysqli_fetch_assoc($res);
                if ($course['c_current_students'] >= $course['c_max_students']) {
                    throw new Exception("New course is full.");
                }

                // 2. Decrement old course
                $dec = mysqli_prepare($con, "UPDATE tb_course SET c_current_students = c_current_students - 1 WHERE c_code = ?");
                mysqli_stmt_bind_param($dec, "s", $old_code);
                mysqli_stmt_execute($dec);

                // 3. Increment new course
                $inc = mysqli_prepare($con, "UPDATE tb_course SET c_current_students = c_current_students + 1 WHERE c_code = ?");
                mysqli_stmt_bind_param($inc, "s", $new_code);
                mysqli_stmt_execute($inc);
            }

            // 4. Update Registration
            $upd = mysqli_prepare($con, "UPDATE tb_registration SET r_course_code = ?, r_status = ? WHERE r_id = ?");
            mysqli_stmt_bind_param($upd, "ssi", $new_code, $status, $r_id);
            mysqli_stmt_execute($upd);

            mysqli_commit($con);
            echo "<script>alert('Registration Updated Successfully'); window.location.href='admin_manage_registrations.php';</script>";

        } catch (Exception $e) {
            mysqli_rollback($con);
            echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='admin_manage_registrations.php';</script>";
        }

    } elseif ($action == 'cancel') {

    mysqli_begin_transaction($con);
    try {
        // Get course code first to decrement count
        $stmt = mysqli_prepare($con, "SELECT r_course_code FROM tb_registration WHERE r_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $r_id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($res)) {
            $c_code = $row['r_course_code'];
            
            // Delete registration
            $del = mysqli_prepare($con, "DELETE FROM tb_registration WHERE r_id = ?");
            mysqli_stmt_bind_param($del, "i", $r_id);
            mysqli_stmt_execute($del);
            
            // Decrement course count
            $upd = mysqli_prepare($con, "UPDATE tb_course SET c_current_students = c_current_students - 1 WHERE c_code = ?");
            mysqli_stmt_bind_param($upd, "s", $c_code);
            mysqli_stmt_execute($upd);
            
            mysqli_commit($con);
            echo "<script>alert('Registration Cancelled Successfully'); window.location.href='admin_manage_registrations.php';</script>";
        } else {
            throw new Exception("Registration not found");
        }
    } catch (Exception $e) {
        mysqli_rollback($con);
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='admin_manage_registrations.php';</script>";
    }
    }
} else {
    header("Location: admin_manage_registrations.php");
}
?>
