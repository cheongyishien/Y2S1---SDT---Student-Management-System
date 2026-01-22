<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['01']);
include 'headeradmin.php';

$faculty_id = isset($_GET['fid']) ? $_GET['fid'] : '';
$isEdit = !empty($faculty_id);

$faculty = ['f_id' => '', 'f_name' => ''];

if ($isEdit) {
    $stmt = mysqli_prepare($con, "SELECT * FROM tb_faculty WHERE f_id = ?");
    mysqli_stmt_bind_param($stmt, "s", $faculty_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($res)) {
        $faculty = $row;
    }
}
?>

<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card border-primary mb-3">
      <div class="card-header"><?php echo $isEdit ? "Edit Faculty" : "Add New Faculty"; ?></div>
      <div class="card-body">
        <form method="POST" action="admin_faculty_process.php">
          <input type="hidden" name="action" value="<?php echo $isEdit ? 'update' : 'add'; ?>">
          
          <div class="form-group mb-3">
            <label class="form-label">Faculty ID (e.g., J28)</label>
            <input type="text" name="f_id" class="form-control" value="<?php echo htmlspecialchars($faculty['f_id']); ?>" required <?php echo $isEdit ? 'readonly' : ''; ?>>
          </div>

          <div class="form-group mb-3">
            <label class="form-label">Faculty Name</label>
            <input type="text" name="f_name" class="form-control" value="<?php echo htmlspecialchars($faculty['f_name']); ?>" required>
          </div>
          
          <div class="mt-4">
            <button type="submit" class="btn btn-primary"><?php echo $isEdit ? "Update Faculty" : "Register Faculty"; ?></button>
            <a href="admin_dashboard.php" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
