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
    
    // 7a. Add new course
    $sql = "INSERT INTO tb_course (c_code, c_name, c_credit, c_section, c_max_students, c_lecturer_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ssiiii", $c_code, $c_name, $c_credit, $c_section, $c_max, $c_lect);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: admin_manage_courses.php");
    } else {
        echo "Error: " . mysqli_error($con);
    }
    
} elseif ($action == 'update') {
    $c_code = $_POST['c_code'];
    $c_name = $_POST['c_name'];
    $c_credit = $_POST['c_credit'];
    $c_section = $_POST['c_section'];
    $c_max = $_POST['c_max_students'];
    $c_lect = !empty($_POST['c_lecturer_id']) ? $_POST['c_lecturer_id'] : NULL;
    
    // 7b. Modify course
    $sql = "UPDATE tb_course SET c_name=?, c_credit=?, c_section=?, c_max_students=?, c_lecturer_id=? WHERE c_code=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "siiiis", $c_name, $c_credit, $c_section, $c_max, $c_lect, $c_code);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: admin_manage_courses.php");
    } else {
        echo "Error: " . mysqli_error($con);
    }

} elseif ($action == 'delete') {
    $c_code = $_GET['c_code'];
    // 7c. Delete course
    $sql = "DELETE FROM tb_course WHERE c_code=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $c_code);
    
    if (mysqli_stmt_execute($stmt)) {
         header("Location: admin_manage_courses.php");
    } else {
         echo "Error deleting (check unregistered students first): " . mysqli_error($con);
    }
}
?>
