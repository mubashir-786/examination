-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2022 at 06:37 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `examination`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `courseid` varchar(20) NOT NULL,
  `coursename` varchar(50) NOT NULL,
  `credithour` int(10) NOT NULL,
  `paper` enum('mids','finals') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `papers`
--

CREATE TABLE `papers` (
  `paperID` int(11) NOT NULL,
  `paperType` int(3) NOT NULL COMMENT '0 For MidTerm | 1 For FinalTerm',
  `courseInstructor` varchar(255) NOT NULL,
  `session` varchar(255) NOT NULL,
  `programSemester` varchar(255) NOT NULL,
  `courseName` varchar(255) NOT NULL,
  `courseCode` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `status` int(3) NOT NULL COMMENT '0 For New Uploaded | 1 For Approved By CHead | 2 For Approved By HOD | 3 For Rejected',
  `date_updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `papers`
--

INSERT INTO `papers` (`paperID`, `paperType`, `courseInstructor`, `session`, `programSemester`, `courseName`, `courseCode`, `date`, `status`, `date_updated`) VALUES
(1, 0, 'Haris Bhai ', 'Mid Term', '3rd Semester', 'Calculas', '255', '2022-11-10 12:00:00', 0, '2022-11-24 08:23:05'),
(2, 1, 'Haris Bhai Abc', 'Final Term', '3rd Semester', 'Mathematics 12', '155', '2022-11-11 12:00:00', 0, '2022-11-25 08:23:11'),
(3, 0, 'Hassan Aqiq', 'Final Term', '2nd Semester', 'Mathematics', '155', '2022-11-03 12:00:00', 0, '2022-11-27 11:34:44'),
(4, 0, 'Haris Bhai ', 'Mid Term', '3rd Semester', 'Calculas', '455', '2022-11-10 12:00:00', 0, '2022-11-27 11:42:56');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `questionID` int(11) NOT NULL,
  `fk_paperID` int(11) NOT NULL COMMENT 'Foreign Key PaperID',
  `question` varchar(255) NOT NULL,
  `questionMarks` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`questionID`, `fk_paperID`, `question`, `questionMarks`) VALUES
(1, 1, 'Question 1?', 10),
(2, 1, 'Question2?', 10),
(6, 2, 'Question 1? Update', 10),
(7, 2, 'Question2?', 20),
(8, 2, 'Question3?', 20),
(9, 3, 'Question 1?', 10),
(10, 3, 'Question2?', 10),
(11, 4, 'Question 1?', 20);

-- --------------------------------------------------------

--
-- Table structure for table `semester`
--

CREATE TABLE `semester` (
  `semid` int(10) NOT NULL,
  `semname` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `subquestions`
--

CREATE TABLE `subquestions` (
  `subquestionID` int(11) NOT NULL,
  `fk_questionID` int(11) NOT NULL COMMENT 'Foreign Key QuestionID',
  `fk_paperID` int(11) NOT NULL COMMENT 'Foreign Key PaperID',
  `subQuestion` varchar(255) NOT NULL,
  `subQuestionMarks` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subquestions`
--

INSERT INTO `subquestions` (`subquestionID`, `fk_questionID`, `fk_paperID`, `subQuestion`, `subQuestionMarks`) VALUES
(1, 1, 1, 'SubQuestion 1?', 5),
(2, 1, 1, 'SubQuestion2?', 5),
(3, 2, 1, 'SubQuestion2.0?', 5),
(4, 2, 1, 'SubQuestion2.1?', 5),
(11, 6, 2, 'SubQuestion 1?', 10),
(12, 7, 2, 'SubQuestion2.0?', 10),
(13, 7, 2, 'SubQuestion2.1? Update', 10),
(14, 8, 2, 'SubQuestion3.0?', 10),
(15, 8, 2, 'SubQuestion3.1?', 5),
(16, 8, 2, 'SubQuestion3.2?', 5),
(17, 9, 3, 'SubQuestion 1?', 5),
(18, 9, 3, 'SubQuestion2?', 5),
(19, 11, 4, 'SubQuestion 1?', 10),
(20, 11, 4, 'SubQuestion2?', 10);

-- --------------------------------------------------------

--
-- Table structure for table `uploadpapers`
--

CREATE TABLE `uploadpapers` (
  `fileID` int(11) NOT NULL,
  `paperID` int(3) NOT NULL,
  `type` int(3) NOT NULL COMMENT '0 For MidTerm | 1 For FinalTerm',
  `title` varchar(255) NOT NULL,
  `path` text NOT NULL,
  `numberStudents` int(11) NOT NULL DEFAULT 0,
  `last_status` int(11) NOT NULL COMMENT '0 For New Uploaded | 1 For Approved By CHead | 2 For Approved By HOD | 3 For Rejected	',
  `status` int(3) NOT NULL COMMENT '	0 For New Uploaded | 1 For Approved By CHead | 2 For Approved By HOD | 3 For Rejected',
  `date_updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `uploadpapers`
--

INSERT INTO `uploadpapers` (`fileID`, `paperID`, `type`, `title`, `path`, `numberStudents`, `last_status`, `status`, `date_updated`) VALUES
(1, 1, 0, 'Calculas', 'uploads/finalTermPaper/MidTermTest.pdf_384', 10, 1, 2, '2022-11-24 09:38:20'),
(2, 2, 1, 'Mathemathics', 'uploads/finalTermPaper/FinalTermTestUpdate.pdf_198', 15, 0, 0, '2022-11-25 08:26:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `role` enum('faculty','clusterhead','hod','examinationcell') NOT NULL,
  `name` text NOT NULL,
  `profession` text NOT NULL,
  `phone` int(20) NOT NULL,
  `email` varchar(20) NOT NULL,
  `address` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `password`, `role`, `name`, `profession`, `phone`, `email`, `address`) VALUES
('melhan', 'melhan123', 'clusterhead', 'Melhan Saeed', 'Cluster Head', 345111555, 'melhan@gmail.com', 'DHA'),
('nauman', 'nauman123', 'examinationcell', 'Nauman Bin Jawad', 'Examination Cell', 322123456, 'nauman@gmail.com', 'Malir Cantt'),
('umar', 'umar123', 'hod', 'Muhammad Umar', 'HOD', 300266613, 'umar@gmail.com', 'Gulshan'),
('yasir', 'yasir123', 'faculty', 'Muhammad Yasir Siraj', 'Faculty', 316333923, 'yasirsiraj@gmail.com', 'North Nazimabad');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`courseid`);

--
-- Indexes for table `papers`
--
ALTER TABLE `papers`
  ADD PRIMARY KEY (`paperID`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`questionID`);

--
-- Indexes for table `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`semid`);

--
-- Indexes for table `subquestions`
--
ALTER TABLE `subquestions`
  ADD PRIMARY KEY (`subquestionID`);

--
-- Indexes for table `uploadpapers`
--
ALTER TABLE `uploadpapers`
  ADD PRIMARY KEY (`fileID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `papers`
--
ALTER TABLE `papers`
  MODIFY `paperID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `questionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `subquestions`
--
ALTER TABLE `subquestions`
  MODIFY `subquestionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `uploadpapers`
--
ALTER TABLE `uploadpapers`
  MODIFY `fileID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
