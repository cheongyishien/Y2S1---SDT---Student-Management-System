<?php
class Course {
    private $db;

    public function __construct() {
        global $con;
        $this->db = $con;
    }

    public function getAllCoursesBySemester($semester_id) {
        $sql = "SELECT c.*, u.u_name as lecturer_name,
                (SELECT COUNT(*) FROM tb_registration r 
                 WHERE r.r_course_code = c.c_code AND r.r_section = c.c_section 
                 AND r.r_status = 'Approved') as enrolled_count
                FROM tb_course c
                LEFT JOIN tb_user u ON c.c_lecturer_id = u.u_id
                WHERE c.c_semester_id = ?
                ORDER BY c.c_code, c.c_section";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "i", $semester_id);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    public function getCourseDetails($code, $section, $semester_id) {
        $sql = "SELECT * FROM tb_course WHERE c_code = ? AND c_section = ? AND c_semester_id = ?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $code, $section, $semester_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    public function addCourse($data) {
        $sql = "INSERT INTO tb_course (c_code, c_name, c_credit, c_section, c_max_students, c_lecturer_id, c_semester_id, c_programmes, c_faculty, c_semester) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "ssisiiiiss", 
            $data['c_code'], 
            $data['c_name'], 
            $data['c_credit'], 
            $data['c_section'], 
            $data['c_max_students'], 
            $data['c_lecturer_id'], 
            $data['c_semester_id'], 
            $data['c_programmes'], 
            $data['c_faculty'], 
            $data['c_semester']
        );
        return mysqli_stmt_execute($stmt);
    }

    public function updateCourse($data, $old_section) {
        $sql = "UPDATE tb_course SET c_name=?, c_credit=?, c_section=?, c_max_students=?, c_lecturer_id=?, c_programmes=? 
                WHERE c_code=? AND c_section=? AND c_semester_id=?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "sisiisssi", 
            $data['c_name'], 
            $data['c_credit'], 
            $data['c_section'], 
            $data['c_max_students'], 
            $data['c_lecturer_id'], 
            $data['c_programmes'], 
            $data['c_code'], 
            $old_section, 
            $data['c_semester_id']
        );
        return mysqli_stmt_execute($stmt);
    }

    public function deleteCourse($code, $section, $semester_id) {
        $sql = "DELETE FROM tb_course WHERE c_code=? AND c_section=? AND c_semester_id=?";
        $stmt = mysqli_prepare($this->db, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $code, $section, $semester_id);
        return mysqli_stmt_execute($stmt);
    }

    public function getCoursesByLecturer($lecturer_id, $semester_id = null) {
        $sql = "SELECT c.*, s.sem_year, s.sem_name,
                (SELECT COUNT(*) FROM tb_registration r WHERE r.r_course_code = c.c_code AND r.r_section = c.c_section AND r.r_status = 'Approved') as enrolled_count 
                FROM tb_course c 
                LEFT JOIN tb_semester s ON c.c_semester_id = s.sem_id 
                WHERE c.c_lecturer_id = ?";
        
        if ($semester_id) {
            $sql .= " AND c.c_semester_id = ?";
        }
        $sql .= " ORDER BY s.sem_year DESC, s.sem_name, c.c_code, c.c_section";
        
        $stmt = mysqli_prepare($this->db, $sql);
        if ($semester_id) {
            mysqli_stmt_bind_param($stmt, "ii", $lecturer_id, $semester_id);
        } else {
            mysqli_stmt_bind_param($stmt, "i", $lecturer_id);
        }
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }
}
