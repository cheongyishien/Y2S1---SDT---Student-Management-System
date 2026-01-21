<?php
include 'includes/db.php';
include 'includes/auth.php';
include 'views/header.php';
?>

<div class="jumbotron text-center">
  <h1 class="display-3">Welcome to SMS</h1>
  <p class="lead">System Management System for Students, Lecturers, and Staff</p>
  <hr class="my-4">
  <p>Please login to continue or check the course list.</p>
  <p class="lead">
    <?php if(!isset($_SESSION['u_id'])): ?>
        <a class="btn btn-primary btn-lg" href="login.php" role="button">Login</a>
        <a class="btn btn-secondary btn-lg" href="register.php" role="button">Register</a>
    <?php else: ?>
        <a class="btn btn-success btn-lg" href="#" role="button">Go to Dashboard</a>
    <?php endif; ?>
  </p>
</div>

<?php
include 'views/footer.php';
?>
