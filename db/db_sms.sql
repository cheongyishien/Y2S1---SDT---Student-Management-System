-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2025-11-18 03:41:24
-- 服务器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `db_sms`
--

-- --------------------------------------------------------

--
-- 表的结构 `tb_faculty`
--

CREATE TABLE `tb_faculty` (
  `f_id` varchar(10) NOT NULL,
  `f_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `tb_faculty`
--

INSERT INTO `tb_faculty` (`f_id`, `f_name`) VALUES
('', 'Admin Department'),
('J28', 'Faculty of Computing'),
('J30', 'Faculty of Artificial Intelligence');

-- --------------------------------------------------------

--
-- 表的结构 `tb_program`
--

CREATE TABLE `tb_program` (
  `p_id` varchar(10) NOT NULL,
  `p_name` varchar(100) NOT NULL,
  `p_faculty` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `tb_program`
--

INSERT INTO `tb_program` (`p_id`, `p_name`, `p_faculty`) VALUES
('FAICH', 'Bachelor of Aatificial Intelligence WIth Honours', 'J30'),
('P00', 'Not Related', ''),
('SECJH', 'Bachelor of Computer Science (Software Engineering)', 'J28'),
('SECPH', 'Bachelor of Computer Science (Data Engineering)', 'J28');

-- --------------------------------------------------------

--
-- 表的结构 `tb_residential`
--

CREATE TABLE `tb_residential` (
  `r_id` varchar(10) NOT NULL,
  `r_name` varchar(50) NOT NULL,
  `r_address` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `tb_residential`
--

INSERT INTO `tb_residential` (`r_id`, `r_name`, `r_address`) VALUES
('R00', 'Outside UTM', ''),
('R01', 'Kolej Tun Dr Ismail', 'Lorong UTM'),
('R02', 'Kolej Tun Fatimah', 'Jalan UTM');

-- --------------------------------------------------------

--
-- 表的结构 `tb_user`
--

CREATE TABLE `tb_user` (
  `u_id` int(10) NOT NULL,
  `u_pwd` varchar(20) NOT NULL,
  `u_name` varchar(100) NOT NULL,
  `u_phone_operator` int(11) NOT NULL,
  `u_phone_no` int(11) NOT NULL,
  `u_email` varchar(50) NOT NULL,
  `u_gender` varchar(10) NOT NULL,
  `u_programme` varchar(10) NOT NULL,
  `u_residential` varchar(10) NOT NULL,
  `u_type` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `tb_user`
--

INSERT INTO `tb_user` (`u_id`, `u_pwd`, `u_name`, `u_phone_operator`, `u_phone_no`, `u_email`, `u_gender`, `u_programme`, `u_residential`, `u_type`) VALUES
(1, '123456', 'Haza', 19, 7719918, 'haza@gmail.com', 'M', 'SECPH', 'R01', '02'),
(2, '123456', 'Malik', 12, 1111888, 'malik@utm.my', 'Male', 'FAICH', 'R02', '02'),
(3, '123456', 'Alia', 17, 5523666, 'alia@gmail.com', 'F', 'SECJH', 'R02', '03'),
(5, '123456', 'Sako', 13, 234567, 'hako@gmail.com', 'M', 'P00', 'R00', '01'),
(14, 'dddd', 'ddd', 11, 2345678, 'dddd@gmail.com', 'F', 'SECPH', 'R02', '03'),
(16, '3333', 'cheong', 11, 77733221, 'cheong@gmail.com', 'F', 'SECPH', 'R02', '03'),
(17, '123456', 'lily', 11, 11111111, 'lily@gmail.com', 'F', 'SECJH', 'R01', '03');

-- --------------------------------------------------------

--
-- 表的结构 `tb_utype`
--

CREATE TABLE `tb_utype` (
  `ut_id` varchar(10) NOT NULL,
  `ut_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `tb_utype`
--

INSERT INTO `tb_utype` (`ut_id`, `ut_name`) VALUES
('01', 'IT Staff'),
('02', 'Lecturer'),
('03', 'Student');

--
-- 转储表的索引
--

--
-- 表的索引 `tb_faculty`
--
ALTER TABLE `tb_faculty`
  ADD PRIMARY KEY (`f_id`);

--
-- 表的索引 `tb_program`
--
ALTER TABLE `tb_program`
  ADD PRIMARY KEY (`p_id`),
  ADD KEY `p_name` (`p_name`),
  ADD KEY `p_name_2` (`p_name`),
  ADD KEY `p_faculty` (`p_faculty`),
  ADD KEY `p_name_3` (`p_name`),
  ADD KEY `p_name_4` (`p_name`);

--
-- 表的索引 `tb_residential`
--
ALTER TABLE `tb_residential`
  ADD PRIMARY KEY (`r_id`);

--
-- 表的索引 `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`u_id`),
  ADD KEY `u_programme` (`u_programme`),
  ADD KEY `u_u_residential` (`u_residential`),
  ADD KEY `u_type` (`u_type`);

--
-- 表的索引 `tb_utype`
--
ALTER TABLE `tb_utype`
  ADD PRIMARY KEY (`ut_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `u_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- 限制导出的表
--

--
-- 限制表 `tb_faculty`
--
ALTER TABLE `tb_faculty`
  ADD CONSTRAINT `tb_faculty_ibfk_1` FOREIGN KEY (`f_id`) REFERENCES `tb_program` (`p_faculty`);

--
-- 限制表 `tb_user`
--
ALTER TABLE `tb_user`
  ADD CONSTRAINT `tb_user_ibfk_1` FOREIGN KEY (`u_programme`) REFERENCES `tb_program` (`p_id`),
  ADD CONSTRAINT `tb_user_ibfk_2` FOREIGN KEY (`u_residential`) REFERENCES `tb_residential` (`r_id`),
  ADD CONSTRAINT `tb_user_ibfk_3` FOREIGN KEY (`u_type`) REFERENCES `tb_utype` (`ut_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
