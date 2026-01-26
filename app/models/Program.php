<?php
class Program {
    private $db;

    public function __construct() {
        global $con;
        $this->db = $con;
    }

    public function getAllPrograms() {
        $sql = "SELECT * FROM tb_program ORDER BY p_name ASC";
        return mysqli_query($this->db, $sql);
    }

    public function getProgramsByFaculty($faculty_id) {
        $sql = "SELECT * FROM tb_program WHERE p_faculty = ? ORDER BY p_name ASC";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "s", $faculty_id);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }
}
