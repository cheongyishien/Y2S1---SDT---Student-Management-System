<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['02']); // Lecturer only
include 'headerlecturer.php';
?>

<div class="row">
    <div class="col-lg-12">
        <h2>My Classes</h2><br>
        
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Course Name</th>
                    <th>Section</th>
                    <th>Semester</th>
                    <th>Students</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $lecturer_id = $_SESSION['u_id'];
                $sid = isset($_GET['sid']) ? $_GET['sid'] : '';
                
                $sql = "SELECT c.*, s.sem_year, s.sem_name 
                        FROM tb_course c 
                        LEFT JOIN tb_semester s ON c.c_semester_id = s.sem_id 
                        WHERE c.c_lecturer_id = ?";
                
                if (!empty($sid)) {
                    $sql .= " AND c.c_semester_id = ?";
                }
                $sql .= " ORDER BY s.sem_year DESC, s.sem_name, c.c_code, c.c_section";
                
                $stmt = mysqli_prepare($con, $sql);
                if (!empty($sid)) {
                    mysqli_stmt_bind_param($stmt, "ii", $lecturer_id, $sid);
                } else {
                    mysqli_stmt_bind_param($stmt, "i", $lecturer_id);
                }
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        $sem_info = $row['sem_year'] ? $row['sem_year'].' '.$row['sem_name'] : 'N/A';
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['c_code']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['c_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['c_section']) . "</td>";
                        echo "<td>" . htmlspecialchars($sem_info) . "</td>";
                        echo "<td>{$row['c_current_students']} / {$row['c_max_students']}</td>";
                        echo "<td>
                                <a href='lecturer_student_list.php?c_code=".urlencode($row['c_code'])."&section=".urlencode($row['c_section'])."' class='btn btn-info btn-sm'>View Students</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No courses assigned for this period.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>

