<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['02']);
include 'headerlecturer.php';

$c_code = $_GET['c_code'];

// 6c. View course details
$stmt = mysqli_prepare($con, "SELECT c.*, u.u_name as lecturer_name FROM tb_course c LEFT JOIN tb_user u ON c.c_lecturer_id = u.u_id WHERE c_code = ?");
mysqli_stmt_bind_param($stmt, "s", $c_code);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$course = mysqli_fetch_assoc($result);

if (!$course) {
    echo "Course not found.";
    exit;
}
?>

<div class="container mt-4">
    <h2>Course Details: <?php echo htmlspecialchars($course['c_code']); ?></h2>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($course['c_name']); ?></h5>
            <p class="card-text"><strong>Credit:</strong> <?php echo $course['c_credit']; ?></p>
            <p class="card-text"><strong>Section:</strong> <?php echo $course['c_section']; ?></p>
            <p class="card-text"><strong>Capacity:</strong> <?php echo $course['c_current_students']; ?> / <?php echo $course['c_max_students']; ?></p>
            <p class="card-text"><strong>Lecturer:</strong> <?php echo htmlspecialchars($course['lecturer_name']); ?></p>
            
            <a href="lecturer_student_list.php?c_code=<?php echo $course['c_code']; ?>" class="btn btn-info">View Registered Students</a>
            <a href="lecturer_courses.php" class="btn btn-secondary">Back to Courses</a>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?>
