<?php
class Semester {
    private $db;

    public function __construct() {
        global $con;
        $this->db = $con;
    }

    public function getAllSemesters() {
        $sql = "SELECT s.*, f.f_name as faculty_name 
                FROM tb_semester s
                LEFT JOIN tb_faculty f ON s.sem_faculty = f.f_id
                ORDER BY s.sem_year DESC, s.sem_name ASC";
        return mysqli_query($this->db, $sql);
    }

    public function getSemesterById($id) {
        $sql = "SELECT s.*, f.f_name 
                 FROM tb_semester s
                 INNER JOIN tb_faculty f ON s.sem_faculty = f.f_id
                 WHERE s.sem_id = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }
}
