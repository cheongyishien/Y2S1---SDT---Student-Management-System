<?php
class AuthController extends Controller {
    public function index() {
        if (isset($_SESSION['u_id'])) {
             $this->redirectBasedOnRole();
        }
        $this->view('auth/login');
    }

    public function login_process() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = $this->model('User');
            $email = $_POST['femail']; 
            $password = $_POST['fpwd'];

            $user = $userModel->login($email, $password);

            if ($user) {
                $_SESSION['u_id'] = $user['u_id'];
                $_SESSION['u_name'] = $user['u_name'];
                $_SESSION['u_type'] = $user['u_type'];
                $_SESSION['u_programme'] = $user['u_programme'];

                $this->redirectBasedOnRole();
            } else {
                $data = ['error' => 'Invalid Email or Password'];
                $this->view('auth/login', $data);
            }
        }
    }

    public function register() {
        $facultyModel = $this->model('Faculty');
        $programModel = $this->model('Program');
        $residentialModel = $this->model('Residential'); // Will create this model
        
        $data = [
            'faculties' => $facultyModel->getAllFaculties(),
            'programs' => $programModel->getAllPrograms(),
            'colleges' => $this->model('Residential')->getAllColleges()
        ];
        $this->view('auth/register', $data);
    }

    public function logout() {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
    }

    private function redirectBasedOnRole() {
         $type = $_SESSION['u_type'];
         if ($type == '01') { 
             header('Location: index.php?controller=admin&action=index');
         } elseif ($type == '02') { 
             header('Location: index.php?controller=lecturer&action=index');
         } elseif ($type == '03') { 
             header('Location: index.php?controller=student&action=index');
         } else {
             header('Location: index.php');
         }
         exit();
    }
}
