-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Nov 17, 2024 at 03:29 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hostel_management`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `ListStudentsWithHostels` ()   BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE student_name VARCHAR(50);
    DECLARE hostel_name VARCHAR(50);

    DECLARE cur CURSOR FOR
        SELECT s.s_name, h.hostel_name 
        FROM student s
        JOIN hostel h ON s.hostel_id = h.hostel_id;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO student_name, hostel_name;
        IF done THEN
            LEAVE read_loop;
        END IF;
        
        -- Display each student and their hostel name
        SELECT student_name AS "Student Name", hostel_name AS "Hostel Name";
    END LOOP;

    CLOSE cur;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListVisitors` (`student_id` INT)   BEGIN
    SELECT visitor_name, visit_date, check_in_time, check_out_time 
    FROM visitors 
    WHERE s_id = student_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `StudentsAndHostels` ()   BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE student_name VARCHAR(50);
    DECLARE hostel_name VARCHAR(50);

    DECLARE cur CURSOR FOR
        SELECT s_name, hostel_name 
        FROM student_personal
        JOIN hostel ON student_personal.hostel_id = hostel.hostel_id;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO student_name, hostel_name;
        IF done THEN
            LEAVE read_loop;
        END IF;
        
        -- Display the student and hostel names
        SELECT student_name AS 'Student Name', hostel_name AS 'Hostel Name';
    END LOOP;

    CLOSE cur;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdatePaymentStatus` ()   BEGIN
    UPDATE admission 
    SET payment_status = 'Completed' 
    WHERE payment_date IS NOT NULL;
    Select s_id, payment_status from admission;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `TotalNightoutRequests` (`student_id` INT) RETURNS INT(11)  BEGIN
    DECLARE count_requests INT;
    SELECT COUNT(request_id) INTO count_requests 
    FROM nightout 
    WHERE s_id = student_id;
    RETURN count_requests;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admission`
--

