<!DOCTYPE html>
<html lang="en">
<head>
  <title>SMS</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    .footer {
       position: fixed;
       left: 0;
       bottom: 0;
       width: 100%;
       background-color: #2d3e50;
       color: white;
       text-align: center;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Student Management System</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarColor01">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Home</a>
        </li>
        <?php if(isset($_SESSION['u_type'])): ?>
            <?php if($_SESSION['u_type'] == '01'): // Admin ?>
                <li class="nav-item">
                   <a class="nav-link" href="index.php?controller=admin&action=dashboard">Dashboard</a>
                </li>
                <li class="nav-item">
                   <a class="nav-link" href="index.php?controller=admin&action=manage_courses">Manage Courses</a>
                </li>
                 <li class="nav-item">
                   <a class="nav-link" href="index.php?controller=admin&action=manage_registrations">Manage Registrations</a>
                </li>
            <?php elseif($_SESSION['u_type'] == '02'): // Lecturer ?>
                <li class="nav-item">
                  <a class="nav-link" href="index.php?controller=lecturer&action=courses">My Courses</a>
                </li>
            <?php elseif($_SESSION['u_type'] == '03'): // Student ?>
                <li class="nav-item">
                  <a class="nav-link" href="index.php?controller=student&action=courses">My Courses</a>
                </li>
                 <li class="nav-item">
                  <a class="nav-link" href="index.php?controller=student&action=register">Register Course</a>
                </li>
            <?php endif; ?>
        <?php endif; ?>
      </ul>
      
      <!-- User Profile Dropdown on the Right -->
      <ul class="navbar-nav ms-auto">
        <?php if(isset($_SESSION['u_id'])): ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <?php echo isset($_SESSION['u_name']) ? $_SESSION['u_name'] : 'User'; ?>
              </a>
              <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="index.php?controller=profile&action=index">Profile Settings</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="index.php?controller=auth&action=logout">Logout</a>
              </div>
            </li>
        <?php else: ?>
             <li class="nav-item">
                <a class="nav-link" href="index.php?controller=auth&action=index">Login</a>
             </li>
             <li class="nav-item">
                <a class="nav-link" href="index.php?controller=auth&action=register">Register</a>
             </li>
        <?php endif; ?>
      </ul>
      
    </div>
  </div>
</nav>
<div class="container mt-4">
