<?php
class Residential {
    private $db;

    public function __construct() {
        global $con;
        $this->db = $con;
    }

    public function getAllColleges() {
        $sql = "SELECT * FROM tb_residential ORDER BY r_name ASC";
        return mysqli_query($this->db, $sql);
    }
}
