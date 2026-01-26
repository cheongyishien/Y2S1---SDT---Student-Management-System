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
        
        $regModel = $this->model('Registration');
        $courseModel = $this->model('Course');

        $data = [
            'course' => $courseModel->getCourseDetails($c_code, $section, $_SESSION['sid'] ?? ''), // Sid handling might need refinement
            'students' => $regModel->getStudentsByCourse($c_code, $section)
        ];
        $this->view('lecturer/student_list', $data);
    }

    public function course_details() {
        $c_code = $_GET['cid'] ?? '';
        $section = $_GET['section'] ?? '';
        $courseModel = $this->model('Course');
        
        $data = [
            'course' => $courseModel->getCourseDetails($c_code, $section, $_SESSION['sid'] ?? '')
        ];
        $this->view('lecturer/course_details', $data);
    }
}
