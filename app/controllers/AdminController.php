<?php
class AdminController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['u_id']) || $_SESSION['u_type'] != '01') {
            header("Location: index.php?controller=auth&action=index");
            exit();
        }
    }

    public function index() {
        $semesterModel = $this->model('Semester');
        $userModel = $this->model('User');
        $courseModel = $this->model('Course');
        
        $data = [
            'semesters' => $semesterModel->getAllSemesters(),
            'stats' => [
                'students' => 0, // Will implement count methods later
                'lecturers' => 0,
                'courses' => 0
            ]
        ];
        $this->view('admin/dashboard', $data);
    }

    public function courses() {
        $sid = $_GET['sid'] ?? '';
        if (empty($sid)) {
            header("Location: index.php?controller=admin&action=index");
            exit;
        }

        $semesterModel = $this->model('Semester');
        $courseModel = $this->model('Course');

        $data = [
            'semester' => $semesterModel->getSemesterById($sid),
            'courses' => $courseModel->getAllCoursesBySemester($sid)
        ];
        $this->view('admin/courses', $data);
    }
}
