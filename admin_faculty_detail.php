<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['01']);
include 'headerstaff.php';

$faculty_id = $_GET['fid'] ?? '';

if (empty($faculty_id)) {
    header("Location: admin_dashboard.php");
    exit;
}

// Get faculty info
$sql_faculty = "SELECT * FROM tb_faculty WHERE f_id = ?";
$stmt = mysqli_prepare($con, $sql_faculty);
mysqli_stmt_bind_param($stmt, "s", $faculty_id);
mysqli_stmt_execute($stmt);
$faculty_result = mysqli_stmt_get_result($stmt);
$faculty = mysqli_fetch_assoc($faculty_result);

if (!$faculty) {
    header("Location: admin_dashboard.php");
    exit;
}

// Get semesters for this faculty
$sql_semesters = "SELECT s.*,
                  (SELECT COUNT(*) FROM tb_course c WHERE c.c_semester_id = s.sem_id) as course_count,
                  (SELECT COUNT(DISTINCT r.r_student_id) 
                   FROM tb_registration r 
                   INNER JOIN tb_course c ON r.r_course_code = c.c_code 
                   WHERE c.c_semester_id = s.sem_id AND r.r_status != 'Cancelled') as student_count
                  FROM tb_semester s
                  WHERE s.sem_faculty = ?
                  ORDER BY s.sem_year DESC, s.sem_name";

$stmt = mysqli_prepare($con, $sql_semesters);
mysqli_stmt_bind_param($stmt, "s", $faculty_id);
mysqli_stmt_execute($stmt);
$semesters_result = mysqli_stmt_get_result($stmt);
?>

<div class="row">
    <div class="col-lg-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active"><?php echo htmlspecialchars($faculty['f_name']); ?></li>
            </ol>
        </nav>
        
        <h2><?php echo htmlspecialchars($faculty['f_name']); ?></h2>
        <p class="text-muted">Manage semesters for this faculty</p>
        
        <div class="mb-3">
            <button class="btn btn-success" onclick="window.location.href='admin_semester_form.php?fid=<?php echo urlencode($faculty_id); ?>'">
                <i class="bi bi-plus-circle"></i> Add New Semester
            </button>
        </div>
        
        <div class="row">
            <?php while ($semester = mysqli_fetch_assoc($semesters_result)): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header <?php echo $semester['sem_status'] == 'Active' ? 'bg-success' : 'bg-secondary'; ?> text-white">
                        <h5 class="mb-0"><?php echo htmlspecialchars($semester['sem_year']); ?></h5>
                        <small><?php echo htmlspecialchars($semester['sem_name']); ?></small>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <span class="badge <?php echo $semester['sem_status'] == 'Active' ? 'bg-success' : 'bg-secondary'; ?>">
                                <?php echo htmlspecialchars($semester['sem_status']); ?>
                            </span>
                        </div>
                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <h4 class="text-primary"><?php echo $semester['course_count']; ?></h4>
                                <small class="text-muted">Courses</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-info"><?php echo $semester['student_count']; ?></h4>
                                <small class="text-muted">Students</small>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" onclick="window.location.href='admin_semester_courses.php?sid=<?php echo $semester['sem_id']; ?>'">
                                View Courses
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="window.location.href='admin_semester_form.php?sid=<?php echo $semester['sem_id']; ?>'">
                                Edit Semester
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
            
            <?php if (mysqli_num_rows($semesters_result) == 0): ?>
            <div class="col-12">
                <div class="alert alert-info">
                    No semesters found for this faculty. Click "Add New Semester" to create one.
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
