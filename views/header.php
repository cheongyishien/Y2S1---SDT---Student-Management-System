<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Default path adjustment if deeper in directory structure
// For simplicity, we'll assume most pages are in root.
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Student Management System</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="assets/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
      body { padding-top: 70px; }
      .footer {
         position: fixed;
         left: 0;
         bottom: 0;
         width: 100%;
         background-color: #2c3e50;
         color: white;
         text-align: center;
         padding: 10px 0;
      }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">SMS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarColor01">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Home</a>
        </li>
        
        <?php if(isset($_SESSION['u_type'])): ?>
            <?php if($_SESSION['u_type'] == '03'): // Student ?>
                <li class="nav-item"><a class="nav-link" href="student_courses.php">My Courses</a></li>
                <li class="nav-item"><a class="nav-link" href="student_register.php">Register</a></li>
            <?php elseif($_SESSION['u_type'] == '02'): // Lecturer ?>
                <li class="nav-item"><a class="nav-link" href="lecturer_courses.php">My Classes</a></li>
            <?php elseif($_SESSION['u_type'] == '01'): // Admin ?>
                <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_manage_courses.php">Manage Courses</a></li>
            <?php endif; ?>
        <?php endif; ?>

      </ul>
      
      <ul class="navbar-nav ms-auto">
        <?php if(isset($_SESSION['u_id'])): ?>
             <li class="nav-item dropdown">
               <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                 Welcome, <?php echo htmlspecialchars($_SESSION['u_name']); ?>
               </a>
               <div class="dropdown-menu dropdown-menu-end">
                 <a class="dropdown-item" href="profile.php">Profile</a>
                 <div class="dropdown-divider"></div>
                 <a class="dropdown-item" href="logout.php">Logout</a>
               </div>
             </li>
        <?php else: ?>
             <li class="nav-item">
               <a class="nav-link" href="login.php">Login</a>
             </li>
             <li class="nav-item">
               <a class="nav-link" href="register.php">Register</a>
             </li>
        <?php endif; ?>
      </ul>

    </div>
  </div>
</nav>

<div class="container mt-4 mb-5">
