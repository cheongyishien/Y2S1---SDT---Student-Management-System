<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['01']);
include 'headerstaff.php';

$c_code = isset($_GET['c_code']) ? $_GET['c_code'] : '';
$isEdit = !empty($c_code);
// 7a. Add new course details with number of maximum students
$course = ['c_code'=>'', 'c_name'=>'', 'c_credit'=>3, 'c_section'=>1, 'c_max_students'=>30, 'c_lecturer_id'=>''];

if ($isEdit) {
    $stmt = mysqli_prepare($con, "SELECT * FROM tb_course WHERE c_code = ?");
    mysqli_stmt_bind_param($stmt, "s", $c_code);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($res)) {
        $course = $row;
    }
}
?>

<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card border-primary mb-3">
      <div class="card-header"><?php echo $isEdit ? "Edit Course" : "Add Course"; ?></div>
      <div class="card-body">
        <form method="POST" action="admin_course_process.php">
          <input type="hidden" name="action" value="<?php echo $isEdit ? 'update' : 'add'; ?>">
          
          <div class="form-group">
            <label class="form-label mt-4">Course Code</label>
            <input type="text" name="c_code" class="form-control" value="<?php echo htmlspecialchars($course['c_code']); ?>" required <?php echo $isEdit ? 'readonly' : ''; ?>>
          </div>

          <div class="form-group">
            <label class="form-label mt-4">Course Name</label>
            <input type="text" name="c_name" class="form-control" value="<?php echo htmlspecialchars($course['c_name']); ?>" required>
          </div>
          
           <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                    <label class="form-label mt-4">Credit</label>
                    <input type="number" name="c_credit" class="form-control" value="<?php echo htmlspecialchars($course['c_credit']); ?>" required>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                    <label class="form-label mt-4">Section</label>
                    <input type="number" name="c_section" class="form-control" value="<?php echo htmlspecialchars($course['c_section']); ?>" required>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                    <label class="form-label mt-4">Max Students</label>
                    <input type="number" name="c_max_students" class="form-control" value="<?php echo htmlspecialchars($course['c_max_students']); ?>" required>
                  </div>
               </div>
           </div>

           <!-- Assign Lecturer -->
           <div class="form-group">
             <label class="form-label mt-4">Lecturer</label>
             <select class="form-select" name="c_lecturer_id">
                 <option value="">-- Select Lecturer --</option>
                 <?php
                 $l_sql = "SELECT u_id, u_name FROM tb_user WHERE u_type='02'";
                 $l_res = mysqli_query($con, $l_sql);
                 while($l_row = mysqli_fetch_assoc($l_res)) {
                     $selected = ($l_row['u_id'] == $course['c_lecturer_id']) ? "selected" : "";
                     echo "<option value='{$l_row['u_id']}' $selected>".htmlspecialchars($l_row['u_name'])."</option>";
                 }
                 ?>
             </select>
           </div>
           
           <br>
           <button type="submit" class="btn btn-primary"><?php echo $isEdit ? "Update" : "Add"; ?></button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include 'views/footer.php'; ?>
