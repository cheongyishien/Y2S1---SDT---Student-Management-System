<?php
class AdminController extends Controller {
    
    public function __construct() {
         if (!isset($_SESSION['u_id']) || $_SESSION['u_type'] != '01') {
             header("Location: index.php");
             exit();
         }
         global $con;
         $this->db = $con;
    }

    public function dashboard() {
        $stats = [];
        $resLink = mysqli_query($this->db, "SELECT COUNT(*) as c FROM tb_user WHERE u_type='03'"); 
        $stats['students'] = mysqli_fetch_assoc($resLink)['c'];
        
        $resLink = mysqli_query($this->db, "SELECT COUNT(*) as c FROM tb_user WHERE u_type='02'"); 
        $stats['lecturers'] = mysqli_fetch_assoc($resLink)['c'];
        
        $resLink = mysqli_query($this->db, "SELECT COUNT(*) as c FROM tb_course"); 
        $stats['courses'] = mysqli_fetch_assoc($resLink)['c'];

        $this->view('admin/dashboard', ['stats' => $stats]);
    }
    
    // Placeholder for other admin actions
    public function manage_courses() {
        // Logic for manage courses
        // For now redirect or show empty view
        header("Location: ../admin_manage_courses.php"); 
        // NOTE: In a full refactor, we would move admin_manage_courses logic here.
    }
    
    public function manage_registrations() {
        header("Location: ../admin_manage_registrations.php");
    }
}
