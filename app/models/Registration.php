<?php
class Registration {
    private $db;

    public function __construct() {
        global $con;
        $this->db = $con;
    }

    public function getRegistrationsByStudent($student_id) {
        $sql = "SELECT r.*, c.c_name, c.c_credit 
                FROM tb_registration r
                JOIN tb_course c ON r.r_course_code = c.c_code AND r.r_section = c.c_section
                WHERE r.r_student_id = ?
                ORDER BY r.r_timestamp DESC";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "i", $student_id);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    public function addRegistration($data) {
        $sql = "INSERT INTO tb_registration (r_student_id, r_course_code, r_section, r_semester, r_status) 
                VALUES (?, ?, ?, ?, 'Registered')";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "isss", 
            $data['student_id'], 
            $data['course_code'], 
            $data['section'], 
            $data['semester']
        );
        return mysqli_stmt_execute($stmt);
    }

    public function updateStatus($id, $status, $reason = null, $admin_id = null) {
        $sql = "UPDATE tb_registration SET r_status = ?, r_rejection_reason = ?, r_rejected_by = ?, r_rejected_at = NOW() 
                WHERE r_id = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "ssii", $status, $reason, $admin_id, $id);
        return mysqli_stmt_execute($stmt);
    }

    public function deleteRegistration($id, $student_id) {
        $sql = "DELETE FROM tb_registration WHERE r_id = ? AND r_student_id = ? AND r_status = 'Registered'";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $id, $student_id);
        return mysqli_stmt_execute($stmt);
    }
}
