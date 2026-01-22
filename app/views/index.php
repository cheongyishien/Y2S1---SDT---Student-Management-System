<?php require_once '../app/views/layouts/header.php'; ?>

<div class="jumbotron text-center mt-5">
  <h1 class="display-3">Welcome to SMS</h1>
  <p class="lead">System Management System for Students, Lecturers, and Staff</p>
  <hr class="my-4">
  <p>Please login to continue or check the course list.</p>
  <p class="lead">
    <?php if(!isset($_SESSION['u_id'])): ?>
        <a class="btn btn-primary btn-lg" href="index.php?controller=auth&action=index" role="button">Login</a>
        <a class="btn btn-primary btn-lg" href="index.php?controller=auth&action=register" role="button">Register</a>
    <?php else: ?>
        <!-- Redirect button based on role -->
        <?php 
            $dashboardLink = '#';
            if($_SESSION['u_type'] == '01') $dashboardLink = 'index.php?controller=admin&action=dashboard';
            elseif($_SESSION['u_type'] == '02') $dashboardLink = 'index.php?controller=lecturer&action=courses';
            elseif($_SESSION['u_type'] == '03') $dashboardLink = 'index.php?controller=student&action=courses';
        ?>
        <a class="btn btn-success btn-lg" href="<?php echo $dashboardLink; ?>" role="button">Go to Dashboard</a>
    <?php endif; ?>
  </p>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
