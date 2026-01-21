-- Database Upgrade Script for SMS

-- 1. Create Course Table
CREATE TABLE IF NOT EXISTS `tb_course` (
  `c_code` varchar(10) NOT NULL,
  `c_name` varchar(100) NOT NULL,
  `c_credit` int(11) NOT NULL DEFAULT 3,
  `c_section` int(11) NOT NULL DEFAULT 1,
  `c_max_students` int(11) NOT NULL DEFAULT 30,
  `c_current_students` int(11) NOT NULL DEFAULT 0,
  `c_lecturer_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`c_code`),
  KEY `c_lecturer_id` (`c_lecturer_id`),
  CONSTRAINT `fk_course_lecturer` FOREIGN KEY (`c_lecturer_id`) REFERENCES `tb_user` (`u_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Create Registration Table
CREATE TABLE IF NOT EXISTS `tb_registration` (
  `r_id` int(11) NOT NULL AUTO_INCREMENT,
  `r_student_id` int(10) NOT NULL,
  `r_course_code` varchar(10) NOT NULL,
  `r_semester` varchar(10) NOT NULL,
  `r_status` varchar(20) NOT NULL DEFAULT 'Registered', -- Registered, Approved, Cancelled
  `r_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`r_id`),
  KEY `r_student_id` (`r_student_id`),
  KEY `r_course_code` (`r_course_code`),
  CONSTRAINT `fk_reg_student` FOREIGN KEY (`r_student_id`) REFERENCES `tb_user` (`u_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_reg_course` FOREIGN KEY (`r_course_code`) REFERENCES `tb_course` (`c_code`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Insert Dummy Courses (Optional for testing)
INSERT INTO `tb_course` (`c_code`, `c_name`, `c_credit`, `c_section`, `c_max_students`, `c_lecturer_id`) VALUES
('SECP3723', 'System Development Technology', 3, 1, 30, 2),
('SECP3724', 'Software Engineering Project', 3, 1, 30, 2);

-- 4. Alter User Table if needed (e.g., ensure u_pwd is long enough for hash)
ALTER TABLE `tb_user` MODIFY `u_pwd` varchar(255) NOT NULL;
