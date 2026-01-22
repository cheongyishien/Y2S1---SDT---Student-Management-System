<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['01']);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

if ($action == 'add') {
    $c_code = $_POST['c_code'];
    $c_name = $_POST['c_name'];
    $c_credit = $_POST['c_credit'];
    $c_section = $_POST['c_section'];
    $c_max = $_POST['c_max_students'];
    $c_lect = !empty($_POST['c_lecturer_id']) ? $_POST['c_lecturer_id'] : NULL;
    $sid = $_POST['c_semester_id'];
    $progs = isset($_POST['c_programmes']) ? implode(',', $_POST['c_programmes']) : NULL;

    // Get faculty from semester
    $f_q = mysqli_query($con, "SELECT sem_faculty FROM tb_semester WHERE sem_id = '$sid'");
    $f_row = mysqli_fetch_assoc($f_q);
    $faculty = $f_row['sem_faculty'];
    
    $sql = "INSERT INTO tb_course (c_code, c_name, c_credit, c_section, c_max_students, c_lecturer_id, c_semester_id, c_programmes, c_faculty) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ssiiiiiss", $c_code, $c_name, $c_credit, $c_section, $c_max, $c_lect, $sid, $progs, $faculty);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: admin_semester_courses.php?sid=$sid");
    } else {
        echo "Error: " . mysqli_error($con);
    }
    
} elseif ($action == 'update') {
    $c_code = $_POST['c_code'];
    $c_name = $_POST['c_name'];
    $c_credit = $_POST['c_credit'];
    $c_section = $_POST['c_section'];
    $old_section = $_POST['old_section'];
    $c_max = $_POST['c_max_students'];
    $c_lect = !empty($_POST['c_lecturer_id']) ? $_POST['c_lecturer_id'] : NULL;
    $sid = $_POST['c_semester_id'];
    $progs = isset($_POST['c_programmes']) ? implode(',', $_POST['c_programmes']) : NULL;
    
    $sql = "UPDATE tb_course SET c_name=?, c_credit=?, c_section=?, c_max_students=?, c_lecturer_id=?, c_programmes=? WHERE c_code=? AND c_section=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "siiiisss", $c_name, $c_credit, $c_section, $c_max, $c_lect, $progs, $c_code, $old_section);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: admin_semester_courses.php?sid=$sid");
    } else {
        echo "Error: " . mysqli_error($con);
    }

} elseif ($action == 'delete') {
    $c_code = $_GET['cid'];
    $section = $_GET['section'];
    $sid = $_GET['sid'];
    
    $sql = "DELETE FROM tb_course WHERE c_code=? AND c_section=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $c_code, $section);
    
    if (mysqli_stmt_execute($stmt)) {
         header("Location: admin_semester_courses.php?sid=$sid");
    } else {
         echo "Error deleting: " . mysqli_error($con);
    }
}
?>
