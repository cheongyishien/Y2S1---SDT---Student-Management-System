<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['01']);
include 'headeradmin.php';

$r_id = $_GET['r_id'];

// Get registration details
$sql = "SELECT r.*, u.u_name FROM tb_registration r JOIN tb_user u ON r.r_student_id = u.u_id WHERE r.r_id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $r_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$reg = mysqli_fetch_assoc($res);

if (!$reg) {
    die("Registration not found.");
}
?>

<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card border-primary mb-3">
      <div class="card-header">Amend Registration (ID: <?php echo $reg['r_id']; ?>)</div>
      <div class="card-body">
        <h5 class="card-title">Student: <?php echo htmlspecialchars($reg['u_name']); ?></h5>
        <form method="POST" action="admin_registration_process.php">
          <input type="hidden" name="action" value="update">
          <input type="hidden" name="r_id" value="<?php echo $reg['r_id']; ?>">
          <input type="hidden" name="old_course_code" value="<?php echo $reg['r_course_code']; ?>">
          
          <div class="form-group">
            <label class="form-label mt-4">Course Code</label>
            <select name="new_course_code" class="form-select">
                <?php
                $c_sql = "SELECT c_code, c_name, c_current_students, c_max_students FROM tb_course";
                $c_res = mysqli_query($con, $c_sql);
                while($c_row = mysqli_fetch_assoc($c_res)) {
                    $selected = ($c_row['c_code'] == $reg['r_course_code']) ? "selected" : "";
                    $full = ($c_row['c_current_students'] >= $c_row['c_max_students'] && !$selected) ? " (FULL)" : "";
                    echo "<option value='{$c_row['c_code']}' $selected>{$c_row['c_code']} - {$c_row['c_name']} $full</option>";
                }
                ?>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label mt-4">Status</label>
            <select name="r_status" class="form-select">
                <option value="Approved" <?php echo ($reg['r_status'] == 'Approved') ? 'selected' : ''; ?>>Approved</option>
                <option value="Registered" <?php echo ($reg['r_status'] == 'Registered') ? 'selected' : ''; ?>>Registered</option>
                <option value="Cancelled" <?php echo ($reg['r_status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
            </select>
          </div>
          
           <br>
           <button type="submit" class="btn btn-primary">Update Registration</button>
           <a href="admin_manage_registrations.php" class="btn btn-secondary">Cancel</a>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include 'views/footer.php'; ?>
