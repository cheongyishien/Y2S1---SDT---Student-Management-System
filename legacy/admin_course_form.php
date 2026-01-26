<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['01']);
include 'headeradmin.php';

$c_code = isset($_GET['cid']) ? $_GET['cid'] : '';
$section = isset($_GET['section']) ? $_GET['section'] : '';
$sid = isset($_GET['sid']) ? $_GET['sid'] : '';
$isEdit = !empty($c_code) && !empty($section);

// Course structure with new fields
$course = [
    'c_code' => '', 
    'c_name' => '', 
    'c_credit' => 3, 
    'c_section' => 1, 
    'c_max_students' => 30, 
    'c_lecturer_id' => '', 
    'c_semester_id' => $sid,
    'c_programmes' => '',
    'c_faculty' => ''
];

if ($isEdit) {
    $stmt = mysqli_prepare($con, "SELECT * FROM tb_course WHERE c_code = ? AND c_section = ? AND c_semester_id = ?");
    mysqli_stmt_bind_param($stmt, "ssi", $c_code, $section, $sid);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($res)) {
        $course = $row;
    }
}

// Get all programmes for the multi-select
$programs_sql = "SELECT p_id, p_name FROM tb_program ORDER BY p_name";
$programs_res = mysqli_query($con, $programs_sql);
$all_programs = [];
while($p_row = mysqli_fetch_assoc($programs_res)) {
    $all_programs[] = $p_row;
}

// Current checked programmes
$current_progs = !empty($course['c_programmes']) ? explode(',', $course['c_programmes']) : [];
?>

<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card border-primary mb-3">
      <div class="card-header"><?php echo $isEdit ? "Edit Course" : "Add Course"; ?></div>
      <div class="card-body">
        <form method="POST" action="admin_course_process.php">
          <input type="hidden" name="action" value="<?php echo $isEdit ? 'update' : 'add'; ?>">
          <input type="hidden" name="old_section" value="<?php echo htmlspecialchars($section); ?>">
          <input type="hidden" name="c_semester_id" value="<?php echo htmlspecialchars($course['c_semester_id']); ?>">
          
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

           <!-- Programme Restrictions -->
           <div class="form-group">
             <label class="form-label mt-4">Programme Restrictions (Optional)</label>
             <div class="row mt-2">
                <?php foreach($all_programs as $p): ?>
                   <div class="col-md-6">
                      <div class="form-check">
                         <input class="form-check-input" type="checkbox" name="c_programmes[]" value="<?php echo $p['p_id']; ?>" id="p_<?php echo $p['p_id']; ?>" <?php echo in_array($p['p_id'], $current_progs) ? 'checked' : ''; ?>>
                         <label class="form-check-label" for="p_<?php echo $p['p_id']; ?>">
                            <?php echo htmlspecialchars($p['p_name']); ?>
                         </label>
                      </div>
                   </div>
                <?php endforeach; ?>
             </div>
             <small class="text-muted">If none selected, all programmes can register.</small>
           </div>
           
           <br>
           <button type="submit" class="btn btn-primary"><?php echo $isEdit ? "Update" : "Add"; ?></button>
           <a href="admin_semester_courses.php?sid=<?php echo $course['c_semester_id']; ?>" class="btn btn-secondary">Cancel</a>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>

