<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['01']);
include 'headerstaff.php';

// Get faculty statistics
$sql_faculties = "SELECT f.f_id, f.f_name,
                  (SELECT COUNT(DISTINCT s.sem_id) FROM tb_semester s WHERE s.sem_faculty = f.f_id) as semester_count,
                  (SELECT COUNT(*) FROM tb_course c WHERE c.c_faculty = f.f_id) as course_count,
                  (SELECT COUNT(DISTINCT r.r_student_id) 
                   FROM tb_registration r 
                   INNER JOIN tb_course c ON r.r_course_code = c.c_code 
                   WHERE c.c_faculty = f.f_id AND r.r_status != 'Cancelled') as student_count
                  FROM tb_faculty f
                  WHERE f.f_id != ''
                  ORDER BY f.f_name";

$faculties_result = mysqli_query($con, $sql_faculties);
?>

<div class="row">
    <div class="col-lg-12">
        <h2>Admin Dashboard</h2><br>
        
        <div class="mb-3">
            <button class="btn btn-success" onclick="window.location.href='admin_faculty_form.php'">
                <i class="bi bi-plus-circle"></i> Add New Faculty
            </button>
        </div>
        
        <div class="row">
            <?php while ($faculty = mysqli_fetch_assoc($faculties_result)): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><?php echo htmlspecialchars($faculty['f_name']); ?></h5>
                        <small><?php echo htmlspecialchars($faculty['f_id']); ?></small>
                    </div>
                    <div class="card-body">
                        <div class="row text-center mb-3">
                            <div class="col-4">
                                <h4 class="text-primary"><?php echo $faculty['semester_count']; ?></h4>
                                <small class="text-muted">Semesters</small>
                            </div>
                            <div class="col-4">
                                <h4 class="text-success"><?php echo $faculty['course_count']; ?></h4>
                                <small class="text-muted">Courses</small>
                            </div>
                            <div class="col-4">
                                <h4 class="text-info"><?php echo $faculty['student_count']; ?></h4>
                                <small class="text-muted">Students</small>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" onclick="window.location.href='admin_faculty_detail.php?fid=<?php echo urlencode($faculty['f_id']); ?>'">
                                View Semesters
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="window.location.href='admin_faculty_form.php?fid=<?php echo urlencode($faculty['f_id']); ?>'">
                                Edit Faculty
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
