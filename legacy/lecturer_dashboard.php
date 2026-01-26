<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['02']);
include 'headerlecturer.php';

// Get lecturers's faculty
$l_id = $_SESSION['u_id'];
$l_prog = $_SESSION['u_programme'];
$l_q = mysqli_query($con, "SELECT p_faculty FROM tb_program WHERE p_id = '$l_prog'");
$l_row = mysqli_fetch_assoc($l_q);
$faculty_id = $l_row['p_faculty'];

// Get semesters for this faculty
$sql_semesters = "SELECT s.*,
                  (SELECT COUNT(*) FROM tb_course c 
                   WHERE c.c_semester_id = s.sem_id AND c.c_lecturer_id = ?) as course_count
                  FROM tb_semester s
                  WHERE s.sem_faculty = ?
                  ORDER BY s.sem_year DESC, s.sem_name";

$stmt = mysqli_prepare($con, $sql_semesters);
mysqli_stmt_bind_param($stmt, "is", $l_id, $faculty_id);
mysqli_stmt_execute($stmt);
$semesters_result = mysqli_stmt_get_result($stmt);
?>

<div class="row">
    <div class="col-lg-12">
        <h2>Lecturer Dashboard</h2><br>
        
        <div class="row mt-4">
            <?php while ($semester = mysqli_fetch_assoc($semesters_result)): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-info">
                    <div class="card-header bg-success text-white">
                        
                        <h5 class="mb-0"><?php echo htmlspecialchars($semester['sem_year']); ?></h5>
                        <small><?php echo htmlspecialchars($semester['sem_name']); ?></small>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 text-center">
                            <h3 class="text-info"><?php echo $semester['course_count']; ?></h3>
                            <p class="text-muted">Assigned Courses</p>
                        </div>
                        <div class="d-grid">
                            <a href="lecturer_courses.php?sid=<?php echo $semester['sem_id']; ?>" class="btn btn-primary">
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
                    <i class="bi bi-calendar-x display-4 text-muted"></i>
                    <p class="mt-2">No semesters found for your faculty.</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
