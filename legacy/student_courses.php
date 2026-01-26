<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['03']);
include 'headerstudent.php';

$student_id = $_SESSION['u_id'];
$sid = isset($_GET['sid']) ? $_GET['sid'] : '';

// Get all registrations ordered by semester desc
$sql = "SELECT r.*, c.c_name, c.c_credit, c.c_section, u.u_name as lecturer_name, s.sem_year, s.sem_name
        FROM tb_registration r 
        JOIN tb_course c ON r.r_course_code = c.c_code AND r.r_section = c.c_section
        LEFT JOIN tb_user u ON c.c_lecturer_id = u.u_id
        LEFT JOIN tb_semester s ON c.c_semester_id = s.sem_id
        WHERE r.r_student_id = ?";

if (!empty($sid)) {
    $sql .= " AND c.c_semester_id = ?";
}
$sql .= " ORDER BY s.sem_year DESC, s.sem_name, r.r_course_code ASC";

$stmt = mysqli_prepare($con, $sql);
if (!empty($sid)) {
    mysqli_stmt_bind_param($stmt, "ii", $student_id, $sid);
} else {
    mysqli_stmt_bind_param($stmt, "i", $student_id);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$courses_by_semester = [];
while ($row = mysqli_fetch_assoc($result)) {
    $sem_label = $row['sem_year'] ? $row['sem_year'].' '.$row['sem_name'] : $row['r_semester'];
    $courses_by_semester[$sem_label][] = $row;
}
?>

<div class="row">
    <div class="col-lg-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>My Courses</h2>
            <a href="student_register.php" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Register for New Course
            </a>
        </div>
        <!-- 5b. View current and previous semester courses -->
        
        <?php if (empty($courses_by_semester)): ?>
            <div class="alert alert-info">You have not registered for any courses yet.</div>
            <a href="student_register.php" class="btn btn-primary">Register New Course</a>
        <?php else: ?>
        
            <?php foreach ($courses_by_semester as $semester => $courses): ?>
                <div class="card mb-4 mt-3">
                    <div class="card-header bg-secondary text-white">Semester: <?php echo htmlspecialchars($semester); ?></div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Course Name</th>
                                    <th>Credit</th>
                                    <th>Section</th>
                                    <th>Lecturer</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($course['r_course_code']); ?></td>
                                    <td><?php echo htmlspecialchars($course['c_name']); ?></td>
                                    <td><?php echo htmlspecialchars($course['c_credit']); ?></td>
                                    <td><?php echo htmlspecialchars($course['r_section']); ?></td>
                                    <td><?php echo htmlspecialchars($course['lecturer_name'] ?? 'TBA'); ?></td>
                                    <td>
                                        <span class="badge <?php echo $course['r_status'] == 'Approved' ? 'bg-success' : 'bg-warning'; ?>">
                                            <?php echo htmlspecialchars($course['r_status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($course['r_status'] != 'Cancelled'): ?>
                                        <form method="POST" action="student_course_cancel.php" onsubmit="return confirm('Are you sure you want to cancel/drop this course?');" style="display:inline;">
                                            <input type="hidden" name="r_id" value="<?php echo $course['r_id']; ?>">
                                            <input type="hidden" name="c_code" value="<?php echo $course['r_course_code']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                                        </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
            
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

