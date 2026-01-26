<?php
class StudentController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['u_id']) || $_SESSION['u_type'] != '03') {
            header("Location: index.php?controller=auth&action=index");
            exit();
        }
    }

    public function index() {
        $this->view('student/dashboard');
    }

    public function courses() {
        // Handle browsing and registering for courses
    }
}
