<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['01']);
include 'headeradmin.php';

$semester_id = $_GET['sid'] ?? '';

if (empty($semester_id)) {
    header("Location: admin_dashboard.php");
    exit;
}

// Get semester info
$sql_semester = "SELECT s.*, f.f_name 
                 FROM tb_semester s
                 INNER JOIN tb_faculty f ON s.sem_faculty = f.f_id
                 WHERE s.sem_id = ?";
$stmt = mysqli_prepare($con, $sql_semester);
mysqli_stmt_bind_param($stmt, "i", $semester_id);
mysqli_stmt_execute($stmt);
$semester_result = mysqli_stmt_get_result($stmt);
$semester = mysqli_fetch_assoc($semester_result);

if (!$semester) {
    header("Location: admin_dashboard.php");
    exit;
}

// Get courses for this semester
$sql_courses = "SELECT c.*, u.u_name as lecturer_name,
                (SELECT COUNT(*) FROM tb_registration r 
                 WHERE r.r_course_code = c.c_code AND r.r_section = c.c_section 
                 AND r.r_status = 'Approved') as enrolled_count
                FROM tb_course c
                LEFT JOIN tb_user u ON c.c_lecturer_id = u.u_id
                WHERE c.c_semester_id = ?
                ORDER BY c.c_code, c.c_section";

$stmt = mysqli_prepare($con, $sql_courses);
mysqli_stmt_bind_param($stmt, "i", $semester_id);
mysqli_stmt_execute($stmt);
$courses_result = mysqli_stmt_get_result($stmt);
?>

<div class="row">
    <div class="col-lg-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="admin_faculty_detail.php?fid=<?php echo urlencode($semester['sem_faculty']); ?>"><?php echo htmlspecialchars($semester['f_name']); ?></a></li>
                <li class="breadcrumb-item active"><?php echo htmlspecialchars($semester['sem_year'] . ' ' . $semester['sem_name']); ?></li>
            </ol>
        </nav>
        
        <h2><?php echo htmlspecialchars($semester['sem_year'] . ' - ' . $semester['sem_name']); ?></h2>
        <p class="text-muted"><?php echo htmlspecialchars($semester['f_name']); ?></p>
        
        <div class="mb-3">
            <button class="btn btn-success" onclick="window.location.href='admin_course_form.php?sid=<?php echo $semester_id; ?>'">
                <i class="bi bi-plus-circle"></i> Add New Course
            </button>
        </div>
        
        <?php if (mysqli_num_rows($courses_result) == 0): ?>
            <div class="alert alert-info">No courses found for this semester.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Section</th>
                            <th>Credit</th>
                            <th>Lecturer</th>
                            <th>Enrolled / Max</th>
                            <th>Programmes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($course = mysqli_fetch_assoc($courses_result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($course['c_code']); ?></td>
                            <td><?php echo htmlspecialchars($course['c_name']); ?></td>
                            <td><?php echo htmlspecialchars($course['c_section']); ?></td>
                            <td><?php echo htmlspecialchars($course['c_credit']); ?></td>
                            <td><?php echo htmlspecialchars($course['lecturer_name'] ?? 'TBA'); ?></td>
                            <td>
                                <span class="<?php echo $course['enrolled_count'] >= $course['c_max_students'] ? 'text-danger' : 'text-success'; ?>">
                                    <?php echo $course['enrolled_count']; ?> / <?php echo $course['c_max_students']; ?>
                                </span>
                            </td>
                            <td>
                                <?php 
                                if (empty($course['c_programmes'])) {
                                    echo '<span class="badge bg-secondary">All</span>';
                                } else {
                                    $progs = explode(',', $course['c_programmes']);
                                    foreach ($progs as $prog) {
                                        echo '<span class="badge bg-info me-1">' . htmlspecialchars(trim($prog)) . '</span>';
                                    }
                                }
                                ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-primary" onclick="window.location.href='admin_course_form.php?cid=<?php echo urlencode($course['c_code']); ?>&section=<?php echo urlencode($course['c_section']); ?>&sid=<?php echo $semester_id; ?>'">
                                        Edit
                                    </button>
                                    <button class="btn btn-info" onclick="window.location.href='admin_manage_section_registrations.php?cid=<?php echo urlencode($course['c_code']); ?>&section=<?php echo urlencode($course['c_section']); ?>'">
                                        Registrations
                                    </button>
                                    <button class="btn btn-danger" onclick="if(confirm('Are you sure you want to delete this course section?')) window.location.href='admin_course_process.php?action=delete&cid=<?php echo urlencode($course['c_code']); ?>&section=<?php echo urlencode($course['c_section']); ?>&sid=<?php echo $semester_id; ?>'">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
