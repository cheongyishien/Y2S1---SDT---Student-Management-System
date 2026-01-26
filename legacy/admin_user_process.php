<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['01']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $u_id = $_POST['u_id'] ?? '';
    $u_name = $_POST['u_name'];
    $u_email = $_POST['u_email'];
    $u_operator = $_POST['u_phone_operator'];
    $u_phone = $_POST['u_phone_no'];
    $u_gender = $_POST['u_gender'];
    $u_programme = $_POST['u_programme'];
    $u_type = $_POST['u_type'];
    $u_pwd = $_POST['u_pwd'];

    if ($action == 'add') {
        $hashed_pwd = password_hash($u_pwd, PASSWORD_DEFAULT);
        $sql = "INSERT INTO tb_user (u_pwd, u_name, u_phone_operator, u_phone_no, u_email, u_gender, u_programme, u_residential, u_type) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'R00', ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ssiissss", $hashed_pwd, $u_name, $u_operator, $u_phone, $u_email, $u_gender, $u_programme, $u_type);
        
        if (mysqli_stmt_execute($stmt)) {
            $msg = "User created successfully.";
            header("Location: admin_lecturer_list.php?msg=" . urlencode($msg));
        } else {
            echo "Error: " . mysqli_error($con);
        }
    } elseif ($action == 'update') {
        if (!empty($u_pwd)) {
            $hashed_pwd = password_hash($u_pwd, PASSWORD_DEFAULT);
            $sql = "UPDATE tb_user SET u_pwd=?, u_name=?, u_phone_operator=?, u_phone_no=?, u_email=?, u_gender=?, u_programme=? WHERE u_id=?";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "ssiisssi", $hashed_pwd, $u_name, $u_operator, $u_phone, $u_email, $u_gender, $u_programme, $u_id);
        } else {
            $sql = "UPDATE tb_user SET u_name=?, u_phone_operator=?, u_phone_no=?, u_email=?, u_gender=?, u_programme=? WHERE u_id=?";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "siisssi", $u_name, $u_operator, $u_phone, $u_email, $u_gender, $u_programme, $u_id);
        }

        if (mysqli_stmt_execute($stmt)) {
            $msg = "User updated successfully.";
            header("Location: admin_lecturer_list.php?msg=" . urlencode($msg));
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }
}
?>