CREATE TABLE `admission` (
  `s_id` int(11) NOT NULL,
  `DOJ` date NOT NULL COMMENT 'date of joining',
  `fees` decimal(10,0) NOT NULL COMMENT 'fees',
  `payment_mode` varchar(50) NOT NULL COMMENT 'mode of payment',
  `payment_status` varchar(50) NOT NULL DEFAULT 'Pending' COMMENT 'status of payment',
  `payment_date` date DEFAULT NULL COMMENT 'date of payment'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admission`
--

INSERT INTO `admission` (`s_id`, `DOJ`, `fees`, `payment_mode`, `payment_status`, `payment_date`) VALUES
(1, '2023-11-01', 150000, 'Cash', 'Completed', '2023-11-05'),
(4, '2021-07-15', 600000, 'net banking', 'Completed', '2021-07-03'),
(12, '2024-02-15', 500000, 'cash', 'Completed', '2024-02-03'),
(14, '2023-07-15', 170000, 'net banking', 'Completed', '2023-07-06'),
(17, '2023-12-16', 2000000, 'net banking', 'Completed', '2023-12-08'),
(22, '2024-11-01', 400000, 'cash', 'Completed', '2024-10-24'),
(24, '2024-05-05', 290000, 'net banking', 'Completed', '2024-05-24'),
(25, '2024-11-06', 177777, 'cash', 'Completed', '2024-11-01');

--
-- Triggers `admission`
--
DELIMITER $$
CREATE TRIGGER `UpdatePaymentStatus` BEFORE UPDATE ON `admission` FOR EACH ROW IF NEW.payment_date IS NOT NULL THEN
    SET NEW.payment_status = 'Completed';
END IF
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `guardian`
--

CREATE TABLE `guardian` (
  `s_id` int(11) NOT NULL,
  `mother_name` varchar(50) NOT NULL,
  `father_name` varchar(50) NOT NULL,
  `guardian_name` varchar(50) NOT NULL,
  `mother_mob` bigint(10) NOT NULL,
  `father_mob` bigint(10) NOT NULL,
  `guardian_mob` bigint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guardian`
--

INSERT INTO `guardian` (`s_id`, `mother_name`, `father_name`, `guardian_name`, `mother_mob`, `father_mob`, `guardian_mob`) VALUES
(70, 'Radha', 'Shyam', 'Rohan', 4759632228, 66758795, 66758795),
(71, 'Radha', 'Shyam', 'Rohan', 4759632228, 66758795, 66758795);

-- --------------------------------------------------------

--
-- Stand-in structure for view `guardiancontactinfo`
-- (See below for the actual view)
--
CREATE TABLE `guardiancontactinfo` (
);

-- --------------------------------------------------------

--
-- Table structure for table `hostel`
--

CREATE TABLE `hostel` (
  `hostel_id` int(11) NOT NULL,
  `hostel_name` varchar(100) NOT NULL,
  `no_of_floors` int(11) NOT NULL,
  `no_of_rooms` int(11) NOT NULL,
  `capacity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hostel`
--

INSERT INTO `hostel` (`hostel_id`, `hostel_name`, `no_of_floors`, `no_of_rooms`, `capacity`) VALUES
(1, 'Anandi', 4, 20, 240),
(2, 'Shanti', 5, 30, 450),
(3, 'Maharshi Karve', 4, 30, 400),
(4, 'Purnima', 2, 40, 380),
(5, 'David', 5, 20, 100),
(6, 'Sasun David', 3, 50, 175),
(7, 'Rama Sadan', 6, 20, 360),
(10, 'Chitale', 4, 20, 160);

-- --------------------------------------------------------

--
-- Table structure for table `nightout`
--

CREATE TABLE `nightout` (
  `request_id` int(11) NOT NULL,
  `s_id` int(11) NOT NULL COMMENT 'student''s id',
  `reason` varchar(255) NOT NULL COMMENT 'reason for the nightout',
  `place` varchar(255) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `status` varchar(20) NOT NULL COMMENT 'Pending/Approved/Rejected',
  `request_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nightout`
--

INSERT INTO `nightout` (`request_id`, `s_id`, `reason`, `place`, `from_date`, `to_date`, `status`, `request_date`) VALUES
(1, 1, 'Holiday Grounds', 'Nanded', '2024-11-07', '2024-11-14', 'Approved', '2024-11-15'),
(2, 1, 'Holiday Grounds', 'Nanded', '2024-11-07', '2024-11-14', 'Approved', '2024-11-15'),
(3, 23, 'Sick leave', 'Solapur', '2024-02-15', '2024-02-17', 'Rejected', '2024-11-15'),
(4, 23, 'Sick leave', 'Solapur', '2024-11-27', '2024-11-29', 'Rejected', '2024-11-15'),
(5, 14, 'Holiday Grounds', 'Ahmednagar', '2024-11-06', '2024-11-18', 'Approved', '2024-11-15'),
(6, 25, 'Sick leave', 'Nanded', '2024-11-19', '2024-11-22', 'Pending', '2024-11-15'),
(7, 12, 'Holiday Grounds', 'Solapur', '2024-11-27', '2024-11-30', 'Pending', '2024-11-15'),
(8, 12, 'Holiday Grounds', 'Solapur', '2024-11-27', '2024-11-30', 'Pending', '2024-11-15'),
(9, 1, 'Diwali holiday', 'Solapur', '2024-11-01', '2024-11-09', 'Pending', '2024-11-15'),
(10, 1, 'Diwali holiday', 'Solapur', '2024-11-01', '2024-11-09', 'Pending', '2024-11-15');

-- --------------------------------------------------------

--
-- Table structure for table `rules`
--

CREATE TABLE `rules` (
  `rule_no` int(11) NOT NULL,
  `rule` varchar(50) NOT NULL,
  `rule_desc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rules`
--

INSERT INTO `rules` (`rule_no`, `rule`, `rule_desc`) VALUES
(1, 'Check-in and Check-out Timings', '-> Entry and exit timings are strict and usually set around 6:00 AM to 8:00 PM for security reasons.\r\n-> Any special permissions for late-night entries or exits should be sought in advance.'),
(2, 'Room and Property Maintenance', '-> Residents are expected to maintain cleanliness and upkeep of their rooms.\r\n-> Damages to hostel property (furniture, fixtures, etc.) will be borne by the resident responsible.'),
(3, 'Personal Belongings', 'Hostel management is not responsible for personal valuables, so residents are advised to secure their belongings.'),
(4, 'Visitors and Guests', '-> Visitors are generally restricted to common areas and must leave by evening (typically by 7:00 PM).\r\n-> Overnight guests are generally not allowed.'),
(5, 'Quiet Hours', '-> Designated quiet hours to respect the study and resting environment, usually between 10:00 PM and 6:00 AM.\r\n-> Loud music, parties, or gatherings are generally prohibited'),
(6, 'Use of Appliances', '-> Personal electrical appliances (such as heaters or cooking devices) may be restricted for safety.\r\n-> Unauthorized appliances could lead to fines or confiscation.'),
(7, 'Disciplinary Actions', '-> Violations of rules can result in warnings, fines, or even expulsion based on severity.\r\n-> Students are required to follow additional instructions or orders given by hostel management.');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `s_id` int(11) NOT NULL,
  `s_name` varchar(50) NOT NULL COMMENT 'student name',
  `mobile_number` bigint(20) NOT NULL COMMENT 'student''s mobile number',
  `gender` varchar(10) NOT NULL,
  `DOB` date NOT NULL COMMENT 'date of birth',
  `year` int(11) NOT NULL,
  `branch` varchar(10) NOT NULL,
  `address` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `blood_group` varchar(10) NOT NULL,
  `room_id` int(11) NOT NULL COMMENT 'room number',
  `hostel_id` int(11) NOT NULL COMMENT 'hostel number'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`s_id`, `s_name`, `mobile_number`, `gender`, `DOB`, `year`, `branch`, `address`, `email`, `blood_group`, `room_id`, `hostel_id`) VALUES
(1, 'Nutan', 9098765670, 'F', '2000-11-14', 2024, 'Comp', 'Mumbai', 'nutan@gmail.com', 'A+', 305, 4),
(2, 'Nutan', 9098765670, 'F', '2000-11-14', 2024, 'Comp', 'Mumbai', 'nutan@gmail.com', 'A+', 305, 4),
(3, 'Nutan', 9098765670, 'F', '2000-11-14', 2024, 'Comp', 'Mumbai', 'nutan@gmail.com', 'A+', 305, 4),
(4, 'Dhanshri', 6589774321, 'F', '2004-12-09', 0, 'Comp', 'Ahmednagar', 'dhanshri.patare@cumminscollege.in', 'A+', 411, 3),
(12, 'Dhanshri', 4759632228, 'F', '2024-11-08', 2027, 'Comp', 'Ahmednagar', 'dhanshri.patare@cumminscollege.in', 'A+', 411, 3),
(13, 'Dhanshri', 4759632228, 'F', '2024-11-04', 2027, 'Comp', 'Ahmednagar', 'dhanshri.patare@cumminscollege.in', 'A+', 411, 3),
(14, 'Nish', 0, 'F', '2007-01-22', 2027, 'Comp', 'Ahmednagar', 'nish@cumminscollege.in', 'A+', 411, 4),
(17, 'Siddhani', 8329295916, 'F', '2005-04-05', 2027, 'Comp', 'Solapur', 'siddhanimagar@gmail.com', 'A+', 208, 3),
(22, 'John', 7789223018, 'M', '2006-01-15', 2023, 'Entc', 'Pune', 'john@gmail.com', 'O+', 133, 2),
(23, 'Esha', 8888876654, 'F', '2004-07-06', 2027, 'It', 'Oty', 'esha@cumminscollege.in', 'A+', 128, 4),
(24, 'Esha', 8888876654, 'F', '2004-07-06', 2027, 'It', 'Oty', 'esha@cumminscollege.in', 'A+', 128, 4),
(25, 'aman', 6666689999, 'M', '2017-07-13', 2029, 'instru', 'mumbai', 'aman@gmail.com', 'B+', 301, 5),
(26, 'Jia', 6666666666, 'F', '2002-08-03', 2020, 'Entc', 'Pune', 'jia@gmail.com', 'O+', 699, 3),
(27, 'a', 2, 'f', '2024-11-02', 2026, 'it', 'Pune', 'a@gmail.com', 'O+', 511, 4),
(28, 'a', 2, 'f', '2024-11-02', 2026, 'it', 'Pune', 'a@gmail.com', 'O+', 511, 4),
(29, 'Nish', 0, 'F', '2007-01-22', 2027, 'Comp', 'Ahmednagar', 'nish@cumminscollege.in', 'A+', 411, 4),
(30, 'Nish', 0, 'F', '2007-01-22', 2027, 'Comp', 'Ahmednagar', 'nish@cumminscollege.in', 'A+', 411, 4),
(31, 'Sam', 6699999021, 'M', '2001-02-22', 2028, 'instru', 'Mumbai', 'sam@gmail.com', 'O-', 501, 2),
(32, 'Sam', 6699999021, 'M', '2001-02-22', 2028, 'instru', 'Mumbai', 'sam@gmail.com', 'O-', 501, 2),
(33, 'Sam', 6699999021, 'M', '2001-02-22', 2028, 'instru', 'Mumbai', 'sam@gmail.com', 'O-', 501, 2),
(34, 'Sam', 6699999021, 'M', '2001-02-22', 2028, 'instru', 'Mumbai', 'sam@gmail.com', 'O-', 501, 2),
(35, 'a', 2, 'f', '2024-11-02', 2026, 'it', 'Pune', 'a@gmail.com', 'O+', 511, 4),
(36, 'a', 2, 'f', '2024-11-02', 2026, 'it', 'Pune', 'a@gmail.com', 'O+', 511, 4),
(37, 'q', 2, 'f', '2024-11-02', 2020, 'it', 'Pune', 'a@gmail.com', 'O+', 511, 4),
(38, 'Sam', 6699999021, 'M', '2001-02-22', 2028, 'instru', 'Mumbai', 'sam@gmail.com', 'O-', 501, 2),
(39, 'Sam', 6699999021, 'M', '2001-02-22', 2028, 'instru', 'Mumbai', 'sam@gmail.com', 'O-', 501, 2),
(40, 'q', 2, 'f', '2024-11-02', 2020, 'it', 'Pune', 'a@gmail.com', 'O+', 511, 4),
(41, 'q', 2, 'f', '2024-11-02', 2020, 'it', 'Pune', 'a@gmail.com', 'O+', 511, 4),
(42, 'z', 987654321, 'M', '2024-11-06', 2020, 'it', 'Pune', 'a@gmail.com', 'O+', 504, 5),
(43, 'z', 987654321, 'M', '2024-11-06', 2020, 'it', 'Pune', 'a@gmail.com', 'O+', 504, 5),
(44, 'iiii', 8888, 'm', '2024-01-05', 2023, 'entc', 'karad', 'iiii@gmail.comm', 'O+', 199, 2),
(45, 'iiii', 8888, 'm', '2024-01-05', 2023, 'entc', 'karad', 'iiii@gmail.comm', 'O+', 199, 2),
(46, 'lopi', 2, 'f', '2007-06-06', 2020, 'it', 'Pune', 'a@gmail.com', 'B-', 101, 2),
(47, 'mopi', 2, 'f', '2024-11-05', 2020, 'it', 'Pune', 'a@gmail.com', 'B-', 101, 2),
(48, 'mopi', 2, 'f', '2024-11-13', 2020, 'it', 'Pune', 'a@gmail.com', 'B-', 101, 2),
(50, 'mopi', 2, 'f', '2024-11-13', 2020, 'it', 'Pune', 'a@gmail.com', 'B-', 101, 2),
(51, 'mopi', 2, 'f', '2024-11-13', 2020, 'it', 'Pune', 'a@gmail.com', 'B-', 101, 2),
(52, 'soni', 2, 'f', '1999-02-08', 2020, 'it', 'Pune', 'a@gmail.com', 'B-', 101, 2),
(60, 'Mahi', 4596, 'F', '2014-11-12', 2030, 'IT', 'Nashik', 'mahi@gmmail.com', 'A+', 200, 2),
(61, 'mahi', 2, 'f', '2024-11-05', 2020, 'it', 'Pune', 'a@gmail.com', 'B-', 101, 2),
(62, 'palavi', 7777799966, 'F', '2005-02-08', 2024, 'ENTC', 'Pune', 'palavi@gmail.com', 'A+', 101, 2),
(63, 'Siya', 6669045908, 'F', '2004-05-10', 2024, 'Instru', 'Pune', 'siya@gmail.com', 'O+', 488, 2),
(64, 'mopi', 2, 'f', '2024-11-13', 2020, 'it', 'Pune', 'a@gmail.com', 'B-', 101, 2),
(65, 'mopi', 2, 'f', '2024-11-13', 2020, 'it', 'Pune', 'a@gmail.com', 'B-', 101, 2),
(66, 'Sam', 66758795, 'M', '2024-11-06', 2023, 'instru', 'mumba', 'sam@gmail.com', 'O+', 211, 2),
(67, 'Sam', 66758795, 'M', '2024-11-01', 2023, 'instru', 'mumba', 'sam@gmail.com', 'O+', 211, 2),
(68, 'Sam', 66758795, 'M', '2024-11-01', 2023, 'instru', 'mumba', 'sam@gmail.com', 'O+', 211, 2),
(69, 'Sam', 66758795, 'M', '2000-11-05', 2023, 'instru', 'mumba', 'sam@gmail.com', 'O+', 211, 2),
(70, 'Sam', 66758795, 'M', '2003-09-12', 2023, 'instru', 'mumba', 'sam@gmail.com', 'O+', 211, 2),
(71, 'Sam', 66758795, 'M', '2024-11-05', 2023, 'instru', 'mumba', 'sam@gmail.com', 'O+', 211, 2);

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

CREATE TABLE `visitors` (
  `visitor_id` int(11) NOT NULL COMMENT 'unique id for each each visitor''s entry',
  `s_id` int(11) NOT NULL,
  `visitor_name` varchar(100) NOT NULL COMMENT 'name of visitor',
  `visit_date` date NOT NULL COMMENT 'date of visit',
  `check_in_time` datetime NOT NULL,
  `check_out_time` datetime NOT NULL,
  `visitor_contact` bigint(20) NOT NULL,
  `relation_to_student` varchar(50) NOT NULL COMMENT 'relation between student and visitor'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visitors`
--

INSERT INTO `visitors` (`visitor_id`, `s_id`, `visitor_name`, `visit_date`, `check_in_time`, `check_out_time`, `visitor_contact`, `relation_to_student`) VALUES
(1, 1, 'Laxman', '2024-11-14', '2024-11-14 22:30:27', '2024-11-14 22:30:27', 9087654321, 'Uncle'),
(2, 14, 'Yuvraj', '2024-11-15', '2024-11-15 05:30:08', '2024-11-15 05:30:08', 9087654321, 'Brother'),
(4, 12, 'Ambika', '2024-11-15', '2024-11-07 17:56:00', '2024-11-07 21:56:00', 9087654321, 'Mother'),
(5, 12, 'Ambika', '2024-11-15', '2024-11-07 17:56:00', '2024-11-07 21:56:00', 9087654321, 'Mother'),
(6, 12, 'Ambika', '2024-11-15', '2024-11-07 17:56:00', '2024-11-07 21:56:00', 9087654321, 'Mother'),
(7, 12, 'Ambika', '2024-11-15', '2024-11-07 17:56:00', '2024-11-07 21:56:00', 9087654321, 'Mother'),
(8, 12, 'Ambika', '2024-11-15', '2024-11-07 17:56:00', '2024-11-07 21:56:00', 9087654321, 'Mother'),
(9, 12, 'Ambika', '2024-11-15', '2024-11-07 17:56:00', '2024-11-07 21:56:00', 9087654321, 'Mother'),
(10, 12, 'Ambika', '2024-11-15', '2024-11-08 08:37:00', '2024-11-15 20:37:00', 9087654321, 'Mother'),
(11, 12, 'Ambika', '2024-11-15', '2024-11-08 08:37:00', '2024-11-15 20:37:00', 9087654321, 'Mother'),
(12, 12, 'Ambika', '2024-11-15', '2024-11-08 08:37:00', '2024-11-15 20:37:00', 9087654321, 'Mother'),
(13, 12, 'Ambika', '2024-11-15', '2024-11-08 08:37:00', '2024-11-15 20:37:00', 9087654321, 'Mother'),
(15, 2, 'Akash', '2024-11-15', '2024-11-01 10:09:00', '2024-11-02 10:10:00', 7777722222, 'Brother'),
(16, 1, 'Ankur', '2024-11-15', '2024-08-08 13:38:00', '2024-11-09 13:38:00', 871111124536, 'Brother'),
(17, 1, 'Ankur', '2024-11-15', '2024-11-12 18:49:00', '2024-11-12 03:50:00', 871111124536, 'Brother');

--
-- Triggers `visitors`
--
DELIMITER $$
CREATE TRIGGER `update_visitor_checkout_time` BEFORE UPDATE ON `visitors` FOR EACH ROW IF NEW.check_out_time IS NULL THEN
        SET NEW.check_out_time = CURRENT_TIMESTAMP;
    END IF
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure for view `guardiancontactinfo`
--
DROP TABLE IF EXISTS `guardiancontactinfo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `guardiancontactinfo`  AS SELECT `g`.`s_id` AS `s_id`, `g`.`mother_name` AS `mother_name`, `g`.`mother_mob_number` AS `mother_mob_number`, `g`.`father_name` AS `father_name`, `g`.`father_mob_number` AS `father_mob_number`, `g`.`guardian_name` AS `guardian_name`, `g`.`guardian_mob_number` AS `guardian_mob_number` FROM `guardian` AS `g` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admission`
--
ALTER TABLE `admission`
  ADD PRIMARY KEY (`s_id`);

--
-- Indexes for table `guardian`
--
ALTER TABLE `guardian`
  ADD PRIMARY KEY (`s_id`);

--
-- Indexes for table `hostel`
--
ALTER TABLE `hostel`
  ADD PRIMARY KEY (`hostel_id`);

--
-- Indexes for table `nightout`
--
ALTER TABLE `nightout`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `s_id` (`s_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`s_id`);

--
-- Indexes for table `visitors`
--
ALTER TABLE `visitors`
  ADD PRIMARY KEY (`visitor_id`),
  ADD KEY `s_id` (`s_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `nightout`
--
ALTER TABLE `nightout`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `s_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `visitor_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique id for each each visitor''s entry', AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admission`
--
ALTER TABLE `admission`
  ADD CONSTRAINT `admission_ibfk_1` FOREIGN KEY (`s_id`) REFERENCES `student` (`s_id`);

--
-- Constraints for table `guardian`
--
ALTER TABLE `guardian`
  ADD CONSTRAINT `guardian_ibfk_1` FOREIGN KEY (`s_id`) REFERENCES `student` (`s_id`);

--
-- Constraints for table `nightout`
--
ALTER TABLE `nightout`
  ADD CONSTRAINT `nightout_ibfk_1` FOREIGN KEY (`s_id`) REFERENCES `student` (`s_id`);

--
-- Constraints for table `visitors`
--
ALTER TABLE `visitors`
  ADD CONSTRAINT `visitors_ibfk_1` FOREIGN KEY (`s_id`) REFERENCES `student` (`s_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
