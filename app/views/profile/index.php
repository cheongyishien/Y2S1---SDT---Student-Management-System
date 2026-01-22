<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card border-primary mb-3">
      <div class="card-header">Edit Profile</div>
      <div class="card-body">
        
        <?php if ($msg): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($msg); ?></div>
        <?php endif; ?>
        <?php if ($err): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($err); ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?controller=profile&action=update">
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
        <form method="POST" action="index.php?controller=profile&action=update">
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

<?php require_once '../app/views/layouts/footer.php'; ?>
