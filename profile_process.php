<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();

$u_id = $_SESSION['u_id'];
$action = $_POST['action'];

if ($action == 'update_info') {
    $email = $_POST['u_email'];
    $phone = $_POST['u_phone_no'];

    $stmt = mysqli_prepare($con, "UPDATE tb_user SET u_email = ?, u_phone_no = ? WHERE u_id = ?");
    mysqli_stmt_bind_param($stmt, "sii", $email, $phone, $u_id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: profile.php?msg=Profile Updated");
    } else {
        header("Location: profile.php?err=Update Failed");
    }

} elseif ($action == 'change_pwd') {
    $new_pwd = $_POST['new_pwd'];
    $confirm_pwd = $_POST['confirm_pwd'];

    if ($new_pwd !== $confirm_pwd) {
         header("Location: profile.php?err=Passwords do not match");
         exit();
    }
    
    // Hash new password
    $hashed = password_hash($new_pwd, PASSWORD_DEFAULT);
    
    $stmt = mysqli_prepare($con, "UPDATE tb_user SET u_pwd = ? WHERE u_id = ?");
    mysqli_stmt_bind_param($stmt, "si", $hashed, $u_id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: profile.php?msg=Password Changed Successfully");
    } else {
        header("Location: profile.php?err=Password Change Failed");
    }
} else {
    header("Location: profile.php");
}
?>
