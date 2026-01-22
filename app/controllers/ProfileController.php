<?php
class ProfileController extends Controller {
    
    public function __construct() {
         if (!isset($_SESSION['u_id'])) {
             header("Location: index.php?controller=auth&action=login");
             exit();
         }
    }

    public function index() {
        $userModel = $this->model('User');
        $user = $userModel->getUserById($_SESSION['u_id']);
        
        $data = [
            'user' => $user,
            'msg' => isset($_GET['msg']) ? $_GET['msg'] : null,
            'err' => isset($_GET['err']) ? $_GET['err'] : null
        ];
        
        $this->view('profile/index', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = $this->model('User');
            $id = $_SESSION['u_id'];
            $action = $_POST['action'];

            if ($action == 'update_info') {
                $email = $_POST['u_email'];
                $phone = $_POST['u_phone_no'];
                
                if ($userModel->updateProfile($id, $email, $phone)) {
                    header("Location: index.php?controller=profile&action=index&msg=Profile Updated");
                } else {
                    header("Location: index.php?controller=profile&action=index&err=Update Failed");
                }
            } elseif ($action == 'change_pwd') {
                $new_pwd = $_POST['new_pwd'];
                $confirm_pwd = $_POST['confirm_pwd'];

                if ($new_pwd !== $confirm_pwd) {
                    header("Location: index.php?controller=profile&action=index&err=Passwords do not match");
                    exit();
                }

                if ($userModel->changePassword($id, $new_pwd)) {
                    header("Location: index.php?controller=profile&action=index&msg=Password Changed Successfully");
                } else {
                    header("Location: index.php?controller=profile&action=index&err=Password Change Failed");
                }
            }
        }
    }
}
