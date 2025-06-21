-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 23, 2025 at 01:13 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `glowup`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminglowup`
--

CREATE TABLE `adminglowup` (
  `adminid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adminglowup`
--

INSERT INTO `adminglowup` (`adminid`, `name`, `email`, `password`) VALUES
(1, 'peter', 'peter@gmail.com', 'peter123'),
(2, 'josh', 'josh@gmail.com', 'josh123');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedbackid` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `rating` int(11) NOT NULL,
  `feedback_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedbackid`, `name`, `subject`, `message`, `rating`, `feedback_date`) VALUES
(27, 'cookie', 'Feedback about the quiz', 'I really like this quiz', 3, '2025-02-18 08:00:42'),
(28, 'daniel', '5 star', 'I like this ', 5, '2025-02-18 08:01:18'),
(29, 'Lana', 'Great Learning Tool! ', 'I really enjoyed the health and wellness quiz on your site! It was informative and engaging, helping me to understand important health topics better. Keep up the fantastic work!', 5, '2025-02-19 04:40:04'),
(30, 'Franzen', 'Suggestion to Enhance Quiz Content', 'I appreciate the effort put into your quizzes, but I think they could benefit from more diverse topics covering different aspects of wellness. Including more comprehensive content could help users gain a broader understanding of health.', 5, '2025-02-19 04:40:44'),
(31, 'Harlin', 'Feedback on Quiz Accuracy', 'I was disappointed with some of the quiz answers, which seemed incorrect or misleading. Specifically, [mention specific question or topic]. Could you review the content to ensure accuracy and reliability?', 2, '2025-02-19 04:42:28'),
(32, 'cookie', 'hello i am cookie', 'I really like this page. 5 star mantap', 5, '2025-02-19 05:55:14');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_db`
--

CREATE TABLE `quiz_db` (
  `quiz_id` int(5) NOT NULL,
  `question` text NOT NULL,
  `A` varchar(150) NOT NULL,
  `B` varchar(150) NOT NULL,
  `C` varchar(150) NOT NULL,
  `D` varchar(150) NOT NULL,
  `correct_answer` char(1) NOT NULL,
  `points` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_db`
--

INSERT INTO `quiz_db` (`quiz_id`, `question`, `A`, `B`, `C`, `D`, `correct_answer`, `points`) VALUES
(1, 'Which nutrient is the main source of energy for the body?', 'Protein', 'Carbohydrates', 'Vitamins', 'Minerals', 'A', 5),
(2, 'What is the primary function of protein in the body?', 'To provide energy', 'To build and repair tissues ', 'To store fat', 'To produce vitamins', 'B', 5),
(3, 'Which vitamin is essential for good vision?', 'Vitamin A', 'Vitamin C', 'Vitamin D', 'Vitamin K', 'A', 5),
(4, 'What mineral is important for strong bones and teeth?\r\n', 'Iron', 'Calcium ', 'Zinc', 'Potassium', 'B', 5),
(5, 'Which of the following foods is a good source of dietary fiber?', 'White rice\r\n', 'French fries', 'Whole wheat bread', 'Ice cream', 'C', 5),
(6, 'Which type of fat is considered the healthiest for the heart?', 'Saturated fat', 'Trans fat', 'Monounsaturated fat', 'Hydrogenated fat', 'C', 5),
(7, 'What is the recommended daily intake of water for an average adult?', '1 liter', '2-3 liters', '5 liters', '6-7 liters', 'B', 5),
(8, 'Which vitamin helps the body absorb calcium?', ' Vitamin A', 'Vitamin B12', 'Vitamin C', 'Vitamin D', 'D', 5),
(9, 'Which of these foods is the best source of iron?', 'Bananas', 'Spinach', 'Cheese', 'Yogurt', 'B', 5),
(18, 'Which of the following is a good source of dietary iron?', 'Broccoli', 'Whole grains\n', 'Red meat', 'Citrus fruits', 'C', 5),
(20, 'Test question', 'option a', 'option b', 'option c', 'option d', 'D', 5);

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `topic` varchar(50) NOT NULL,
  `rating_1` int(11) NOT NULL,
  `rating_2` int(11) NOT NULL,
  `rating_3` int(11) NOT NULL,
  `rating_4` int(11) NOT NULL,
  `rating_5` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `topic`, `rating_1`, `rating_2`, `rating_3`, `rating_4`, `rating_5`) VALUES
