<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['03']);

header('Content-Type: application/json');

$course_code = $_GET['course_code'] ?? '';
$student_id = $_SESSION['u_id'];

if (empty($course_code)) {
    echo json_encode(['error' => 'Course code is required']);
    exit;
}

// Get all sections for this course with lecturer info and check if student is already registered
$sql = "SELECT c.c_code, c.c_section, c.c_max_students, c.c_current_students, c.c_semester,
        u.u_name as lecturer_name,
        (SELECT COUNT(*) FROM tb_registration 
         WHERE r_student_id = ? AND r_course_code = c.c_code AND r_section = c.c_section 
         AND r_status != 'Cancelled') as is_registered
        FROM tb_course c
        LEFT JOIN tb_user u ON c.c_lecturer_id = u.u_id
        WHERE c.c_code = ?
        ORDER BY c.c_section";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "is", $student_id, $course_code);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$sections = [];
while ($row = mysqli_fetch_assoc($result)) {
    $sections[] = $row;
}

echo json_encode(['sections' => $sections]);
?>
