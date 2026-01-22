<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();

// Determine header based on role
if ($_SESSION['u_type'] == '01') {
    include 'headeradmin.php';
} elseif ($_SESSION['u_type'] == '02') {
    include 'headerlecturer.php';
} else {
    include 'headerstudent.php';
}

$u_id = $_SESSION['u_id'];
$stmt = mysqli_prepare($con, "SELECT * FROM tb_user WHERE u_id = ?");
mysqli_stmt_bind_param($stmt, "i", $u_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($res);
?>

<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card border-primary mb-3">
      <div class="card-header">Edit Profile</div>
      <div class="card-body">
        
        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['err'])): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['err']); ?></div>
        <?php endif; ?>

        <form method="POST" action="profile_process.php">
          <input type="hidden" name="action" value="update_info">
          
          <div class="form-group">
            <label class="form-label mt-4">Name</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['u_name']); ?>" readonly>
            <small class="form-text text-muted">Name cannot be changed.</small>
          </div>

          <div class="form-group">
            <label class="form-label mt-4">Email address</label>
            <input type="email" name="u_email" class="form-control" value="<?php echo htmlspecialchars($user['u_email']); ?>" required>
          </div>
          
           <div class="form-group">
            <label class="form-label mt-4">Phone Number</label>
            <input type="number" name="u_phone_no" class="form-control" value="<?php echo htmlspecialchars($user['u_phone_no']); ?>" required>
          </div>

          <button type="submit" class="btn btn-primary mt-4">Update Info</button>
        </form>
        
        <hr>
        
        <h4>Change Password</h4>
        <form method="POST" action="profile_process.php">
            <input type="hidden" name="action" value="change_pwd">
            <div class="form-group">
                <label class="form-label mt-2">New Password</label>
                <input type="password" name="new_pwd" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label mt-2">Confirm New Password</label>
                <input type="password" name="confirm_pwd" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-warning mt-3">Change Password</button>
        </form>
        
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
