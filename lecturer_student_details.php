<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['02']);
include 'headerlecturer.php';

$student_id = $_GET['u_id'];

// 6d. View student details
$stmt = mysqli_prepare($con, "SELECT * FROM tb_user WHERE u_id = ? AND u_type = '03'");
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);

if (!$student) {
    echo "Student not found.";
    exit;
}
?>

<div class="container mt-4">
    <h2>Student Details</h2>
    <div class="card">
        <div class="card-header">
            <?php echo htmlspecialchars($student['u_name']); ?>
        </div>
        <div class="card-body">
            <p><strong>Email:</strong> <?php echo htmlspecialchars($student['u_email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($student['u_phone_no']); ?></p>
            <p><strong>Programme:</strong> <?php echo htmlspecialchars($student['u_programme']); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($student['u_gender']); ?></p>
            <p><strong>Residential College:</strong> <?php echo htmlspecialchars($student['u_residential']); ?></p>
            
            <button class="btn btn-secondary" onclick="window.history.back()">Back</button>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
