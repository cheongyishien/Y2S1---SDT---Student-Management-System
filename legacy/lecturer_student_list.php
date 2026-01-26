<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['02']);
include 'headerlecturer.php';

$c_code = $_GET['c_code'];
$section = isset($_GET['section']) ? $_GET['section'] : '1';

// Verify ownership (Lecturer can only view their own courses)
$check = mysqli_prepare($con, "SELECT c_name FROM tb_course WHERE c_code = ? AND c_section = ? AND c_lecturer_id = ?");
mysqli_stmt_bind_param($check, "ssi", $c_code, $section, $_SESSION['u_id']);
mysqli_stmt_execute($check);
$res = mysqli_stmt_get_result($check);
if (mysqli_num_rows($res) == 0) {
    echo "<div class='alert alert-danger'>Access Denied or Course Not Found.</div>";
    include 'footer.php';
    exit();
}
$course = mysqli_fetch_assoc($res);
?>

<div class="row">
    <div class="col-lg-12">
        <h2>Student List for <?php echo htmlspecialchars($course['c_name']); ?> (<?php echo htmlspecialchars($c_code); ?>) - Section <?php echo htmlspecialchars($section); ?></h2><br>
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
                // 6d. View updated student details
                $sql = "SELECT u.u_id, u.u_name, u.u_email, u.u_programme, r.r_status, r.r_timestamp 
                        FROM tb_registration r
                        JOIN tb_user u ON r.r_student_id = u.u_id
                        WHERE r.r_course_code = ? AND r.r_section = ? AND r.r_status != 'Cancelled'
                        ORDER BY u.u_name";
                $stmt = mysqli_prepare($con, $sql);
                mysqli_stmt_bind_param($stmt, "ss", $c_code, $section);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>".htmlspecialchars($row['u_name'])."</td>";
                        echo "<td>".htmlspecialchars($row['u_email'])."</td>";
                        echo "<td>".htmlspecialchars($row['r_status'])."</td>";
                        echo "<td>".htmlspecialchars($row['r_timestamp'])."</td>";
                        echo "<td>".htmlspecialchars($row['u_programme'])."</td>";
                        echo "<td><a href='lecturer_student_details.php?u_id={$row['u_id']}' class='btn btn-secondary btn-sm'>View Details</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No active registrations for this section.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
