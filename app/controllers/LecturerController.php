<?php
class LecturerController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['u_id']) || $_SESSION['u_type'] != '02') {
            header("Location: index.php?controller=auth&action=index");
            exit();
        }
    }

    public function index() {
        $courseModel = $this->model('Course');
        $lecturer_id = $_SESSION['u_id'];
        
        $data = [
            'courses' => $courseModel->getCoursesByLecturer($lecturer_id)
        ];
        $this->view('lecturer/dashboard', $data);
    }

    public function student_list() {
        $c_code = $_GET['c_code'] ?? '';
        $section = $_GET['section'] ?? '';
        
        // Use Registration model to get students
        $regModel = $this->model('Registration');
        // We'll need a method in Registration model for this
    }
}
