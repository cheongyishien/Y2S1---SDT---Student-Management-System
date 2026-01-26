<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['01']);
include 'headeradmin.php';

// Get all lecturers
$sql = "SELECT u.*, p.p_name as programme_name 
        FROM tb_user u 
        LEFT JOIN tb_program p ON u.u_programme = p.p_id 
        WHERE u.u_type = '02' 
        ORDER BY u.u_name";
$result = mysqli_query($con, $sql);
?>

<div class="row">
    <div class="col-lg-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Manage Lecturers</h2>
            <a href="admin_user_form.php?type=02" class="btn btn-success">
                <i class="bi bi-person-plus"></i> Add New Lecturer
            </a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Programme</th>
                        <th>Gender</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): 
                        $operator = str_pad($row['u_phone_operator'], 3, "0", STR_PAD_LEFT);
                        $phone_display = "0" . $operator . "-" . $row['u_phone_no'];
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['u_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['u_email']); ?></td>
                        <td><?php echo htmlspecialchars($phone_display); ?></td>
                        <td><?php echo htmlspecialchars($row['programme_name'] ?? 'N/A'); ?></td>
                        <td><?php echo $row['u_gender'] == 'M' ? 'Male' : 'Female'; ?></td>
                        <td>
                            <a href="admin_user_form.php?uid=<?php echo $row['u_id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
