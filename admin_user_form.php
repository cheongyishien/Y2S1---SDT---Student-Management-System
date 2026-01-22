<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['01']);
include 'headeradmin.php';

$uid = $_GET['uid'] ?? '';
$type = $_GET['type'] ?? '02'; // Default to Lecturer
$isEdit = !empty($uid);

$user = [
    'u_name' => '',
    'u_email' => '',
    'u_phone_operator' => '',
    'u_phone_no' => '',
    'u_gender' => 'M',
    'u_programme' => 'P00', // Not Related for Admin/Staff by default
    'u_residential' => 'R00',
    'u_type' => $type
];

if ($isEdit) {
    $stmt = mysqli_prepare($con, "SELECT * FROM tb_user WHERE u_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $uid);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($res)) {
        $user = $row;
    }
}
?>

<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card border-primary mb-3">
      <div class="card-header"><?php echo $isEdit ? "Edit User" : "Add New User"; ?></div>
      <div class="card-body">
        <form method="POST" action="admin_user_process.php">
          <input type="hidden" name="action" value="<?php echo $isEdit ? 'update' : 'add'; ?>">
          <input type="hidden" name="u_id" value="<?php echo htmlspecialchars($uid); ?>">
          
          <div class="form-group mb-3">
            <label class="form-label">User Type</label>
            <select name="u_type" class="form-select" required <?php echo $isEdit ? 'readonly disabled' : ''; ?>>
                <option value="01" <?php echo $user['u_type'] == '01' ? 'selected' : ''; ?>>Admin</option>
                <option value="02" <?php echo $user['u_type'] == '02' ? 'selected' : ''; ?>>Lecturer</option>
            </select>
            <?php if($isEdit): ?>
                <input type="hidden" name="u_type" value="<?php echo $user['u_type']; ?>">
            <?php endif; ?>
          </div>

          <div class="form-group mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="u_name" class="form-control" value="<?php echo htmlspecialchars($user['u_name']); ?>" required>
          </div>

          <div class="form-group mb-3">
            <label class="form-label">Email address</label>
            <input type="email" name="u_email" class="form-control" value="<?php echo htmlspecialchars($user['u_email']); ?>" required>
          </div>

          <div class="row mb-3">
              <div class="col-md-4">
                  <label class="form-label">Phone Operator</label>
                  <input type="number" name="u_phone_operator" class="form-control" value="<?php echo htmlspecialchars($user['u_phone_operator']); ?>" required>
              </div>
              <div class="col-md-8">
                  <label class="form-label">Phone Number</label>
                  <input type="number" name="u_phone_no" class="form-control" value="<?php echo htmlspecialchars($user['u_phone_no']); ?>" required>
              </div>
          </div>

          <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Gender</label>
                <select name="u_gender" class="form-select" required>
                    <option value="M" <?php echo $user['u_gender'] == 'M' ? 'selected' : ''; ?>>Male</option>
                    <option value="F" <?php echo $user['u_gender'] == 'F' ? 'selected' : ''; ?>>Female</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Programme (Optional)</label>
                <select name="u_programme" class="form-select">
                    <option value="P00">Not Related</option>
                    <?php
                    $p_sql = "SELECT p_id, p_name FROM tb_program ORDER BY p_name";
                    $p_res = mysqli_query($con, $p_sql);
                    while($p_row = mysqli_fetch_assoc($p_res)) {
                        $selected = ($p_row['p_id'] == $user['u_programme']) ? "selected" : "";
                        echo "<option value='{$p_row['p_id']}' $selected>".htmlspecialchars($p_row['p_name'])."</option>";
                    }
                    ?>
                </select>
              </div>
          </div>

          <div class="form-group mb-4">
            <label class="form-label"><?php echo $isEdit ? "New Password (Leave blank to keep current)" : "Password"; ?></label>
            <input type="password" name="u_pwd" class="form-control" <?php echo $isEdit ? '' : 'required'; ?>>
          </div>

          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary"><?php echo $isEdit ? "Update User" : "Create User"; ?></button>
            <a href="admin_lecturer_list.php" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
