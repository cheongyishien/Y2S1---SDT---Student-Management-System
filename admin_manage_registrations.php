<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['01']);
include 'headerstaff.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
?>

<div class="row">
    <div class="col-lg-12">
        <h2>Manage Registrations</h2>
        <form class="d-flex mb-4" method="GET" action="admin_manage_registrations.php">
          <input class="form-control me-sm-2" type="search" name="search" placeholder="Search by Student Name or Course Code" value="<?php echo htmlspecialchars($search); ?>">
          <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
        </form>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Registration ID</th>
                    <th>Student</th>
                    <th>Course Code</th>
                    <th>Status</th>
                    <th>Timestamp</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // 7d. Amend or cancel registration
                $sql = "SELECT r.r_id, r.r_status, r.r_timestamp, r.r_course_code, u.u_name 
                        FROM tb_registration r
                        JOIN tb_user u ON r.r_student_id = u.u_id
                        WHERE u.u_name LIKE ? OR r.r_course_code LIKE ?
                        ORDER BY r.r_timestamp DESC";
                $stmt = mysqli_prepare($con, $sql);
                $param = "%$search%";
                mysqli_stmt_bind_param($stmt, "ss", $param, $param);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['r_id']}</td>";
                    echo "<td>{$row['u_name']}</td>";
                    echo "<td>{$row['r_course_code']}</td>";
                    echo "<td>{$row['r_status']}</td>";
                    echo "<td>{$row['r_timestamp']}</td>";
                    echo "<td>
                            <a href='admin_registration_edit.php?r_id={$row['r_id']}' class='btn btn-warning btn-sm'>Edit</a>
                            <form method='POST' action='admin_registration_process.php' onsubmit='return confirm(\"Cancel this registration?\");' style='display:inline;'>
                                <input type='hidden' name='r_id' value='{$row['r_id']}'>
                                <input type='hidden' name='action' value='cancel'>
                                <button type='submit' class='btn btn-danger btn-sm'>Cancel</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'views/footer.php'; ?>
