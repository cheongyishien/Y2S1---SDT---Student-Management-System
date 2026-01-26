<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['01']);
include 'headeradmin.php';
?>

<div class="row">
    <div class="col-lg-12">
        <h2>Manage Courses</h2>
        <a href="admin_course_form.php" class="btn btn-primary mb-3">Add New Course</a>
        
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Students</th>
                    <th>Lecturer ID</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM tb_course ORDER BY c_code";
                $result = mysqli_query($con, $sql);
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['c_code']}</td>";
                    echo "<td>{$row['c_name']}</td>";
                    echo "<td>{$row['c_current_students']} / {$row['c_max_students']}</td>";
                    echo "<td>{$row['c_lecturer_id']}</td>";
                    echo "<td>
                            <!-- 7b. Modify course details -->
                            <a href='admin_course_form.php?c_code={$row['c_code']}' class='btn btn-warning btn-sm'>Edit</a>
                            <!-- 7c. Delete course -->
                            <a href='admin_course_process.php?action=delete&c_code={$row['c_code']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'views/footer.php'; ?>
