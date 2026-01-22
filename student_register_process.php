<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['03']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_code = $_POST['course_code'];
    $section = $_POST['section'];
    $semester = $_POST['semester'];
    $student_id = $_SESSION['u_id'];
    
    mysqli_begin_transaction($con);
    
    try {
        // Check if already registered for this course (any section) in this semester
        $check_sql = "SELECT r_id FROM tb_registration 
                      WHERE r_student_id = ? AND r_course_code = ? AND r_semester = ? 
                      AND r_status != 'Cancelled'";
        $check_stmt = mysqli_prepare($con, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "iss", $student_id, $course_code, $semester);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);
        
        if (mysqli_num_rows($check_result) > 0) {
            throw new Exception("You are already registered for this course in this semester.");
        }
        
        // Check if section is full
        $capacity_sql = "SELECT c_max_students, c_current_students 
                         FROM tb_course 
                         WHERE c_code = ? AND c_section = ?";
        $capacity_stmt = mysqli_prepare($con, $capacity_sql);
        mysqli_stmt_bind_param($capacity_stmt, "ss", $course_code, $section);
        mysqli_stmt_execute($capacity_stmt);
        $capacity_result = mysqli_stmt_get_result($capacity_stmt);
        $capacity = mysqli_fetch_assoc($capacity_result);
        
        if ($capacity['c_current_students'] >= $capacity['c_max_students']) {
            throw new Exception("This section is full.");
        }
        
        // Insert registration (Auto-Approved if space available)
        $insert_sql = "INSERT INTO tb_registration (r_student_id, r_course_code, r_section, r_semester, r_status) 
                       VALUES (?, ?, ?, ?, 'Approved')";
        $insert_stmt = mysqli_prepare($con, $insert_sql);
        mysqli_stmt_bind_param($insert_stmt, "isss", $student_id, $course_code, $section, $semester);
        mysqli_stmt_execute($insert_stmt);
        
        // Update course enrollment count
        $update_sql = "UPDATE tb_course 
                       SET c_current_students = c_current_students + 1 
                       WHERE c_code = ? AND c_section = ?";
        $update_stmt = mysqli_prepare($con, $update_sql);
        mysqli_stmt_bind_param($update_stmt, "ss", $course_code, $section);
        mysqli_stmt_execute($update_stmt);
        
        mysqli_commit($con);
        echo "<script>alert('Successfully registered for " . htmlspecialchars($course_code) . " Section " . htmlspecialchars($section) . "'); window.location.href='student_courses.php';</script>";
        
    } catch (Exception $e) {
        mysqli_rollback($con);
        echo "<script>alert('Error: " . htmlspecialchars($e->getMessage()) . "'); window.location.href='student_register.php';</script>";
    }
} else {
    header("Location: student_register.php");
}
?>
