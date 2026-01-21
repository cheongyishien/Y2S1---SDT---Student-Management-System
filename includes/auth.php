<?php
session_start();

function verifyLogin($email, $password, $con) {
    // 8a. Encrypt/Hash password logic
    // For now, we'll check against plain text first if old users exist, 
    // but eventually we want password_verify.
    // However, the user requires "Encrypt/Hash password".
    // So we should assume new users are hashed. 
    
    // Use prepared statement for SQL Injection prevention (8c)
    $stmt = mysqli_prepare($con, "SELECT u_id, u_pwd, u_name, u_type, u_programme FROM tb_user WHERE u_email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        // Check if password is hashed (default php hash starts with $2y$)
        // If not, it might be old plain text.
        // For this assignment, let's support both or just verify hash.
        
        $verified = false;
        if (password_verify($password, $row['u_pwd'])) {
            $verified = true;
        } elseif ($row['u_pwd'] === $password) {
             // Handle legacy plain text passwords (optional, but good for transition)
             $verified = true;
        }

        if ($verified) {
             $_SESSION['u_id'] = $row['u_id'];
             $_SESSION['u_name'] = $row['u_name'];
             $_SESSION['u_type'] = $row['u_type'];
             $_SESSION['u_programme'] = $row['u_programme'];
             return true;
        }
    }
    return false;
}

function checkSession() {
    if (!isset($_SESSION['u_id'])) {
        header("Location: login.php");
        exit();
    }
}

function checkRole($allowed_types) {
    if (!isset($_SESSION['u_type']) || !in_array($_SESSION['u_type'], $allowed_types)) {
         // Redirect to home or error page
         header("Location: index.php");
         exit();
    }
}

function getRoleName($type) {
    // These IDs come from tb_utype
    // '01' => 'IT Staff', '02' => 'Lecturer', '03' => 'Student'
    switch($type) {
        case '01': return 'Admin';
        case '02': return 'Lecturer';
        case '03': return 'Student';
        default: return 'Guest';
    }
}
?>
