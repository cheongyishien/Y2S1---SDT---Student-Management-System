<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['02']);
include 'headerlecturer.php';

$c_code = $_GET['c_code'];

// Verify ownership (Lecturer can only view their own courses)
$check = mysqli_prepare($con, "SELECT c_name FROM tb_course WHERE c_code = ? AND c_lecturer_id = ?");
mysqli_stmt_bind_param($check, "si", $c_code, $_SESSION['u_id']);
mysqli_stmt_execute($check);
$res = mysqli_stmt_get_result($check);
if (mysqli_num_rows($res) == 0) {
    echo "<div class='alert alert-danger'>Access Denied or Course Not Found.</div>";
    include 'views/footer.php';
    exit();
}
$course = mysqli_fetch_assoc($res);
?>

<div class="row">
    <div class="col-lg-12">
        <h2>Student List for <?php echo htmlspecialchars($course['c_name']); ?> (<?php echo htmlspecialchars($c_code); ?>)</h2>
        <a href="lecturer_courses.php" class="btn btn-secondary mb-3">Back to Courses</a>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Timestamp</th>
                    <th>Programme</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // 6b. View student list
                // 6d. View updated student details (just basic info for now)
                $sql = "SELECT u.u_name, u.u_email, r.r_status, r.r_timestamp 
                        FROM tb_registration r
                        JOIN tb_user u ON r.r_student_id = u.u_id
                        WHERE r.r_course_code = ?
                        ORDER BY u.u_name";
                $stmt = mysqli_prepare($con, $sql);
                mysqli_stmt_bind_param($stmt, "s", $c_code);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>{$row['u_name']}</td>";
                        echo "<td>{$row['u_email']}</td>";
                        echo "<td>{$row['r_status']}</td>";
                        echo "<td>{$row['r_timestamp']}</td>";
                        echo "<td>{$row['u_programme']}</td>";
                        echo "<td><a href='lecturer_student_details.php?u_id={$row['u_id']}' class='btn btn-secondary btn-sm'>View Details</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No students registered yet.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'views/footer.php'; ?>
