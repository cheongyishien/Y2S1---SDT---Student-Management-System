<?php
class User {
    private $db;

    public function __construct() {
        global $con;
        $this->db = $con;
    }

    public function login($email, $password) {
        $stmt = mysqli_prepare($this->db, "SELECT u_id, u_pwd, u_name, u_type, u_programme FROM tb_user WHERE u_email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            $verified = false;
            if (password_verify($password, $row['u_pwd'])) {
                $verified = true;
            } elseif ($row['u_pwd'] === $password) {
                $verified = true;
            }

            if ($verified) {
                return $row;
            }
        }
        return false;
    }

    public function getUserById($id) {
        $stmt = mysqli_prepare($this->db, "SELECT * FROM tb_user WHERE u_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    public function updateProfile($id, $email, $phone) {
        $stmt = mysqli_prepare($this->db, "UPDATE tb_user SET u_email = ?, u_phone_no = ? WHERE u_id = ?");
        mysqli_stmt_bind_param($stmt, "sii", $email, $phone, $id);
        return mysqli_stmt_execute($stmt);
    }

    public function changePassword($id, $new_pwd) {
        $hashed = password_hash($new_pwd, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($this->db, "UPDATE tb_user SET u_pwd = ? WHERE u_id = ?");
        mysqli_stmt_bind_param($stmt, "si", $hashed, $id);
        return mysqli_stmt_execute($stmt);
    }

    public function getRoleName($type) {
         switch($type) {
            case '01': return 'Admin';
            case '02': return 'Lecturer';
            case '03': return 'Student';
            default: return 'Guest';
        }
    }
}
