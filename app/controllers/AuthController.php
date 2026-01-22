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
            // Using names from old form or new form? I will use new names 'email' and 'password' in the view.
            $email = $_POST['email']; 
            $password = $_POST['password'];

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
        // Redirect to the registration form
        header("Location: ../register.php");
        exit();
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
             header('Location: index.php?controller=admin&action=dashboard');
         } elseif ($type == '02') { 
             header('Location: index.php?controller=lecturer&action=courses');
         } elseif ($type == '03') { 
             header('Location: index.php?controller=student&action=courses');
         } else {
             header('Location: index.php');
         }
         exit();
    }
}
