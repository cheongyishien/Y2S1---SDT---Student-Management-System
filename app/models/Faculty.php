<?php
class Faculty {
    private $db;

    public function __construct() {
        global $con;
        $this->db = $con;
    }

    public function getAllFaculties() {
        $sql = "SELECT * FROM tb_faculty WHERE f_id != '' ORDER BY f_name ASC";
        return mysqli_query($this->db, $sql);
    }
}
