<?php require_once '../app/views/layouts/header.php'; ?>

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
            <button class="btn btn-secondary" onclick="window.location.href='index.php?controller=admin&action=manage_courses'">Manage Courses</button>
            <button class="btn btn-secondary" onclick="window.location.href='index.php?controller=admin&action=manage_registrations'">Manage Registrations</button>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
