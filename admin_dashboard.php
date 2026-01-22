<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['01']);
include 'headerstaff.php';

// Stats
$stats = [];
$res = mysqli_query($con, "SELECT COUNT(*) as c FROM tb_user WHERE u_type='03'"); $stats['students'] = mysqli_fetch_assoc($res)['c'];
$res = mysqli_query($con, "SELECT COUNT(*) as c FROM tb_user WHERE u_type='02'"); $stats['lecturers'] = mysqli_fetch_assoc($res)['c'];
$res = mysqli_query($con, "SELECT COUNT(*) as c FROM tb_course"); $stats['courses'] = mysqli_fetch_assoc($res)['c'];
?>

<div class="row">
    <div class="col-lg-12">
        <h2>Admin Dashboard</h2>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                  <div class="card-header">Total Students</div>
                  <div class="card-body">
                    <h4 class="card-title"><?php echo $stats['students']; ?></h4>
                  </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                  <div class="card-header">Total Lecturers</div>
                  <div class="card-body">
                    <h4 class="card-title"><?php echo $stats['lecturers']; ?></h4>
                  </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                  <div class="card-header">Total Courses</div>
                  <div class="card-body">
                    <h4 class="card-title"><?php echo $stats['courses']; ?></h4>
                  </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <button class="btn btn-secondary" onclick="window.location.href='admin_manage_courses.php'">Manage Courses</button>
            <button class="btn btn-secondary" onclick="window.location.href='admin_manage_registrations.php'">Manage Registrations</button>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?>
