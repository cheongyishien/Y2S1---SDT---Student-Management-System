<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['03']);
include 'headerstudent.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
?>

<div class="row">
    <div class="col-lg-12">
        <h2>Register Courses</h2>
        <!-- 5c. Search course -->
        <form class="d-flex mb-4" method="GET" action="student_register.php">
          <input class="form-control me-sm-2" type="search" name="search" placeholder="Search Course Code or Name" value="<?php echo htmlspecialchars($search); ?>">
          <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
        </form>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Course Name</th>
                    <th>Credit</th>
                    <th>Section</th>
                    <th>Capacity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Use Prepared Statements for search
                $sql = "SELECT * FROM tb_course WHERE (c_code LIKE ? OR c_name LIKE ?) ORDER BY c_code";
                $stmt = mysqli_prepare($con, $sql);
                $param = "%$search%";
                mysqli_stmt_bind_param($stmt, "ss", $param, $param);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        $isFull = $row['c_current_students'] >= $row['c_max_students'];
                        
                        // Check if already registered
                        $checkSql = "SELECT r_id FROM tb_registration WHERE r_student_id = ? AND r_course_code = ?";
                        $checkStmt = mysqli_prepare($con, $checkSql);
                        mysqli_stmt_bind_param($checkStmt, "is", $_SESSION['u_id'], $row['c_code']);
                        mysqli_stmt_execute($checkStmt);
                        $checkResult = mysqli_stmt_get_result($checkStmt);
                        $isRegistered = mysqli_num_rows($checkResult) > 0;
                        
                        echo "<tr>";
                        echo "<td>{$row['c_code']}</td>";
                        echo "<td>{$row['c_name']}</td>";
                        echo "<td>{$row['c_credit']}</td>";
                        echo "<td>{$row['c_section']}</td>";
                        echo "<td>{$row['c_current_students']} / {$row['c_max_students']}</td>";
                        echo "<td>";
                        
                        if ($isRegistered) {
                            echo "<button class='btn btn-success btn-sm' disabled>Registered</button>";
                        } elseif ($isFull) {
                             echo "<button class='btn btn-danger btn-sm' disabled>Full</button>";
                        } else {
                             // 5a. Register courses
                             echo "<form method='POST' action='student_register_process.php' style='display:inline;'>
                                     <input type='hidden' name='course_code' value='{$row['c_code']}'>
                                     <button type='submit' class='btn btn-primary btn-sm'>Register</button>
                                   </form>";
                        }
                        
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No courses found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'views/footer.php'; ?>
