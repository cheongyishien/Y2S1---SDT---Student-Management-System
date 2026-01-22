<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card border-primary mb-3">
      <div class="card-header">Login</div>
      <div class="card-body">
        <?php if(isset($data['error'])): ?>
            <div class="alert alert-danger"><?php echo $data['error']; ?></div>
        <?php endif; ?>
        <form method="POST" action="index.php?controller=auth&action=login_process">
          <fieldset>
            <div class="form-group">
              <label for="email" class="form-label mt-4">Email address</label>
              <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" required>
            </div>
            <div class="form-group">
              <label for="password" class="form-label mt-4">Password</label>
              <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Login</button>
            <a href="index.php?controller=auth&action=register" class="btn btn-link">Register</a>
          </fieldset>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
