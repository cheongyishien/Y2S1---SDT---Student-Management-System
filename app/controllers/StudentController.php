<?php
class StudentController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['u_id']) || $_SESSION['u_type'] != '03') {
            header("Location: index.php?controller=auth&action=index");
            exit();
        }
    }

    public function index() {
        $regModel = $this->model('Registration');
        $data = [
            'registrations' => $regModel->getRegistrationsByStudent($_SESSION['u_id'])
        ];
        $this->view('student/dashboard', $data);
    }

    public function courses() {
        $courseModel = $this->model('Course');
        $semesterModel = $this->model('Semester');
        
        $data = [
            'courses' => $courseModel->getAllCoursesBySemester($_SESSION['current_sid'] ?? 5), // Default to 5 if not set
            'semesters' => $semesterModel->getAllSemesters()
        ];
        $this->view('student/courses', $data);
    }

    public function register() {
        // Handle registration process
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $regModel = $this->model('Registration');
            // ... (Logic from student_register_process.php)
        }
    }
}
