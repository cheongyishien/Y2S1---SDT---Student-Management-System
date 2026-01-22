<?php
class LecturerController extends Controller {
    
    public function __construct() {
         if (!isset($_SESSION['u_id']) || $_SESSION['u_type'] != '02') {
             header("Location: index.php");
             exit();
         }
         global $con;
         $this->db = $con;
    }

    public function courses() {
        header("Location: ../lecturer_courses.php");
    }
}
