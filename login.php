<?php
include 'includes/db.php';
include 'includes/auth.php';
include 'views/header.php';
?>

<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card border-primary mb-3">
      <div class="card-header">Login</div>
      <div class="card-body">
        <form method="POST" action="loginprocess.php">
          <fieldset>
            <div class="form-group">
              <label for="email" class="form-label mt-4">Email address</label>
              <input type="email" name="femail" class="form-control" id="email" placeholder="Enter email" required>
            </div>
            <div class="form-group">
              <label for="password" class="form-label mt-4">Password</label>
              <input type="password" name="fpwd" class="form-control" id="password" placeholder="Password" required>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Login</button>
            <a href="register.php" class="btn btn-link">Register</a>
          </fieldset>
        </form>
      </div>
    </div>
  </div>
</div>

<?php
include 'views/footer.php';
?>
