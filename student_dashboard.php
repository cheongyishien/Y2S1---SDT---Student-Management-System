<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['03']);
include 'headerstudent.php';

$s_id = $_SESSION['u_id'];
$s_prog = $_SESSION['u_programme'];

// Get student's faculty
$s_q = mysqli_query($con, "SELECT p_faculty FROM tb_program WHERE p_id = '$s_prog'");
$s_row = mysqli_fetch_assoc($s_q);
$faculty_id = $s_row['p_faculty'];

// Get semesters for this faculty
$sql_semesters = "SELECT s.*,
                  (SELECT COUNT(*) FROM tb_registration r 
                   INNER JOIN tb_course c ON r.r_course_code = c.c_code AND r.r_section = c.c_section
                   WHERE c.c_semester_id = s.sem_id AND r.r_student_id = ? AND r.r_status != 'Cancelled') as course_count
                  FROM tb_semester s
                  WHERE s.sem_faculty = ?
                  ORDER BY s.sem_year DESC, s.sem_name";

$stmt = mysqli_prepare($con, $sql_semesters);
mysqli_stmt_bind_param($stmt, "is", $s_id, $faculty_id);
mysqli_stmt_execute($stmt);
$semesters_result = mysqli_stmt_get_result($stmt);
?>

<div class="row">
    <div class="col-lg-12">
        <h2>Student Dashboard</h2><br>
        
        <div class="row mt-4">
            <?php while ($semester = mysqli_fetch_assoc($semesters_result)): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><?php echo htmlspecialchars($semester['sem_year']); ?></h5>
                        <small><?php echo htmlspecialchars($semester['sem_name']); ?></small>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 text-center">
                            <h3 class="text-success"><?php echo $semester['course_count']; ?></h3>
                            <p class="text-muted">Registered Courses</p>
                        </div>
                        <div class="d-grid">
                            <a href="student_courses.php?sid=<?php echo $semester['sem_id']; ?>" class="btn btn-primary">
                                View My Courses
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
            
            <?php if (mysqli_num_rows($semesters_result) == 0): ?>
            <div class="col-12 text-center">
                <div class="alert alert-light border">
                    <i class="bi bi-journal-x display-4 text-muted"></i>
                    <p class="mt-2">No registered courses found.</p>
                    <a href="student_register.php" class="btn btn-success">Register for a Course</a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
