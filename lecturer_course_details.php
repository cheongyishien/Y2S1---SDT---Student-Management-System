<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['02']);
include 'headerlecturer.php';

$c_code = $_GET['cid'] ?? '';
$section = $_GET['section'] ?? '';

if (empty($c_code) || empty($section)) {
    echo "Invalid course details.";
    exit;
}

// Get course details
$sql = "SELECT c.*, s.sem_name, s.sem_year, f.f_name as faculty_name 
        FROM tb_course c 
        LEFT JOIN tb_semester s ON c.c_semester_id = s.sem_id 
        LEFT JOIN tb_faculty f ON c.c_faculty = f.f_id 
        WHERE c.c_code = ? AND c.c_section = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "ss", $c_code, $section);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$course = mysqli_fetch_assoc($res);

if (!$course) {
    echo "Course not found.";
    exit;
}

// Get enrolled students (Approved only)
$sql_students = "SELECT u.u_name, u.u_email, u.u_id, r.r_status 
                 FROM tb_registration r 
                 JOIN tb_user u ON r.r_student_id = u.u_id 
                 WHERE r.r_course_code = ? AND r.r_section = ? AND r.r_status = 'Approved'";
$stmt_s = mysqli_prepare($con, $sql_students);
mysqli_stmt_bind_param($stmt_s, "ss", $c_code, $section);
mysqli_stmt_execute($stmt_s);
$students_res = mysqli_stmt_get_result($stmt_s);
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><?php echo htmlspecialchars($course['c_code'] . ' - ' . $course['c_name']); ?></h4>
                <a href="lecturer_courses.php" class="btn btn-light btn-sm">Back to My Courses</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Section:</strong> <?php echo htmlspecialchars($course['c_section']); ?></p>
                        <p><strong>Credit:</strong> <?php echo htmlspecialchars($course['c_credit']); ?></p>
                        <p><strong>Semester:</strong> <?php echo htmlspecialchars($course['sem_year'] . ' ' . $course['sem_name']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Faculty:</strong> <?php echo htmlspecialchars($course['faculty_name'] ?? 'N/A'); ?></p>
                        <p><strong>Capacity:</strong> <?php echo htmlspecialchars($course['c_current_students'] . ' / ' . $course['c_max_students']); ?></p>
                        <p><strong>Programmes:</strong> <?php echo htmlspecialchars($course['c_programmes'] ?: 'All'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <h3>Enrolled Students (Approved)</h3>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($students_res) > 0): ?>
                        <?php while ($student = mysqli_fetch_assoc($students_res)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['u_name']); ?></td>
                            <td><?php echo htmlspecialchars($student['u_email']); ?></td>
                            <td>
                                <a href="lecturer_student_details.php?u_id=<?php echo $student['u_id']; ?>" class="btn btn-info btn-sm">View Details</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">No approved students enrolled yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
