-- ========================================
-- Semester Management System
-- ========================================
-- Add semester table and update course structure

-- Step 1: Create Semester Table
CREATE TABLE IF NOT EXISTS `tb_semester` (
  `sem_id` int(11) NOT NULL AUTO_INCREMENT,
  `sem_name` varchar(50) NOT NULL COMMENT 'e.g., Semester I, Semester II',
  `sem_year` varchar(10) NOT NULL COMMENT 'e.g., 2024/2025',
  `sem_faculty` varchar(10) NOT NULL,
  `sem_status` varchar(20) NOT NULL DEFAULT 'Active' COMMENT 'Active, Inactive, Archived',
  `sem_start_date` date DEFAULT NULL,
  `sem_end_date` date DEFAULT NULL,
  PRIMARY KEY (`sem_id`),
  UNIQUE KEY `unique_semester` (`sem_name`, `sem_year`, `sem_faculty`),
  KEY `sem_faculty` (`sem_faculty`),
  CONSTRAINT `fk_semester_faculty` FOREIGN KEY (`sem_faculty`) REFERENCES `tb_faculty` (`f_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Step 2: Add semester link and programme restrictions to tb_course
ALTER TABLE `tb_course` 
  ADD COLUMN `c_semester_id` int(11) DEFAULT NULL AFTER `c_semester`,
  ADD COLUMN `c_programmes` TEXT DEFAULT NULL COMMENT 'Comma-separated programme IDs' AFTER `c_lecturer_id`,
  ADD COLUMN `c_faculty` varchar(10) DEFAULT NULL AFTER `c_programmes`;

-- Add foreign keys
ALTER TABLE `tb_course`
  ADD CONSTRAINT `fk_course_semester` FOREIGN KEY (`c_semester_id`) REFERENCES `tb_semester` (`sem_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_course_faculty` FOREIGN KEY (`c_faculty`) REFERENCES `tb_faculty` (`f_id`) ON DELETE SET NULL;

-- Step 3: Add rejection reason to tb_registration
ALTER TABLE `tb_registration`
  ADD COLUMN `r_rejection_reason` TEXT DEFAULT NULL AFTER `r_status`,
  ADD COLUMN `r_rejected_by` int(10) DEFAULT NULL AFTER `r_rejection_reason`,
  ADD COLUMN `r_rejected_at` timestamp NULL DEFAULT NULL AFTER `r_rejected_by`;

-- Step 4: Insert sample semesters
INSERT INTO `tb_semester` (`sem_name`, `sem_year`, `sem_faculty`, `sem_status`) VALUES
('Semester I', '2024/2025', 'J28', 'Active'),
('Semester II', '2024/2025', 'J28', 'Active'),
('Semester I', '2025/2026', 'J28', 'Inactive'),
('Semester II', '2025/2026', 'J28', 'Inactive'),
('Semester I', '2024/2025', 'J30', 'Active'),
('Semester II', '2024/2025', 'J30', 'Active');

-- Step 5: Migrate existing courses to semesters
-- Link existing courses to first semester of their faculty (if c_semester field exists)
UPDATE `tb_course` c
INNER JOIN `tb_semester` s ON s.sem_faculty = c.c_faculty AND s.sem_name = 'Semester I' AND s.sem_year = '2024/2025'
SET c.c_semester_id = s.sem_id
WHERE c.c_semester_id IS NULL;

-- If courses don't have faculty, try to infer from lecturer's programme
UPDATE `tb_course` c
INNER JOIN `tb_user` u ON c.c_lecturer_id = u.u_id
INNER JOIN `tb_program` p ON u.u_programme = p.p_id
SET c.c_faculty = p.p_faculty
WHERE c.c_faculty IS NULL AND c.c_lecturer_id IS NOT NULL;

-- Step 6: Create view for easy querying
CREATE OR REPLACE VIEW `v_course_details` AS
SELECT 
  c.c_code,
  c.c_name,
  c.c_section,
  c.c_credit,
  c.c_max_students,
  c.c_current_students,
  c.c_programmes,
  s.sem_id,
  s.sem_name,
  s.sem_year,
  CONCAT(s.sem_year, ' ', s.sem_name) as semester_full,
  f.f_name as faculty_name,
  u.u_name as lecturer_name,
  u.u_email as lecturer_email
FROM `tb_course` c
LEFT JOIN `tb_semester` s ON c.c_semester_id = s.sem_id
LEFT JOIN `tb_faculty` f ON c.c_faculty = f.f_id
LEFT JOIN `tb_user` u ON c.c_lecturer_id = u.u_id
ORDER BY s.sem_year DESC, s.sem_name, c.c_code, c.c_section;

-- Verification queries
-- SELECT * FROM tb_semester;
-- SELECT * FROM v_course_details;
