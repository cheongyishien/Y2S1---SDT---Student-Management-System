<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['01']);
include 'headerstaff.php';

$semester_id = $_GET['sid'] ?? '';
$faculty_id = $_GET['fid'] ?? '';
$is_edit = !empty($semester_id);

if ($is_edit) {
    // Get semester data
    $sql = "SELECT * FROM tb_semester WHERE sem_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $semester_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $semester = mysqli_fetch_assoc($result);
    
    if (!$semester) {
        header("Location: admin_dashboard.php");
        exit;
    }
    $faculty_id = $semester['sem_faculty'];
}

// Get faculties for dropdown
$faculties_result = mysqli_query($con, "SELECT * FROM tb_faculty WHERE f_id != '' ORDER BY f_name");
?>

<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <h2><?php echo $is_edit ? 'Edit Semester' : 'Add New Semester'; ?></h2>
        
        <div class="card">
            <div class="card-body">
                <form method="POST" action="admin_semester_process.php">
                    <?php if ($is_edit): ?>
                        <input type="hidden" name="sem_id" value="<?php echo $semester_id; ?>">
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label for="sem_faculty" class="form-label">Faculty *</label>
                        <select class="form-select" name="sem_faculty" id="sem_faculty" required <?php echo $is_edit ? 'disabled' : ''; ?>>
                            <option value="">-- Select Faculty --</option>
                            <?php while ($faculty = mysqli_fetch_assoc($faculties_result)): ?>
                                <option value="<?php echo htmlspecialchars($faculty['f_id']); ?>" 
                                        <?php echo ($faculty_id == $faculty['f_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($faculty['f_name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <?php if ($is_edit): ?>
                            <input type="hidden" name="sem_faculty" value="<?php echo htmlspecialchars($faculty_id); ?>">
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="sem_year" class="form-label">Academic Year *</label>
                        <input type="text" class="form-control" name="sem_year" id="sem_year" 
                               placeholder="e.g., 2024/2025" 
                               value="<?php echo $is_edit ? htmlspecialchars($semester['sem_year']) : ''; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="sem_name" class="form-label">Semester Name *</label>
                        <select class="form-select" name="sem_name" id="sem_name" required>
                            <option value="">-- Select Semester --</option>
                            <option value="Semester I" <?php echo ($is_edit && $semester['sem_name'] == 'Semester I') ? 'selected' : ''; ?>>Semester I</option>
                            <option value="Semester II" <?php echo ($is_edit && $semester['sem_name'] == 'Semester II') ? 'selected' : ''; ?>>Semester II</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="sem_status" class="form-label">Status *</label>
                        <select class="form-select" name="sem_status" id="sem_status" required>
                            <option value="Active" <?php echo ($is_edit && $semester['sem_status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                            <option value="Inactive" <?php echo ($is_edit && $semester['sem_status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                            <option value="Archived" <?php echo ($is_edit && $semester['sem_status'] == 'Archived') ? 'selected' : ''; ?>>Archived</option>
                        </select>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <?php echo $is_edit ? 'Update Semester' : 'Create Semester'; ?>
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="window.history.back()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
