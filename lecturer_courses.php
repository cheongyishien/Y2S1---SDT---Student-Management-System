<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['02']); // Lecturer only
include 'headerlecturer.php';
?>

<div class="row">
    <div class="col-lg-12">
        <h2>My Classes</h2>
        <p>Courses assigned to you.</p>
        
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Course Name</th>
                    <th>Status</th>
                    <th>Students</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $lecturer_id = $_SESSION['u_id'];
                // 6a. View assigned courses
                $sql = "SELECT * FROM tb_course WHERE c_lecturer_id = ?";
                $stmt = mysqli_prepare($con, $sql);
                mysqli_stmt_bind_param($stmt, "i", $lecturer_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>{$row['c_code']}</td>";
                        echo "<td>{$row['c_name']}</td>";
                        echo "<td>Active</td>"; // Simplified status
                        echo "<td>{$row['c_current_students']} / {$row['c_max_students']}</td>";
                        echo "<td>
                                <a href='lecturer_student_list.php?c_code={$row['c_code']}' class='btn btn-info btn-sm'>View Students</a>
                                <a href='lecturer_course_details.php?c_code={$row['c_code']}' class='btn btn-secondary btn-sm'>View Details</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No courses assigned.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'views/footer.php'; ?>
