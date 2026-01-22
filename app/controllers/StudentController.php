<?php
class StudentController extends Controller {
    
    public function __construct() {
         if (!isset($_SESSION['u_id']) || $_SESSION['u_type'] != '03') {
             header("Location: index.php");
             exit();
         }
         global $con;
         $this->db = $con;
    }

    public function courses() {
        // Redirect to old file for now or implement logic
        header("Location: ../student_courses.php");
    }
    
    public function register() {
        header("Location: ../student_register.php");
    }
}
