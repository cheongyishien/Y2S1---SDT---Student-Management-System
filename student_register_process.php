<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['03']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['course_code'])) {
    $course_code = $_POST['course_code'];
    $student_id = $_SESSION['u_id'];
    
    // Begin Transaction
    mysqli_begin_transaction($con);
    
    try {
        // 1. Lock course row to prevent race condition (using FOR UPDATE)
        $stmt = mysqli_prepare($con, "SELECT c_current_students, c_max_students FROM tb_course WHERE c_code = ? FOR UPDATE");
        mysqli_stmt_bind_param($stmt, "s", $course_code);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $course = mysqli_fetch_assoc($result);
        
        if (!$course) {
            throw new Exception("Course not found.");
        }
        
        // 2. Check Capacity
        if ($course['c_current_students'] >= $course['c_max_students']) {
            throw new Exception("Course is full.");
        }

        // 2.5 Check if already registered
        $checkStmt = mysqli_prepare($con, "SELECT r_id FROM tb_registration WHERE r_student_id = ? AND r_course_code = ?");
        mysqli_stmt_bind_param($checkStmt, "is", $student_id, $course_code);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);
        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            throw new Exception("You are already registered for this course.");
        }
        
        // 3. Register Student
        // 8g. Auto approved registration if number of students do not reach the maximum.
        $status = 'Approved'; 
        
        $insertStmt = mysqli_prepare($con, "INSERT INTO tb_registration (r_student_id, r_course_code, r_semester, r_status) VALUES (?, ?, '202520261', ?)");
        // Using a dummy semester code '202520261' for now
        mysqli_stmt_bind_param($insertStmt, "iss", $student_id, $course_code, $status);
        if (!mysqli_stmt_execute($insertStmt)) {
             throw new Exception("Already registered or error.");
        }
        
        // 4. Update Course Count
        $updateStmt = mysqli_prepare($con, "UPDATE tb_course SET c_current_students = c_current_students + 1 WHERE c_code = ?");
        mysqli_stmt_bind_param($updateStmt, "s", $course_code);
        mysqli_stmt_execute($updateStmt);
        
        mysqli_commit($con);
        echo "<script>alert('Successfully Registered!'); window.location.href='student_courses.php';</script>";
        
    } catch (Exception $e) {
        mysqli_rollback($con);
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='student_register.php';</script>";
    }
} else {
    header("Location: student_register.php");
}
?>