(1, 'Nutrition', 0, 1, 3, 3, 12),
(2, 'Fitness', 1, 2, 2, 6, 9),
(3, 'Mental Health', 0, 3, 1, 1, 9),
(4, 'Self-Care', 0, 2, 1, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `userglowup`
--

CREATE TABLE `userglowup` (
  `studentid` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userglowup`
--

INSERT INTO `userglowup` (`studentid`, `name`, `email`, `password`) VALUES
(1, 'Lana', 'lleestut0@weather.com', 'jO5&NV+}D6!~'),
(2, 'Franzen', 'fslimmon1@de.vu', 'fW3$0IEit6e_u1a'),
(3, 'Saunderson', 'sgoudie2@auda.org.au', 'cB1#oQ0uvg{k\">'),
(4, 'Harlin', 'htomicki3@wikispaces.com', 'mA1~j%@g$8uzU'),
(5, 'Dionysus', 'dpamphilon4@wired.com', 'rJ3\'%d\\t|'),
(6, 'Standford', 'ssich5@usgs.gov', 'uV5$TlORWj&Omt&7'),
(7, 'Ezri', 'emoralas6@people.com.cn', 'gZ5,e7,>B'),
(8, 'Ignacius', 'ihallitt7@utexas.edu', 'sL3*?/?Pd8q%0'),
(9, 'Alden', 'acoare8@hatena.ne.jp', 'uL0|IUNL'),
(10, 'Nyssa', 'nmcgennis9@redcross.org', 'eB2`|d1c+z`|Y,'),
(11, 'Edwin', 'edwin@gmail.com', 'lala123'),
(19, 'cookie', 'cookie@gmail.com', 'cook123'),
(25, 'daniel', 'daniel@gmail.com', 'daniel123'),
(27, 'weibin', 'weibin@gmail.com', 'weibin123'),
(28, 'xuexian', 'xuexian@gmail.com', 'xx123');

-- --------------------------------------------------------

--
-- Table structure for table `user_answers`
--

CREATE TABLE `user_answers` (
  `play_id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `selected_answer` char(1) NOT NULL,
  `correct_answer` char(1) NOT NULL,
  `is_correct` tinyint(1) NOT NULL,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `points` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_answers`
--

INSERT INTO `user_answers` (`play_id`, `user_id`, `quiz_id`, `selected_answer`, `correct_answer`, `is_correct`, `completed_at`, `points`) VALUES
('1739863841', '25', 1, 'A', '', 1, '2025-02-18 07:30:47', 5),
('1739863841', '25', 2, 'B', '', 1, '2025-02-18 07:30:49', 5),
('1739863841', '25', 3, 'C', '', 0, '2025-02-18 07:30:51', 0),
('1739863841', '25', 4, 'D', '', 0, '2025-02-18 07:30:53', 0),
('1739863841', '25', 5, 'C', '', 1, '2025-02-18 07:30:55', 5),
('1739863841', '25', 6, 'C', '', 1, '2025-02-18 07:30:57', 5),
('1739863841', '25', 7, 'B', '', 1, '2025-02-18 07:30:58', 5),
('1739863841', '25', 8, 'D', '', 1, '2025-02-18 07:31:00', 5),
('1739863841', '25', 9, 'B', '', 1, '2025-02-18 07:31:01', 5),
('1739863888', '25', 1, 'A', '', 1, '2025-02-18 07:31:30', 5),
('1739863888', '25', 2, 'A', '', 0, '2025-02-18 07:31:31', 0),
('1739863888', '25', 3, 'C', '', 0, '2025-02-18 07:31:33', 0),
('1739863888', '25', 4, 'D', '', 0, '2025-02-18 07:31:34', 0),
('1739863888', '25', 5, 'D', '', 0, '2025-02-18 07:31:35', 0),
('1739863888', '25', 6, 'C', '', 1, '2025-02-18 07:31:37', 5),
('1739863888', '25', 7, 'C', '', 0, '2025-02-18 07:31:38', 0),
('1739863888', '25', 8, 'C', '', 0, '2025-02-18 07:31:39', 0),
('1739863888', '25', 9, 'B', '', 1, '2025-02-18 07:31:41', 5),
('1739865788', '19', 1, 'A', '', 1, '2025-02-18 08:03:10', 5),
('1739865788', '19', 2, 'D', '', 0, '2025-02-18 08:03:11', 0),
('1739865788', '19', 3, 'C', '', 0, '2025-02-18 08:03:13', 0),
('1739865788', '19', 4, 'D', '', 0, '2025-02-18 08:03:14', 0),
('1739865788', '19', 5, 'C', '', 1, '2025-02-18 08:03:16', 5),
('1739865788', '19', 6, 'C', '', 1, '2025-02-18 08:03:18', 5),
('1739865788', '19', 7, 'B', '', 1, '2025-02-18 08:03:20', 5),
('1739865788', '19', 8, 'D', '', 1, '2025-02-18 08:03:22', 5),
('1739865788', '19', 9, 'B', '', 1, '2025-02-18 08:03:25', 5),
('1739865788', '19', 18, 'C', '', 0, '2025-02-18 08:03:27', 0),
('1739939106', '19', 1, 'B', '', 0, '2025-02-19 04:25:08', 0),
('1739939106', '19', 2, 'D', '', 0, '2025-02-19 04:25:09', 0),
('1739939106', '19', 3, 'C', '', 0, '2025-02-19 04:25:11', 0),
('1739939106', '19', 4, 'C', '', 0, '2025-02-19 04:25:14', 0),
('1739939106', '19', 5, 'C', '', 1, '2025-02-19 04:25:18', 5),
('1739939106', '19', 6, 'C', '', 1, '2025-02-19 04:25:20', 5),
('1739939106', '19', 7, 'C', '', 0, '2025-02-19 04:25:22', 0),
('1739939106', '19', 8, 'C', '', 0, '2025-02-19 04:25:23', 0),
('1739939106', '19', 9, 'D', '', 0, '2025-02-19 04:25:26', 0),
('1739939106', '19', 18, 'C', '', 1, '2025-02-19 04:25:29', 5),
('1739939398', '19', 1, 'D', '', 0, '2025-02-19 04:30:00', 0),
('1739939398', '19', 2, 'C', '', 0, '2025-02-19 04:30:02', 0),
('1739939398', '19', 3, 'D', '', 0, '2025-02-19 04:30:03', 0),
('1739939398', '19', 4, 'A', '', 0, '2025-02-19 04:30:05', 0),
('1739939398', '19', 5, 'C', '', 1, '2025-02-19 04:30:06', 5),
('1739939398', '19', 6, 'C', '', 1, '2025-02-19 04:30:08', 5),
('1739939398', '19', 7, 'C', '', 0, '2025-02-19 04:30:10', 0),
('1739939398', '19', 8, 'D', '', 1, '2025-02-19 04:30:12', 5),
('1739939398', '19', 9, 'B', '', 1, '2025-02-19 04:30:14', 5),
('1739939398', '19', 18, 'B', '', 0, '2025-02-19 04:30:16', 0),
('1739939429', '19', 1, 'B', '', 0, '2025-02-19 04:30:36', 0),
('1739939429', '19', 2, 'A', '', 0, '2025-02-19 04:30:40', 0),
('1739939429', '19', 3, 'B', '', 0, '2025-02-19 04:30:43', 0),
('1739939429', '19', 4, 'B', '', 1, '2025-02-19 04:30:46', 5),
('1739939429', '19', 5, 'C', '', 1, '2025-02-19 04:30:48', 5),
('1739939429', '19', 6, 'C', '', 1, '2025-02-19 04:30:49', 5),
('1739939429', '19', 7, 'B', '', 1, '2025-02-19 04:30:51', 5),
('1739939429', '19', 8, 'D', '', 1, '2025-02-19 04:30:53', 5),
('1739939429', '19', 9, 'B', '', 1, '2025-02-19 04:30:55', 5),
('1739939429', '19', 18, 'C', '', 1, '2025-02-19 04:30:57', 5),
('1739939468', '19', 1, 'A', '', 1, '2025-02-19 04:31:19', 5),
('1739939468', '19', 2, 'A', '', 0, '2025-02-19 04:31:23', 0),
('1739939468', '19', 3, 'C', '', 0, '2025-02-19 04:31:25', 0),
('1739939468', '19', 4, 'B', '', 1, '2025-02-19 04:31:28', 5),
('1739939468', '19', 5, 'C', '', 1, '2025-02-19 04:31:30', 5),
('1739939468', '19', 6, 'C', '', 1, '2025-02-19 04:31:32', 5),
('1739939468', '19', 7, 'B', '', 1, '2025-02-19 04:31:33', 5),
('1739939468', '19', 8, 'C', '', 0, '2025-02-19 04:31:36', 0),
('1739939468', '19', 9, 'B', '', 1, '2025-02-19 04:31:40', 5),
('1739939468', '19', 18, 'C', '', 1, '2025-02-19 04:31:41', 5),
('1739939522', '19', 1, 'A', '', 1, '2025-02-19 04:32:04', 5),
('1739939522', '19', 2, 'A', '', 0, '2025-02-19 04:32:06', 0),
('1739939522', '19', 3, 'A', '', 1, '2025-02-19 04:32:08', 5),
('1739939522', '19', 4, 'A', '', 0, '2025-02-19 04:32:09', 0),
('1739939522', '19', 5, 'A', '', 0, '2025-02-19 04:32:11', 0),
('1739939522', '19', 6, 'A', '', 0, '2025-02-19 04:32:12', 0),
('1739939522', '19', 7, 'A', '', 0, '2025-02-19 04:32:14', 0),
('1739939522', '19', 8, 'A', '', 0, '2025-02-19 04:32:17', 0),
('1739939522', '19', 9, 'A', '', 0, '2025-02-19 04:32:19', 0),
('1739939522', '19', 18, 'A', '', 0, '2025-02-19 04:32:20', 0),
('1739941224', '19', 1, 'D', '', 0, '2025-02-19 05:01:32', 0),
('1739941224', '19', 2, 'D', '', 0, '2025-02-19 05:01:34', 0),
('1739941224', '19', 3, 'A', '', 1, '2025-02-19 05:01:35', 5),
('1739941224', '19', 4, 'B', '', 1, '2025-02-19 05:01:37', 5),
('1739941224', '19', 5, 'C', '', 1, '2025-02-19 05:01:39', 5),
('1739941224', '19', 6, 'C', '', 1, '2025-02-19 05:01:41', 5),
('1739941224', '19', 7, 'B', '', 1, '2025-02-19 05:01:42', 5),
('1739941224', '19', 8, 'C', '', 0, '2025-02-19 05:01:44', 0),
('1739941224', '19', 9, 'C', '', 0, '2025-02-19 05:01:45', 0),
('1739941224', '19', 18, 'C', '', 1, '2025-02-19 05:01:47', 5),
('1739942267', '4', 1, 'B', '', 0, '2025-02-19 05:17:49', 0),
('1739942267', '4', 2, 'D', '', 0, '2025-02-19 05:17:50', 0),
('1739942267', '4', 3, 'B', '', 0, '2025-02-19 05:17:52', 0),
('1739942267', '4', 4, 'D', '', 0, '2025-02-19 05:17:53', 0),
('1739942267', '4', 5, 'B', '', 0, '2025-02-19 05:17:55', 0),
('1739942267', '4', 6, 'C', '', 1, '2025-02-19 05:17:57', 5),
('1739942267', '4', 7, 'B', '', 1, '2025-02-19 05:17:58', 5),
('1739942267', '4', 8, 'C', '', 0, '2025-02-19 05:18:00', 0),
('1739942267', '4', 9, 'C', '', 0, '2025-02-19 05:18:02', 0),
('1739942267', '4', 18, 'C', '', 1, '2025-02-19 05:18:03', 5),
('1739942354', '3', 1, 'B', '', 0, '2025-02-19 05:19:16', 0),
('1739942354', '3', 2, 'B', '', 1, '2025-02-19 05:19:18', 5),
('1739942354', '3', 3, 'B', '', 0, '2025-02-19 05:19:20', 0),
('1739942354', '3', 4, 'B', '', 1, '2025-02-19 05:19:22', 5),
('1739942354', '3', 5, 'C', '', 1, '2025-02-19 05:19:26', 5),
('1739942354', '3', 6, 'C', '', 1, '2025-02-19 05:19:28', 5),
('1739942354', '3', 7, 'B', '', 1, '2025-02-19 05:19:29', 5),
('1739942354', '3', 8, 'D', '', 1, '2025-02-19 05:19:32', 5),
('1739942354', '3', 9, 'B', '', 1, '2025-02-19 05:19:34', 5),
('1739942354', '3', 18, 'C', '', 1, '2025-02-19 05:19:36', 5),
('1739942399', '7', 1, 'A', '', 1, '2025-02-19 05:20:01', 5),
('1739942399', '7', 2, 'B', '', 1, '2025-02-19 05:20:03', 5),
('1739942399', '7', 3, 'B', '', 0, '2025-02-19 05:20:05', 0),
('1739942399', '7', 4, 'B', '', 1, '2025-02-19 05:20:08', 5),
('1739942399', '7', 5, 'C', '', 1, '2025-02-19 05:20:11', 5),
('1739942399', '7', 6, 'C', '', 1, '2025-02-19 05:20:13', 5),
('1739942399', '7', 7, 'B', '', 1, '2025-02-19 05:20:14', 5),
('1739942399', '7', 8, 'C', '', 0, '2025-02-19 05:20:18', 0),
('1739942399', '7', 9, 'B', '', 1, '2025-02-19 05:20:19', 5),
('1739942399', '7', 18, 'C', '', 1, '2025-02-19 05:20:21', 5),
('1739942809', '27', 1, 'C', '', 0, '2025-02-19 05:26:52', 0),
('1739942809', '27', 2, 'C', '', 0, '2025-02-19 05:26:54', 0),
('1739942809', '27', 3, 'C', '', 0, '2025-02-19 05:26:56', 0),
('1739942809', '27', 4, 'C', '', 0, '2025-02-19 05:26:58', 0),
('1739942809', '27', 5, 'C', '', 1, '2025-02-19 05:27:00', 5),
('1739942809', '27', 6, 'C', '', 1, '2025-02-19 05:27:02', 5),
('1739942809', '27', 7, 'C', '', 0, '2025-02-19 05:27:04', 0),
('1739942809', '27', 8, 'C', '', 0, '2025-02-19 05:27:05', 0),
('1739942809', '27', 9, 'C', '', 0, '2025-02-19 05:27:07', 0),
('1739942809', '27', 18, 'C', '', 1, '2025-02-19 05:27:09', 5),
('1739944122', '19', 1, 'A', '', 1, '2025-02-19 05:50:08', 5),
('1739944122', '19', 2, 'B', '', 1, '2025-02-19 05:50:10', 5),
('1739944122', '19', 3, 'B', '', 0, '2025-02-19 05:50:12', 0),
('1739944122', '19', 4, 'D', '', 0, '2025-02-19 05:50:14', 0),
('1739944122', '19', 5, 'B', '', 0, '2025-02-19 05:50:15', 0),
('1739944122', '19', 6, 'C', '', 1, '2025-02-19 05:50:16', 5),
('1739944122', '19', 7, 'B', '', 1, '2025-02-19 05:50:18', 5),
('1739944122', '19', 8, 'B', '', 0, '2025-02-19 05:50:20', 0),
('1739944122', '19', 9, 'D', '', 0, '2025-02-19 05:50:22', 0),
('1739944122', '19', 18, 'B', '', 0, '2025-02-19 05:50:27', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminglowup`
--
ALTER TABLE `adminglowup`
  ADD PRIMARY KEY (`adminid`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedbackid`);

--
-- Indexes for table `quiz_db`
--
ALTER TABLE `quiz_db`
  ADD PRIMARY KEY (`quiz_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userglowup`
--
ALTER TABLE `userglowup`
  ADD PRIMARY KEY (`studentid`);

--
-- Indexes for table `user_answers`
--
ALTER TABLE `user_answers`
  ADD PRIMARY KEY (`play_id`,`quiz_id`),
  ADD KEY `user-answer` (`quiz_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adminglowup`
--
ALTER TABLE `adminglowup`
  MODIFY `adminid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedbackid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `quiz_db`
--
ALTER TABLE `quiz_db`
  MODIFY `quiz_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `userglowup`
--
ALTER TABLE `userglowup`
  MODIFY `studentid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
