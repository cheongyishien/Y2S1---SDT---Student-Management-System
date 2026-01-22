-- Add faculty column to tb_user table
-- This allows tracking which faculty a user (especially students) belongs to

ALTER TABLE `tb_user` 
  ADD COLUMN `u_faculty` varchar(10) DEFAULT NULL AFTER `u_programme`;

-- Add foreign key constraint
ALTER TABLE `tb_user`
  ADD CONSTRAINT `fk_user_faculty` FOREIGN KEY (`u_faculty`) REFERENCES `tb_faculty` (`f_id`) ON DELETE SET NULL;

-- Update existing users to set their faculty based on their programme
UPDATE `tb_user`
INNER JOIN `tb_program` p ON u.u_programme = p.p_id
SET u.u_faculty = p.p_faculty
WHERE u.u_faculty IS NULL;
