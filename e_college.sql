-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 12, 2024 at 03:39 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e_college`
--
CREATE DATABASE IF NOT EXISTS `e_college` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `e_college`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `admin_id`, `password`, `email`) VALUES
(1, 2208, '$2y$10$K8rKeNUqNJpz0Ao0k2MOOucuSHXWzyaCYrOa4pXknOcj1EK8lyMi2', 'admin@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `course_id` int(10) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `duration_sem` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fees_detail`
--

CREATE TABLE `fees_detail` (
  `f_id` int(11) NOT NULL,
  `course_id` int(10) NOT NULL,
  `amount` int(255) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fees_stud`
--

CREATE TABLE `fees_stud` (
  `id` int(11) NOT NULL,
  `f_id` int(11) NOT NULL,
  `stud_id` int(10) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'open',
  `trans_status` varchar(255) NOT NULL,
  `trans_date` date NOT NULL,
  `trans_id` varchar(255) NOT NULL DEFAULT '--',
  `order_id` varchar(255) NOT NULL DEFAULT '--',
  `receipt` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `help`
--

CREATE TABLE `help` (
  `title` text NOT NULL,
  `tutorial` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lecture`
--

CREATE TABLE `lecture` (
  `l_id` int(11) NOT NULL,
  `sub_code` int(10) NOT NULL,
  `meeting_id` varchar(15) NOT NULL,
  `meeting_pass` varchar(15) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `recorded_lect_link` varchar(255) NOT NULL,
  `attendance_act` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'not completed',
  `attendance` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lecture_attendance`
--

CREATE TABLE `lecture_attendance` (
  `lect_id` int(11) NOT NULL,
  `stud_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(10) NOT NULL,
  `sub_code` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `notes_doc` varchar(255) NOT NULL,
  `audio_note` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notice`
--

CREATE TABLE `notice` (
  `n_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `doc` varchar(255) NOT NULL,
  `matter` text NOT NULL,
  `course_id` int(10) NOT NULL,
  `sem` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `f_name` varchar(255) NOT NULL,
  `m_name` varchar(255) NOT NULL,
  `l_name` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(11) NOT NULL,
  `mob_no` varchar(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `pin_code` varchar(6) NOT NULL,
  `work_exp` int(2) NOT NULL,
  `qualification` varchar(255) NOT NULL,
  `joining_year` year(4) NOT NULL,
  `leaving_year` year(4) NOT NULL DEFAULT 0000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_login`
--

CREATE TABLE `staff_login` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `logged` tinyint(1) NOT NULL DEFAULT 0,
  `activation` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `stud_id` int(11) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `f_name` varchar(255) NOT NULL,
  `m_name` varchar(255) NOT NULL,
  `l_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `stud_mob_no` varchar(10) NOT NULL,
  `parent_mob_no` varchar(10) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `pin_code` varchar(6) NOT NULL,
  `gender` varchar(11) NOT NULL,
  `dob` date NOT NULL,
  `roll_no` int(3) NOT NULL,
  `semester` int(1) NOT NULL,
  `course_id` int(11) NOT NULL,
  `admission_year` year(4) NOT NULL,
  `quota` varchar(255) NOT NULL,
  `caste` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stud_login`
--

CREATE TABLE `stud_login` (
  `id` int(11) NOT NULL,
  `stud_id` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `logged` tinyint(1) NOT NULL DEFAULT 0,
  `activation` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `sub_code` int(10) NOT NULL,
  `sub_name` varchar(255) NOT NULL,
  `sub_abbr` varchar(3) NOT NULL,
  `course_id` int(10) NOT NULL,
  `semester` int(1) NOT NULL,
  `staff_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `test_attempt`
--

CREATE TABLE `test_attempt` (
  `id` int(11) NOT NULL,
  `stud_id` int(11) NOT NULL,
  `t_id` int(11) NOT NULL,
  `total_marks` int(3) NOT NULL,
  `score` int(3) NOT NULL,
  `attempt_status` varchar(255) NOT NULL DEFAULT 'not completed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `test_detail`
--

CREATE TABLE `test_detail` (
  `t_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `sub_code` int(10) NOT NULL,
  `total_questions` int(3) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `test_status` varchar(255) NOT NULL DEFAULT 'proposed',
  `result` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `test_question`
--

CREATE TABLE `test_question` (
  `t_id` int(11) NOT NULL,
  `q_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option1` text NOT NULL,
  `option2` text NOT NULL,
  `option3` text NOT NULL,
  `option4` text NOT NULL,
  `correct_option` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `fees_detail`
--
ALTER TABLE `fees_detail`
  ADD PRIMARY KEY (`f_id`),
  ADD KEY `fees_course` (`course_id`);

--
-- Indexes for table `fees_stud`
--
ALTER TABLE `fees_stud`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fees_details` (`f_id`);

--
-- Indexes for table `lecture`
--
ALTER TABLE `lecture`
  ADD PRIMARY KEY (`l_id`),
  ADD KEY `lecture_subject` (`sub_code`);

--
-- Indexes for table `lecture_attendance`
--
ALTER TABLE `lecture_attendance`
  ADD KEY `lect_attendance` (`lect_id`),
  ADD KEY `lect_student` (`stud_id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notes_sub` (`sub_code`);

--
-- Indexes for table `notice`
--
ALTER TABLE `notice`
  ADD PRIMARY KEY (`n_id`),
  ADD KEY `notice_course` (`course_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `staff_login`
--
ALTER TABLE `staff_login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`stud_id`);

--
-- Indexes for table `stud_login`
--
ALTER TABLE `stud_login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stud_id` (`stud_id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`sub_code`),
  ADD KEY `subject_staff` (`staff_id`),
  ADD KEY `subject_course` (`course_id`);

--
-- Indexes for table `test_attempt`
--
ALTER TABLE `test_attempt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_student` (`stud_id`),
  ADD KEY `attempt_test` (`t_id`);

--
-- Indexes for table `test_detail`
--
ALTER TABLE `test_detail`
  ADD PRIMARY KEY (`t_id`),
  ADD KEY `test_sub` (`sub_code`);

--
-- Indexes for table `test_question`
--
ALTER TABLE `test_question`
  ADD PRIMARY KEY (`q_id`),
  ADD KEY `question_test` (`t_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fees_detail`
--
ALTER TABLE `fees_detail`
  MODIFY `f_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fees_stud`
--
ALTER TABLE `fees_stud`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lecture`
--
ALTER TABLE `lecture`
  MODIFY `l_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notice`
--
ALTER TABLE `notice`
  MODIFY `n_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_login`
--
ALTER TABLE `staff_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stud_login`
--
ALTER TABLE `stud_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `test_attempt`
--
ALTER TABLE `test_attempt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `test_detail`
--
ALTER TABLE `test_detail`
  MODIFY `t_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `test_question`
--
ALTER TABLE `test_question`
  MODIFY `q_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fees_detail`
--
ALTER TABLE `fees_detail`
  ADD CONSTRAINT `fees_course` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fees_stud`
--
ALTER TABLE `fees_stud`
  ADD CONSTRAINT `fees_details` FOREIGN KEY (`f_id`) REFERENCES `fees_detail` (`f_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lecture`
--
ALTER TABLE `lecture`
  ADD CONSTRAINT `lecture_subject` FOREIGN KEY (`sub_code`) REFERENCES `subject` (`sub_code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lecture_attendance`
--
ALTER TABLE `lecture_attendance`
  ADD CONSTRAINT `lect_attendance` FOREIGN KEY (`lect_id`) REFERENCES `lecture` (`l_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lect_student` FOREIGN KEY (`stud_id`) REFERENCES `student` (`stud_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_sub` FOREIGN KEY (`sub_code`) REFERENCES `subject` (`sub_code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notice`
--
ALTER TABLE `notice`
  ADD CONSTRAINT `notice_course` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `staff_login`
--
ALTER TABLE `staff_login`
  ADD CONSTRAINT `staff_id` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stud_login`
--
ALTER TABLE `stud_login`
  ADD CONSTRAINT `stud_id` FOREIGN KEY (`stud_id`) REFERENCES `student` (`stud_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `subject_course` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `subject_staff` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `test_attempt`
--
ALTER TABLE `test_attempt`
  ADD CONSTRAINT `attempt_test` FOREIGN KEY (`t_id`) REFERENCES `test_detail` (`t_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `test_student` FOREIGN KEY (`stud_id`) REFERENCES `student` (`stud_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `test_detail`
--
ALTER TABLE `test_detail`
  ADD CONSTRAINT `test_sub` FOREIGN KEY (`sub_code`) REFERENCES `subject` (`sub_code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `test_question`
--
ALTER TABLE `test_question`
  ADD CONSTRAINT `question_test` FOREIGN KEY (`t_id`) REFERENCES `test_detail` (`t_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
