-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 13, 2025 at 02:54 PM
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
-- Database: `unieventportal`
--

-- --------------------------------------------------------

--
-- Table structure for table `app_settings`
--

CREATE TABLE `app_settings` (
  `setting_key` varchar(255) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `app_settings`
--

INSERT INTO `app_settings` (`setting_key`, `setting_value`, `created_at`, `updated_at`) VALUES
('approval_required_for_new_users', '0', '2025-07-13 12:11:13', '2025-07-13 12:11:13'),
('auto_logout_time', '30', '2025-07-13 12:11:13', '2025-07-13 12:11:13'),
('contact_email', 'info@unieventportal.com', '2025-07-13 12:11:13', '2025-07-13 12:11:13'),
('date_format', 'Y-m-d', '2025-07-13 12:11:13', '2025-07-13 12:11:13'),
('default_user_role', '1', '2025-07-13 12:11:13', '2025-07-13 12:11:13'),
('email_notifications', '1', '2025-07-13 12:11:13', '2025-07-13 12:11:13'),
('facebook_url', 'https://facebook.com/unieventportal', '2025-07-13 12:11:13', '2025-07-13 12:11:13'),
('google_analytics_id', 'UA-XXXXX-Y', '2025-07-13 12:11:13', '2025-07-13 12:11:13'),
('linkedin_url', 'https://linkedin.com/company/unieventportal', '2025-07-13 12:11:13', '2025-07-13 12:11:13'),
('maintenance_mode', '0', '2025-07-13 12:11:13', '2025-07-13 12:11:13'),
('password_min_length', '8', '2025-07-13 12:11:13', '2025-07-13 12:11:13'),
('password_require_special', '0', '2025-07-13 12:11:13', '2025-07-13 12:11:13'),
('site_logo_url', 'assets/images/websiteLogo.png', '2025-07-13 12:11:13', '2025-07-13 12:11:13'),
('site_title', 'UniEventPortal', '2025-07-13 12:11:13', '2025-07-13 12:11:13'),
('timezone', 'Asia/Kolkata', '2025-07-13 12:11:13', '2025-07-13 12:11:13'),
('time_format', 'H:i', '2025-07-13 12:11:13', '2025-07-13 12:11:13'),
('twitter_url', 'https://twitter.com/unieventportal', '2025-07-13 12:11:13', '2025-07-13 12:11:13');

-- --------------------------------------------------------

--
-- Table structure for table `colleges`
--

CREATE TABLE `colleges` (
  `college_id` int(11) NOT NULL,
  `college_name` varchar(255) NOT NULL,
  `college_code` varchar(20) NOT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `colleges`
--

INSERT INTO `colleges` (`college_id`, `college_name`, `college_code`, `address`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Bhaisaheb Sawant Ayurved College', 'BSAC001', 'Mumbai-Goa Road, Khaskilwada, Sawantwadi, Maharashtra 416510', '2025-06-29 13:00:00', '2025-06-29 13:00:00', 1),
(2, 'Government Polytechnic Malvan', 'GPM002', 'Kumbharmath, Tal. Malvan, Sindhudurg 416606', '2025-06-29 13:00:00', '2025-06-29 13:00:00', 1),
(3, 'Vengurla Homoeopathic Medical College', 'VHMC003', 'Vengurla Main Road, Vengurla, Maharashtra 416516', '2025-06-29 13:00:00', '2025-06-29 13:00:00', 1),
(4, 'MITM College of Engineering, Malvan', 'MITM004', 'Village Sukhalwad, Near Sindhudurg Railway Station, Malvan, Oras, Maharashtra 416534', '2025-06-29 13:00:00', '2025-06-29 13:00:00', 1),
(5, 'SSPM College of Engineering, Kankavali', 'SSPM005', 'A/P Harkul Budruk, SH-181, Tal-Kankavali, Maharashtra 416602', '2025-06-29 13:00:00', '2025-06-29 13:00:00', 1),
(6, 'SSPM’s Medical College, Ranbambuli', 'SSPMMED006', 'Padave Malvan-Kasal Rd, Ranbambuli, Maharashtra 416534', '2025-06-29 13:00:00', '2025-06-29 13:00:00', 1),
(7, 'Government Medical College, Sindhudurg', 'GMC007', 'Oros, Sindhudurg district, Maharashtra', '2025-06-29 13:00:00', '2025-06-29 13:00:00', 1),
(8, 'College of Horticulture, Mulde, Kudal', 'CHMK008', 'Mulde, Taluka Kudal, Sindhudurg', '2025-06-29 13:00:00', '2025-06-29 13:00:00', 1),
(9, 'V P College of Pharmacy, Madkhol', 'VPP009', 'Madkhol, Sindhudurg', '2025-06-29 13:00:00', '2025-06-29 13:00:00', 1),
(10, 'Shri Saraswati Institute of Pharmacy, Tondavali', 'SSIPT010', 'Tondavali, Sindhudurg', '2025-06-29 13:00:00', '2025-06-29 13:00:00', 1),
(11, 'Shri Angarsiddha Shikshan Prasarak Mandal D Pharmacy Institute', 'SASPM011', 'Sangulwadi, Sindhudurg, Maharashtra', '2025-06-29 13:00:00', '2025-06-29 13:00:00', 1),
(12, 'Shree Pushpasen Sawant College of Diploma in Pharmacy', 'SPS012', 'Sindhudurg, Maharashtra', '2025-06-29 13:00:00', '2025-06-29 13:00:00', 1),
(13, 'Sindhudurg Shikshan Prasarak Mandal’s College of Engineering', 'SSPMENG013', 'Sindhudurg, Maharashtra', '2025-06-29 13:00:00', '2025-06-29 13:00:00', 1),
(14, 'ASPM College of Pharmacy', 'ASPM014', 'Sangulwadi, Sindhudurg, Maharashtra', '2025-06-29 13:00:00', '2025-06-29 13:00:00', 1),
(15, 'Amdar Deepakbhai Kesarkar Science College (ADKSC)', 'ADKSC015', 'Sindhudurg, Maharashtra', '2025-06-29 13:00:00', '2025-06-29 13:00:00', 1),
(16, 'Vijayrao Naik College of Pharmacy', 'VNCP016', 'Sindhudurg, Maharashtra', '2025-06-29 13:00:00', '2025-06-29 13:00:00', 1),
(17, 'Yashwantrao Bhonsale Polytechnic', 'YBP017', 'Sawantwadi, Sindhudurg, Maharashtra', '2025-06-29 13:00:00', '2025-06-29 13:00:00', 1),
(18, 'Yashwantrao Bhonsale College of Pharmacy', 'YBCP018', 'Sindhudurg, Maharashtra', '2025-06-29 13:00:00', '2025-06-29 13:00:00', 1),
(19, 'Dnyanwardhini Charitable Trusts Arts & Commerce College', 'DCTACC019', 'Sindhudurg, Maharashtra', '2025-06-29 13:00:00', '2025-06-29 13:00:00', 1),
(20, 'Chhatrapati Shivaji College of Agriculture', 'CSCA020', 'Sindhudurg, Maharashtra', '2025-06-29 13:00:00', '2025-06-29 13:00:00', 1),
(21, 'Shri Pancham Khemraj Mahavidyalaya', 'SPKM021', 'Near Moti Talav, Sawantwadi, Sindhudurg, Maharashtra 416510', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(22, 'S H Kelkar College', 'SHK022', 'Deogad, Sindhudurg, Maharashtra 416613', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(23, 'Raosaheb Gogate College of Commerce & Smt Saraswatibai Ganashet Walke College of Arts', 'RGCW023', 'Banda Panval, Sawantwadi, Sindhudurg 416511', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(24, 'Phondaghat Education Society\'s Arts and Commerce College', 'PESAC024', 'Kankavli, Sindhudurg, Maharashtra', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(25, 'Anandibai Raorane Arts & Commerce College', 'ARAC025', 'Sindhudurg (Vaibhavwadi area)', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(26, 'S K Patil Sindhudurg Mahavidyalaya', 'SKP026', 'Malvan, Sindhudurg, Maharashtra', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(27, 'Shree Anant Smriti Charitable Trust Kasals Institute Of Nursing', 'KINN027', 'Sindhudurg, Maharashtra', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(28, 'Dr B B Gaitonde Charitable Trusts B S Bandekar College of Fine Art', 'BBGT028', 'Survey No. 54/01, Near Forest Bhavan, Salaiwada, Sawantwadi, Sindhudurg 416510', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(29, 'Guruvarya B S Naik Memorial Trusts College of Arts & Commerce', 'GBSN029', 'C/o R.P.D. High School, Sawantwadi, Sindhudurg', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(30, 'Jaihind Gramonnati Sansthas Jaihind College of Science', 'JGSC030', 'At Post Salgaon, Kudal, Sindhudurg', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(31, 'Sai Shikshan & Samajik Sanshodhan Sanstha College of Arts Commerce & Science', 'SSSAC31', 'A/P- Kharyewadi, Oros, Kudal, Sindhudurg, Maharashtra', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(32, 'Mangaon Panchkroshi Shikshan Prasarak Mandals College of Arts', 'MPSPM32', 'A/P Mangaon, Taluka Kudal, Sindhudurg, Maharashtra 416519', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(33, 'Late B. B. Sawant Adhyapak Vidhyalaya Digas Sindhudurg College', 'LBBSD33', 'A/P Digas, Tal. Kudal, Sindhudurg, Maharashtra 416521', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(34, 'Indrayani Shikshan Prasarak Mandal Shri Pushpasen Sawant College of Diploma Pharmacy', 'ISPMS34', 'Digas, Wadi Humarmala, Jaywant Nagar, Kudal, Sindhudurg, Maharashtra', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(35, 'Sant Rawool Maharaj Mahavidyalaya (S.R.M. Mahavidyalaya), Kudal', 'SRMM35', 'Kudal, Sindhudurg, Maharashtra', '2025-06-30 13:00:00', '2025-07-06 06:29:35', 1),
(36, 'Br. Nath Pai College of B.Sc. Nursing', 'BNP36', 'Sindhudurg, Maharashtra', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(37, 'Balasaheb Khardekar College, Vengurla', 'BKK37', 'Vengurla, Sindhudurg, Maharashtra', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(38, 'Achara College of Management Studies, Achara', 'ACMS38', 'Achara, Tal. Malvan, Sindhudurg, Maharashtra', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(39, 'Shri Shantaram Krishnaji College of Education, Deogad', 'SVMS39', 'Deogad, Sindhudurg, Maharashtra', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(40, 'Dr Waradkar College of Arts & Commerce, Katta', 'KPDR40', 'Katta, Tal. Malvan, Sindhudurg, Maharashtra', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(41, 'Balasaheb Khardekar College of Fine Art', 'BKKFA041', 'Vengurla, Sindhudurg, Maharashtra', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(42, 'Metropolitan Institute of Technology & Management (MITM)', 'MITM042', 'Malvan, Sindhudurg, Maharashtra', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(43, 'Shree Saraswati Institute of Pharmacy, Tondavali', 'SSIP043', 'Tondavali, Sindhudurg, Maharashtra', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(44, 'Aarna Institute of Maritime Studies', 'AIMS044', 'Sindhudurg, Maharashtra', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(45, 'Mai Institute of Hotel Management', 'MIHM045', 'Sindhudurg, Maharashtra', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(46, 'Kankavali Arts & Commerce College', 'KACC047', 'Kankavali, Sindhudurg, Maharashtra 416602', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(47, 'Laxmibai Sitaram Halbe College of Arts Commerce & Science, Dodamarg', 'LSH048', 'Dodamarg, Sindhudurg, Maharashtra', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(48, 'Pramod Ravindra Dhuri College of Education, Salgaon', 'PRDC049', 'Salgaon, Kudal, Sindhudurg, Maharashtra', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(49, 'Late Parvatibai Mahadeorao Dhuri Arts College, Mangaon', 'LMDC050', 'Mangaon, Kudal, Sindhudurg, Maharashtra 416519', '2025-06-30 13:00:00', '2025-06-30 13:00:00', 1),
(50, 'Goa University', 'GU050', 'Taleigao Plateau, Goa 403206', '2025-07-11 15:32:43', '2025-07-11 15:32:43', 1),
(51, 'Goa College of Engineering', 'GCE051', 'Farmagudi, Ponda, Goa 403401', '2025-07-11 15:32:43', '2025-07-11 15:32:43', 1),
(52, 'Goa Medical College', 'GMC052', 'Bambolim, Goa 403202', '2025-07-11 15:32:43', '2025-07-11 15:32:43', 1),
(53, 'Goa College of Pharmacy', 'GCP053', '18th June Road, Panaji, Goa 403001', '2025-07-11 15:32:43', '2025-07-11 15:32:43', 1),
(54, 'Goa Dental College and Hospital', 'GDCH054', 'Bambolim, Goa 403202', '2025-07-11 15:32:43', '2025-07-11 15:32:43', 1),
(55, 'Dhempe College of Arts and Science', 'DCAS055', 'Miramar, Panaji, Goa 403001', '2025-07-11 15:32:43', '2025-07-11 15:32:43', 1),
(56, 'St. Xavier’s College', 'SXC056', 'Mapusa, Goa 403507', '2025-07-11 15:32:43', '2025-07-11 15:32:43', 1),
(57, 'Carmel College for Women', 'CCW057', 'Nuvem, South Goa, Goa 403713', '2025-07-11 15:32:43', '2025-07-11 15:32:43', 1),
(58, 'Don Bosco College of Engineering', 'DBCE058', 'Fatorda, Margao, Goa 403602', '2025-07-11 15:32:43', '2025-07-11 15:32:43', 1),
(59, 'BITS Pilani, Goa Campus', 'BITS059', 'Zuarinagar, Goa 403726', '2025-07-11 15:32:43', '2025-07-11 15:32:43', 1),
(60, 'Dr. Balasaheb Sawant Konkan Krishi Vidyapeeth', 'DBSKKV060', 'Dapoli, Ratnagiri, Maharashtra 415712', '2025-07-11 15:52:50', '2025-07-11 15:52:50', 1),
(61, 'College of Fisheries, Ratnagiri', 'CFR061', 'Ratnagiri, Maharashtra 415612', '2025-07-11 15:52:50', '2025-07-11 15:52:50', 1),
(62, 'Finolex Academy of Management & Technology', 'FAMT062', 'P-60, MIDC, Mirjole, Ratnagiri, Maharashtra 415639', '2025-07-11 15:52:50', '2025-07-11 15:52:50', 1),
(63, 'Rajendra Mane College of Engineering & Technology', 'RMCET063', 'Ambav Devrukh, Ratnagiri, Maharashtra 415804', '2025-07-11 15:52:50', '2025-07-11 15:52:50', 1),
(64, 'Rajaram Shinde College of Engineering', 'RSCE064', 'Pedhambe, Chiplun, Ratnagiri, Maharashtra 415605', '2025-07-11 15:52:50', '2025-07-11 15:52:50', 1),
(65, 'Gharda Institute of Technology', 'GIT065', 'At Post Lavel, Tal. Khed, Dist. Ratnagiri, Maharashtra 415708', '2025-07-11 15:52:50', '2025-07-11 15:52:50', 1),
(66, 'Government Polytechnic Ratnagiri', 'GPR066', 'Near Regional Transport Office, Ratnagiri, Maharashtra 415612', '2025-07-11 15:52:50', '2025-07-11 15:52:50', 1),
(67, 'Indira Institute of Pharmacy', 'IIP067', 'Sadavali, Tal. Sangameshwar, Ratnagiri, Maharashtra 415611', '2025-07-11 15:52:50', '2025-07-11 15:52:50', 1),
(68, 'B.K.L. Walawalkar Rural Medical College', 'BKLW068', 'Sawarde, Chiplun, Ratnagiri, Maharashtra 415606', '2025-07-11 15:52:50', '2025-07-11 15:52:50', 1),
(69, 'Yogita Dental College & Hospital', 'YDCH069', 'Khed, Ratnagiri, Maharashtra 415709', '2025-07-11 15:52:50', '2025-07-11 15:52:50', 1),
(70, 'College of Agricultural Engineering & Technology', 'CAET070', 'Dapoli, Ratnagiri, Maharashtra 415712', '2025-07-11 15:52:50', '2025-07-11 15:52:50', 1),
(71, 'College of Agriculture, Dapoli', 'CAD071', 'Dapoli, Ratnagiri, Maharashtra 415712', '2025-07-11 15:52:50', '2025-07-11 15:52:50', 1),
(72, 'College of Horticulture, Dapoli', 'CHD072', 'Dapoli, Ratnagiri, Maharashtra 415712', '2025-07-11 15:52:50', '2025-07-11 15:52:50', 1),
(73, 'Government College of Education, Ratnagiri', 'GCER073', 'Boarding Road, Ratnagiri, Maharashtra 415612', '2025-07-11 15:52:50', '2025-07-11 15:52:50', 1),
(74, 'Government College of Pharmacy, Ratnagiri', 'GCPR074', 'Near Thiba Palace, Polytechnic Campus, Ratnagiri, Maharashtra 415612', '2025-07-11 15:52:50', '2025-07-11 15:52:50', 1),
(75, 'Model College of Arts, Commerce & Science, Ratnagiri', 'MCACS075', 'Ratnagiri, Maharashtra 415612', '2025-07-11 15:52:50', '2025-07-11 15:52:50', 1),
(76, 'Govindraoji Nikam College of Agriculture', 'GNCA076', 'Mandki-Palvan, Chiplun, Ratnagiri, Maharashtra 415641', '2025-07-11 15:52:50', '2025-07-11 15:52:50', 1),
(77, 'Gurukul College of Computer Science & IT', 'GCCSIT077', 'Bahadurshekh Naka, Chiplun, Ratnagiri, Maharashtra', '2025-07-11 15:52:50', '2025-07-11 15:52:50', 1),
(78, 'Loknete Shamraoji Peje College of ACS, Ratnagiri', 'LSPC078', 'Shivar Ambere, Ratnagiri, Maharashtra', '2025-07-11 15:52:51', '2025-07-11 15:52:51', 1),
(79, 'Loknete Gopinathji Munde ACS College, Mandangad', 'LGAMC079', 'Bankot Road, Mandangad, Ratnagiri, Maharashtra 415203', '2025-07-11 15:52:51', '2025-07-11 15:52:51', 1),
(80, 'Madanbhai Sura Institute of Business Management', 'MSIBM080', 'Ratnagiri, Maharashtra', '2025-07-11 15:52:51', '2025-07-11 15:52:51', 1),
(81, 'Dr Datar Science & Joshi Commerce College', 'DDSJC081', 'Chiplun, Ratnagiri, Maharashtra', '2025-07-11 15:52:51', '2025-07-11 15:52:51', 1),
(82, 'G.B. Tatyasaheb Khare Commerce & Dhere Arts College', 'GBTKCAD082', 'Guhagar, Ratnagiri, Maharashtra 415703', '2025-07-11 15:52:51', '2025-07-11 15:52:51', 1),
(83, 'Marg Tamhane Dr Tata Saheb Natu College of Arts', 'MTDSN083', 'Margtamhane, Chiplun, Ratnagiri, Maharashtra 415702', '2025-07-11 15:52:51', '2025-07-11 15:52:51', 1),
(84, 'Nyayadhish Tatyasaheb Athalye Arts & Sapre Commerce College', 'NTASCC084', 'Deorukh, Ratnagiri, Maharashtra', '2025-07-11 15:52:51', '2025-07-11 15:52:51', 1),
(85, 'NK Varadkar & VR Belose College, Dapoli', 'NKVRC085', 'Dapoli, Ratnagiri, Maharashtra 415712', '2025-07-11 15:52:51', '2025-07-11 15:52:51', 1),
(86, 'Navnirman Shikshan Sanstha College of ACS', 'NSSACS086', 'Navadi, Sangameshwar, Ratnagiri, Maharashtra', '2025-07-11 15:52:51', '2025-07-11 15:52:51', 1),
(87, 'New Education Society ACS College', 'NESA087', 'Ratnagiri, Maharashtra 415612', '2025-07-11 15:52:51', '2025-07-11 15:52:51', 1),
(88, 'Mrs Shailaja Shinde ACS Senior College', 'MSSASC088', 'Pedhambe, Chiplun, Ratnagiri, Maharashtra', '2025-07-11 15:52:51', '2025-07-11 15:52:51', 1),
(89, 'Kunbi Shikshan Prasarak Sanstha College of Education', 'KSPCE089', 'Ratnagiri, Maharashtra', '2025-07-11 15:52:51', '2025-07-11 15:52:51', 1),
(90, 'Maharshi Karve Stree Shikshan Sanstha B.Ed College for Women', 'MKSSS090', 'Shirgaon, Ratnagiri, Maharashtra 415629', '2025-07-11 15:52:51', '2025-07-11 15:52:51', 1),
(91, 'MES College of Nursing, Khed', 'MESN091', 'Ghanekhunt-Lote, Khed, Ratnagiri, Maharashtra', '2025-07-11 15:52:51', '2025-07-11 15:52:51', 1),
(92, 'Ramraje College of Hotel & Tourism Management', 'RCHTM092', 'Dapoli, Ratnagiri, Maharashtra', '2025-07-11 15:52:51', '2025-07-11 15:52:51', 1),
(93, 'Sahyadri College of Hotel Management & Tourism', 'SCHMT093', 'Ratnagiri, Maharashtra', '2025-07-11 15:52:51', '2025-07-11 15:52:51', 1),
(94, 'Sahyadri Shikshan Sanstha College of Pharmacy', 'SSSCP094', 'Sawarde (Wahal Phata), Chiplun, Ratnagiri, Maharashtra 415606', '2025-07-11 15:52:51', '2025-07-11 15:52:51', 1),
(95, 'Sahyadri Shikshan Sanstha Polytechnic', 'SSSP095', 'Sawarde, Chiplun, Ratnagiri, Maharashtra 415606', '2025-07-11 15:52:51', '2025-07-11 15:52:51', 1),
(96, 'Shramik Kisan Seva Samiti’s Peje Mahavidyalaya, Shivar Ambere', 'SKSSP096', 'Shivar Ambere, Ratnagiri, Maharashtra', '2025-07-11 15:52:51', '2025-07-11 15:52:51', 1),
(97, 'Rajaram Shinde Diploma College of Pharmacy', 'RSDCP097', 'Pedhambe, Chiplun, Ratnagiri, Maharashtra 415603', '2025-07-11 15:52:51', '2025-07-11 15:52:51', 1),
(98, 'Rajaram Shinde Institute of Engineering & Technology', 'RSIET098', 'Pedhambe, Chiplun, Ratnagiri, Maharashtra 415603', '2025-07-11 15:52:51', '2025-07-11 15:52:51', 1),
(99, 'Sharadchandraji Pawar College of Agriculture', 'SPCA099', 'Dahiwali, Chiplun, Ratnagiri, Maharashtra 415606', '2025-07-11 15:52:51', '2025-07-11 15:52:51', 1),
(100, 'R.P. Gogate Arts & Science & R.V. Joglekar Commerce College', 'GSRVJ100', 'Near District Court, Ratnagiri, Maharashtra 415612', '2025-07-11 15:52:51', '2025-07-11 15:52:51', 1),
(101, 'Vishwakarma Sahajeevan Institute of Management', 'VSIM101', 'Khed, Ratnagiri, Maharashtra 415709', '2025-07-11 15:54:31', '2025-07-11 15:54:31', 1),
(102, 'Adhyapak Vidyalaya', 'AV102', 'Rajapur, Ratnagiri, Maharashtra 416704', '2025-07-11 15:54:31', '2025-07-11 15:54:31', 1),
(103, 'Navkonkan Education Society’s DBJ College', 'DBJ103', 'Chiplun, Ratnagiri, Maharashtra 415605', '2025-07-11 15:54:31', '2025-07-11 15:54:31', 1),
(104, 'Mohini Murari Mayekar Arts & Commerce College', 'MMMACC104', 'Chafe, Malgund, Ratnagiri, Maharashtra 415615', '2025-07-11 15:54:31', '2025-07-11 15:54:31', 1),
(105, 'Kohinoor College of Hotel & Tourism Management', 'KCHTM105', 'Bhatye, Ratnagiri, Maharashtra 415612', '2025-07-11 15:54:31', '2025-07-11 15:54:31', 1),
(106, 'Maharshi Parshuram College of Engineering', 'MPCE106', 'Velneshwar, Guhagar, Ratnagiri, Maharashtra 415729', '2025-07-11 15:54:31', '2025-07-11 15:54:31', 1),
(107, 'Dapoli Homoeopathy College', 'DHC107', 'Dapoli, Ratnagiri, Maharashtra 415712', '2025-07-11 15:54:31', '2025-07-11 15:54:31', 1),
(108, 'Shradchandraji Pawar College of Food Technology', 'SPCFT108', 'Ratnagiri, Maharashtra 415612', '2025-07-11 15:54:31', '2025-07-11 15:54:31', 1),
(109, 'Marine Biological Research Station', 'MBRS109', 'Ratnagiri, Maharashtra 415612', '2025-07-11 15:54:31', '2025-07-11 15:54:31', 1),
(110, 'Ayurved Mahavidyalaya', 'AM110', 'Khed, Ratnagiri, Maharashtra 415709', '2025-07-11 15:54:31', '2025-07-11 15:54:31', 1),
(111, 'Mumbai College of Hotel Management and Catering', 'MCHMC111', 'Ratnagiri, Maharashtra 415612', '2025-07-11 15:54:31', '2025-07-11 15:54:31', 1),
(127, 'Abasaheb Marathe Arts & Commerce College', 'AMACC127', 'Ghatkopar West, Mumbai, Maharashtra 400086', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(128, 'Bhavan’s College', 'BC128', 'Munshi Nagar, Andheri West, Mumbai, Maharashtra 400058', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(129, 'St. Andrew’s College of Arts, Science & Commerce', 'SACASC129', 'Bandstand, Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(130, 'Sardark Patel Institute of Technology & Research', 'SPITR130', 'Andheri West, Mumbai, Maharashtra 400058', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(131, 'Sathaye College', 'SC131', 'Vidyavihar East, Mumbai, Maharashtra 400077', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(132, 'Seth Hirachand Mutha College of Arts, Commerce & Science', 'SHM132', 'Kharghar, Navi Mumbai, Maharashtra 410210', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(133, 'Siddharth College of Arts, Science & Commerce', 'SCSC133', 'Fort, Mumbai, Maharashtra 400001', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(134, 'Siddharth College of Law', 'SCL134', 'Fort, Mumbai, Maharashtra 400001', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(135, 'Rizvi College of Arts, Science & Commerce', 'RCASC135', 'Carter Road, Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(136, 'Guru Nanak Khalsa College of Arts, Science & Commerce', 'GNK136', 'Matunga East, Mumbai, Maharashtra 400019', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(137, 'Mithibai College of Arts, Science & Commerce', 'MCSC137', 'Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(138, 'Ramnarain Ruia College', 'RRC138', 'Matunga East, Mumbai, Maharashtra 400019', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(139, 'Wilson College', 'WC139', 'Girgaon, Marine Lines, Mumbai, Maharashtra 400020', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(140, 'Elphinstone College', 'EC140', 'Fort, Mumbai, Maharashtra 400001', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(141, 'K.J. Somaiya College of Arts & Commerce', 'KJS141', 'Vidyavihar East, Mumbai, Maharashtra 400077', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(142, 'H.R. College of Commerce & Economics', 'HRCCE142', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(143, 'Jai Hind College', 'JHC143', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(144, 'Kishinchand Chellaram College', 'KCC144', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(145, 'St. Xavier’s College, Mumbai', 'SXC145', 'Dhobitalao, Fort, Mumbai, Maharashtra 400002', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(146, 'Don Bosco College, Kurla (Arts, Sci & Comm)', 'DBC146', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(147, 'S.K. Somaiya College of Arts, Commerce & Science', 'SKS147', 'Vidyavihar East, Mumbai, Maharashtra 400077', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(148, 'SIES College of Arts, Science & Commerce', 'SIES148', 'Sion West, Mumbai, Maharashtra 400022', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(149, 'R.A. Podar College of Commerce & Economics', 'RAP149', 'Matunga East, Mumbai, Maharashtra 400019', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(150, 'Sophia College for Women', 'SCW150', 'Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(151, 'Shri Ram College of Commerce', 'SRCC151', 'South Campus, Lutyens’ Delhi (Oops—different university!)', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 0),
(152, 'Chhabildas College of Commerce & Economics', 'CCCE152', 'Tardeo, Mumbai, Maharashtra 400034', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(153, 'Veermata Jijabai Technological Institute', 'VJTI153', 'Matunga, Mumbai, Maharashtra 400019', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(154, 'Indian Institute of Technology Bombay', 'IITB154', 'Powai, Mumbai, Maharashtra 400076', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(155, 'Grant Government Medical College', 'GGMC155', 'J.J. Hospital Campus, Byculla, Mumbai, Maharashtra 400008', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(156, 'Seth G.S. Medical College', 'GSMC156', 'K.E.M. Hospital Campus, Parel, Mumbai, Maharashtra 400012', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(157, 'Lokmanya Tilak Municipal Medical College', 'LTMMC157', 'Sion, Mumbai, Maharashtra 400022', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(158, 'Tolani College of Commerce', 'TCC158', 'Andheri West, Mumbai, Maharashtra 400058', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(159, 'Phoenix Vallabhbhai Patel College of Engineering', 'VPCoE159', 'Mulund West, Mumbai, Maharashtra 400080', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(160, 'K.J. Somaiya Institute of Management Studies & Research', 'KJSIM160', 'Ghatkopar East, Mumbai, Maharashtra 400077', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(161, 'Jamnalal Bajaj Institute of Management Studies', 'JBIMS161', 'Fort, Mumbai, Maharashtra 400001', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(162, 'WeSchool (Prin. L. N. Welingkar Institute)', 'Weschool162', 'Lower Parel, Mumbai, Maharashtra 400013', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(163, 'Universal College of Engineering', 'UCE163', 'Vasai West, Mumbai, Maharashtra 401202', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(164, 'Shah & Anchor Kutchhi Engineering College', 'SAKEC164', 'Chembur, Mumbai, Maharashtra 400074', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(165, 'Thadomal Shahani Engineering College', 'TSEC165', 'Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(166, 'Don Bosco Institute of Technology', 'DBIT166', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(167, 'Sardar Patel Institute of Technology', 'SPIT167', 'Andheri West, Mumbai, Maharashtra 400058', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(168, 'NMIMS School of Business Management', 'NMIMS168', 'Shriram Campus, Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(169, 'Narsee Monjee Institute of Management Studies', 'NM169', 'Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(170, 'Tata Institute of Social Sciences', 'TISS170', 'Deonar, Mumbai, Maharashtra 400088', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(171, 'SP Jain Institute of Management and Research', 'SPJIMR171', 'Juhu Tara Road, Mumbai, Maharashtra 400049', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(172, 'Terna Engineering College', 'TEC172', 'Nerul, Navi Mumbai, Maharashtra 400706', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(173, 'DY Patil College of Engineering', 'DYPCOE173', 'Akurdi, Navi Mumbai, Maharashtra 411044', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(174, 'Pillai College of Engineering', 'PCE174', 'Panvel, Navi Mumbai, Maharashtra 410206', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(175, 'Somaiya Vidyavihar Institute of Technology', 'SVIT175', 'Vidya Vihar, Mumbai, Maharashtra 400077', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(176, 'Kalsekar Technical Campus', 'KTC176', 'New Panvel, Navi Mumbai, Maharashtra 410206', '2025-07-11 16:02:06', '2025-07-11 16:02:06', 1),
(177, 'Fr. Conceicao Rodrigues College of Engineering', 'CRCE177', 'Bandstand Road, Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(178, 'Datta Meghe College of Engineering', 'DMCE178', 'Airoli, Navi Mumbai, Maharashtra 400708', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(179, 'Vidyalankar Institute of Technology', 'VIT179', 'Wadala East, Mumbai, Maharashtra 400037', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(180, 'VIVA Institute of Technology', 'VIVA180', 'Virar West, Mumbai, Maharashtra 401303', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(181, 'Rizvi College of Engineering', 'RCOE181', 'Electronic Zone, Dahisar East, Mumbai, Maharashtra 400068', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(182, 'Pillai HOC College of Engineering Technology', 'PHOCT182', 'Panvel, Navi Mumbai, Maharashtra 410206', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(183, 'Mumbai Educational Trust’s Institute of Management', 'METIM183', 'Shivaji Park, Dadar, Mumbai, Maharashtra 400028', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(184, 'SIES Graduate School of Technology', 'SGST184', 'Nerul, Navi Mumbai, Maharashtra 400706', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(185, 'SCT College of Engineering', 'SCT185', 'Powai, Mumbai, Maharashtra 400076', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(186, 'Thakur College of Engineering & Technology', 'TCET186', 'Kandivali East, Mumbai, Maharashtra 400101', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(187, 'Rajiv Gandhi Institute of Technology', 'RGIT187', 'Gandhi Nagar, Andheri West, Mumbai, Maharashtra 400053', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(188, 'G. H. Raisoni College of Engineering & Management', 'GHREM188', 'Pimpri, Pune (Oops, different district)', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 0),
(189, 'Institute of Chemical Technology', 'ICT189', 'Matunga East, Mumbai, Maharashtra 400019', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(190, 'National Institute of Industrial Engineering', 'NITIE190', 'Vihar Lake Road, Powai, Mumbai, Maharashtra 400087', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(191, 'Podar College of Architecture', 'PCA191', 'Santacruz West, Mumbai, Maharashtra 400054', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(192, 'Sir JJ College of Architecture', 'JJCA192', 'Fort, Mumbai, Maharashtra 400001', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(193, 'L. S. Raheja College of Architecture', 'LSRCA193', 'Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(194, 'S. M. Shetty College of Pharmacy', 'SMSCP194', 'Phase-II, Mira Road East, Thane, Maharashtra 401107', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(195, 'SKB College of Pharmacy', 'SKBCP195', 'Mumbai–Agra Road, Shankarpali, Thane, Maharashtra 421302', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(196, 'Dr. L. H. Hiranandani College of Pharmacy', 'LHCP196', 'Powai, Mumbai, Maharashtra 400076', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(197, 'RM Bhattad Institute of Pharmacy', 'RMBIP197', 'Plot No 14, Vidhyavihar West, Mumbai, Maharashtra 400086', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(198, 'D. J. Sanghvi College of Engineering', 'DJSE198', 'Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(199, 'Chandrabai H. Patel College of Pharmacy', 'CHPCP199', 'Uttan Road, Bhayandar West, Thane, Maharashtra 401101', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(200, 'Bharti Vidyapeeth’s College of Pharmacy', 'BVCP200', 'Sector-8, CBD Belapur, Navi Mumbai, Maharashtra 400614', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(201, 'Institute of Distance & Open Learning (IDOL)', 'IDOL201', 'Fort, Mumbai, Maharashtra 400032', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(202, 'Podar JN Petit Technical High School & Jr College', 'PJT202', 'Matunga East, Mumbai, Maharashtra 400019', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(203, 'IIDE – Indian Institute of Digital Education', 'IIDE203', 'Khar West, Mumbai, Maharashtra 400052', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(204, 'Whistling Woods International', 'WWI204', 'Goregaon East, Mumbai, Maharashtra 400065', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(205, 'MET’s School of Business Management', 'METSBM205', 'Chembur, Mumbai, Maharashtra 400071', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(206, 'SIES Institute of Comprehensive Education', 'SIESICE206', 'Sion West, Mumbai, Maharashtra 400022', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(207, 'R A Podar Institute of Management', 'RAPIM207', 'Matunga East, Mumbai, Maharashtra 400019', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(208, 'Mumbai Institute of Hotel Management & Training', 'MIHMT208', 'Ratnagari–Mulund Link Road, Bhandup West, Mumbai, Maharashtra 400078', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(209, 'AISSMS Institute of Hotel Management', 'AISSMS209', 'Pune (again outside Mumbai)', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 0),
(210, 'Ishwar Desai College', 'IDC210', 'Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(211, 'K C College of Management Studies', 'KCMS211', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(212, 'Rizvi Institute of Management Studies & Research', 'RIMSR212', 'Carter Road, Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(213, 'NMIMS School of Commerce', 'NMSC213', 'Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(214, 'Thadomal Shahani Engineering College – Pharmacy', 'TSECPharm214', 'Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(215, 'Universal College of Education & Research', 'UCER215', 'Vasai, Palghar (outside Mumbai)', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 0),
(216, 'Sophia Polytechnic', 'SP216', 'Ghatkopar East, Mumbai, Maharashtra 400077', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(217, 'Sir J. J. College of Applied Arts', 'JJCAA217', 'Fort, Mumbai, Maharashtra 400001', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(218, 'Rustomjee Academy for Global Careers', 'RAGC218', 'Bhandup West, Mumbai, Maharashtra 400078', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(219, 'Aditya College of Commerce & Economics', 'ACCE219', 'Borivali West, Mumbai, Maharashtra 400092', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(220, 'Abeda Inamdar Senior College', 'AISC220', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(221, 'Aurora’s Technological & Research Institute', 'ATRI221', 'Navi Mumbai (Panvel), Maharashtra 410206', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(222, 'IES Management College & Research Centre', 'IESMCRC222', 'Nerul, Navi Mumbai, Maharashtra 400706', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(223, 'Lala Lajpat Rai College of Commerce & Economics', 'LLRCCE223', 'Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(224, 'M.M.K. College of Commerce & Economics', 'MMKCCE224', 'Mazgaon, Mumbai, Maharashtra 400010', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(225, 'S.K. Porwal College of Arts & Commerce', 'SKP225', 'Borivali East, Mumbai, Maharashtra 400066', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(226, 'Universal College of Multimedia & Mass Communication', 'UCMMC226', 'Wadala East, Mumbai, Maharashtra 400037', '2025-07-11 16:03:02', '2025-07-11 16:03:02', 1),
(227, 'Government Law College, Mumbai', 'GLC227', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(229, 'Nair Hospital Dental College', 'NHDC229', 'Dr. A. L. Nair Road, Mumbai, Maharashtra 400008', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(230, 'K. J. Somaiya College of Physiotherapy', 'KJSCP230', 'Sion East, Mumbai, Maharashtra 400022', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(231, 'N. M. Wadia Institute of Nursing Education', 'NMWINE231', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(232, 'Bombay College of Pharmacy', 'BCP232', 'Kalina, Santacruz East, Mumbai, Maharashtra 400098', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(233, 'NMIMS School of Pharmacy', 'NMPharm233', 'Mumbai, Maharashtra 400056', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(235, 'Lal Bahadur Shastri College of Pharmacy', 'LBSPharm235', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(236, 'MGM Institute of Health Sciences', 'MGMHS236', 'Kamothe, Navi Mumbai, Maharashtra 410209', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(237, 'ISMS College of Physiotherapy', 'ISMS237', 'Sanpada, Navi Mumbai, Maharashtra 400705', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(238, 'Dr. D. Y. Patil Medical College, Nerul', 'DYPMed238', 'Nerul Sector-15, Navi Mumbai, Maharashtra 400706', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(239, 'Terna Dental College & Hospital', 'TDCH239', 'Nerul, Navi Mumbai, Maharashtra 400706', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(240, 'Indian School of Rehabilitation Sciences', 'ISRS240', 'Kurla East, Mumbai, Maharashtra 400024', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(241, 'Nair Dental College', 'NDC241', 'Mumbai, Maharashtra 400008', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(242, 'Sai Sneh Hospital School of Nursing', 'SSHSN242', 'Bhabha Nagar, Goregaon West, Mumbai, Maharashtra 400104', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(243, 'Hinduja Hospital College of Nursing', 'HHCN243', 'Mahim Health Centre, Mumbai, Maharashtra 400016', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(244, 'Tata Memorial Centre – Homi Bhabha Cancer Hospital', 'TMC244', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(245, 'KJ Somaiya College of Nursing', 'KJSCN245', 'Sion East, Mumbai, Maharashtra 400022', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(246, 'Bombay Paramedical Institute', 'BPI246', 'Kalina, Mumbai, Maharashtra 400098', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(247, 'National College of Midwives', 'NCM247', 'Kurla East, Mumbai, Maharashtra 400024', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(248, 'Bhabha Atomic Research Centre Training School', 'BARCTS248', 'Anushakti Nagar, Mumbai, Maharashtra 400094', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(249, 'Lilavati Hospital College of Nursing', 'LHRCNC249', 'Bandstand Road, Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(250, 'Institute of Speech & Hearing', 'ISH250', 'Chandivali, Mumbai, Maharashtra 400072', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(251, 'G. M. Modi Institute of Pharmacy', 'GMIP251', 'Belapur, Navi Mumbai, Maharashtra 400614', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(252, 'Dr. R N Cooper Municipal Dental College', 'RNCMDC252', 'Juhu, Mumbai, Maharashtra 400049', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(253, 'Institute of Psychological Education & Research', 'IPER253', 'Juhu, Mumbai, Maharashtra 400049', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(254, 'Bombay VAMNICOM Skill University – Mumbai Campus', 'BVSMC254', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(255, 'Bombay Veterinary College', 'BVCARC255', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(256, 'Institute of Material Technology', 'IMT256', 'Vile Parle East, Mumbai, Maharashtra 400057', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(257, 'Shrimati Liladhar Parshottam Thakore Arts College', 'SLPTAC257', 'Andheri West, Mumbai, Maharashtra 400058', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(258, 'Santacruz College of Speech & Hearing', 'SCSH258', 'Santacruz East, Mumbai, Maharashtra 400055', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(259, 'Azad Degree College of Commerce & Economics', 'ADCCE259', 'Sion West, Mumbai, Maharashtra 400022', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(262, 'Bombay Hospital College of Nursing', 'BHCN262', 'Marine Lines, Mumbai, Maharashtra 400020', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(265, 'Nirmala Niketan College of Social Work', 'NNCSW265', 'Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(266, 'Matunga Educational Society’s College of Speech & Hearing', 'MESCOSH266', 'Matunga East, Mumbai, Maharashtra 400019', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(267, 'Ramniranjan Jhunjhunwala College', 'RJCasC267', 'Ghatkopar East, Mumbai, Maharashtra 400077', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(268, 'D. G. Ruparel College', 'DGRASC268', 'Matunga East, Mumbai, Maharashtra 400019', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(269, 'Usha Pravin Gandhi College of Management', 'UPGCM269', 'Juhu, Mumbai, Maharashtra 400049', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(270, 'Vaze College of Arts, Science & Commerce', 'VASC270', 'Mulund West, Mumbai, Maharashtra 400080', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(271, 'Dr. Babasaheb Ambedkar Technological University – Mumbai Campus', 'DBATU271', 'Mankhurd, Mumbai, Maharashtra 400088', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(272, 'Dasharatha Shastri Police Training College', 'DSPTC272', 'Kalina, Mumbai, Maharashtra 400098', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(273, 'National Institute of Fashion Technology, Mumbai', 'NIFT273', 'Santacruz East, Mumbai, Maharashtra 400055', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(275, 'Rizvi Law College', 'RLC275', 'Sion East, Mumbai, Maharashtra 400022', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(276, 'KET’s V.G. Vaze College', 'VVCC276', 'Mulund East, Mumbai, Maharashtra 400081', '2025-07-11 16:24:39', '2025-07-11 16:24:39', 1),
(277, 'Thakur College of Science & Commerce', 'TCSC277', 'Kandivali West, Mumbai, Maharashtra 400067', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(278, 'SIES College of Pharmacy', 'SIESP278', 'Sion West, Mumbai, Maharashtra 400022', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(279, 'Vidyalankar School of Information Technology', 'VSIT279', 'Wadala East, Mumbai, Maharashtra 400037', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(280, 'Universal College of Engineering (Pharmacy)', 'UCEP280', 'Vasai West, Mumbai, Maharashtra 401202', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(281, 'Knockout Institute of Film Education', 'KIFE281', 'Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(282, 'Sophia Polytechnic – Applied Arts', 'SPA282', 'Ghatkopar East, Mumbai, Maharashtra 400077', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(283, 'Sant Gadge Maharaj College of Engineering', 'SGMCE283', 'Mankhurd, Mumbai, Maharashtra 400088', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(284, 'Dr. Vithalrao Vikhe Patil Commerce College', 'DVVP284', 'Sion East, Mumbai, Maharashtra 400022', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(285, 'Amity Global Business School, Mumbai', 'AGBS285', 'Bandra Kurla Complex, Mumbai, Maharashtra 400051', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(286, 'Spicer Adventist University (Mumbai Campus)', 'SAU286', 'Malad West, Mumbai, Maharashtra 400064', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(287, 'Fr. Agnel Multipurpose School & Junior College', 'FAMSC287', 'Vasai East, Mumbai, Maharashtra 401208', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(288, 'Ryan International School & Junior College', 'RIJC288', 'Malad West, Mumbai, Maharashtra 400064', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(289, 'Mumbai Institute of Graphic Arts', 'MIGA289', 'Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(290, 'National College, Bandra', 'NCB290', 'Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(291, 'Shahani Engineering College', 'SEC291', 'Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(292, 'St. John College of Engineering & Management', 'SJCEM292', 'Thane West, Maharashtra 400601', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(293, 'Wilson Institute of Business Management', 'WIBM293', 'Girgaon, Marine Lines, Mumbai, Maharashtra 400020', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(294, 'K.C. College of Science and Commerce', 'KCCSC294', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(295, 'Gourishankar Institute of Engineering & Technology', 'GIET295', 'Vile Parle East, Mumbai, Maharashtra 400057', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(296, 'Universal College of Pharmacy', 'UCP296', 'Vasai West, Mumbai, Maharashtra 401202', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(297, 'Rajiv Gandhi College of Education', 'RGCE297', 'Nerul, Navi Mumbai, Maharashtra 400706', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(298, 'Shree Bholekar Institute of Education', 'SBIE298', 'Thane West, Maharashtra 400602', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(299, 'Dr. Ambedkar Institute of Management Studies and Research', 'DAIMSR299', 'Mankhurd, Mumbai, Maharashtra 400088', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(300, 'Anjuman-I-Islam’s Kalsekar Technical Campus', 'AIKTC300', 'Panvel, Navi Mumbai, Maharashtra 410206', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(301, 'D. Y. Patil College of Physiotherapy', 'DYPCP301', 'Nerul, Navi Mumbai, Maharashtra 400706', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(302, 'Sies Indian Institute of Environment Management', 'SIEM302', 'Sion West, Mumbai, Maharashtra 400022', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(303, 'Kishinchand Chellaram College Junior Science', 'KCCJS303', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(304, 'Bhavan\'s Junior College', 'BJC304', 'Munshi Nagar, Andheri West, Mumbai, Maharashtra 400058', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(305, 'Fr. Agnel Institute of Hotel Management', 'FAIHM305', 'Pilerne, Bardez (Oops—outside Mumbai)', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 0),
(306, 'Institute of Management & Entrepreneurship Development (Ahmedabad)', 'IMED306', 'Ahmedabad (outside Mumbai)', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 0),
(307, 'Eva Concordia Junior College', 'ECJC307', 'Amboli, Borivali West, Mumbai, Maharashtra 400091', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(308, 'Hirachand Nemchand College of Commerce & Economics', 'HNCCE308', 'Khar West, Mumbai, Maharashtra 400052', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(309, 'Gurukul Institute of Engineering & Technology', 'GIET309', 'Chembur, Mumbai, Maharashtra 400074', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(310, 'Rajiv Gandhi Institute of Pharmacy', 'RGIP310', 'Nerul, Navi Mumbai, Maharashtra 400706', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(311, 'Institute of Hotel Management and Catering Technology', 'IHMCT311', 'Andheri East, Mumbai, Maharashtra 400069', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(312, 'G.T. Saraswati College of Education', 'GTSCE312', 'Goregaon West, Mumbai, Maharashtra 400062', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(313, 'Guru Nanak Khalsa College Junior College', 'GNKCJC313', 'Matunga East, Mumbai, Maharashtra 400019', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(314, 'Bhavans Parliaments Junior College', 'BPJC314', 'Khar West, Mumbai, Maharashtra 400052', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(315, 'K. J. Somaiya Junior College', 'KJSJC315', 'Vidyavihar East, Mumbai, Maharashtra 400077', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(316, 'St. Elizabeth’s Technical Campus', 'SETC316', 'Nerul, Navi Mumbai, Maharashtra 400706', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(317, 'Datta Meghe Polytechnic', 'DMP317', 'Airoli, Navi Mumbai, Maharashtra 400708', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(318, 'MET Institute of Pharmacy', 'METP318', 'Chembur, Mumbai, Maharashtra 400071', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(319, 'Balaji Institute of Foreign Trade', 'BIFT319', 'Malad West, Mumbai, Maharashtra 400064', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(320, 'ITM Institute of Health Sciences', 'ITMIHS320', 'Vasai West, Mumbai, Maharashtra 401202', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(321, 'Anjuman-I-Islam\'s Kalsekar College Junior', 'AIKJC321', 'New Panvel, Navi Mumbai, Maharashtra 410206', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(322, 'Don Bosco College Junior', 'DBCJ322', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(323, 'Nerul College of Arts', 'NCA323', 'Nerul, Navi Mumbai, Maharashtra 400706', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(324, 'St. Joseph\'s College of Commerce', 'SJCC324', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(325, 'Sophia College of Higher Secondary', 'SCHS325', 'Sion East, Mumbai, Maharashtra 400022', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(326, 'Wilson Junior College', 'WJC326', 'Girgaon, Marine Lines, Mumbai, Maharashtra 400020', '2025-07-11 16:25:28', '2025-07-11 16:25:28', 1),
(327, 'H.R. College Junior College', 'HRCCJ327', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(328, 'Bhavan’s Bandra College of Commerce & Arts', 'BBCCA328', 'Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(329, 'Garware College of Arts & Science', 'GCAS329', 'Karjat (Oops—outside Mumbai)', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 0),
(330, 'St. Andrew’s Junior College', 'SAJC330', 'Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(331, 'Jamnabai Narsee School of Scholarship', 'JNSS331', 'Juhu, Mumbai, Maharashtra 400049', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(332, 'Ryan International Junior College', 'RIJC332', 'Malad West, Mumbai, Maharashtra 400064', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(333, 'Somaiya Vidyavihar Junior College', 'SVJC333', 'Vidya Vihar East, Mumbai, Maharashtra 400077', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(334, 'Mother’s International Junior College', 'MIJC334', 'Khar West, Mumbai, Maharashtra 400052', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(335, 'Anjuman-I-Islam’s Kalsekar Junior College', 'AIKJC335', 'Panvel, Navi Mumbai, Maharashtra 410206', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(336, 'St. John’s Universal College of Education', 'SJUCE336', 'Thane West, Maharashtra 400601', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(337, 'K.C. Law College', 'KCLC337', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(338, 'Pillai College of Arts, Commerce & Science', 'PCACS338', 'Panvel, Navi Mumbai, Maharashtra 410206', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(339, 'Raheja College of Arts & Commerce', 'RCAC339', 'Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(340, 'St. Theresa’s College', 'STC340', 'Santacruz East, Mumbai, Maharashtra 400055', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(341, 'Uxbridge College of Commerce & Foundation', 'UCCF341', 'Andheri West, Mumbai, Maharashtra 400058', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(342, 'VPM’s B.N. Bandodkar College of Science', 'VPMBNBC342', 'Thane West, Maharashtra 400606', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(343, 'Shivshakti Vidyalaya & Junior College', 'SVJC343', 'Bhandup West, Mumbai, Maharashtra 400078', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(344, 'DRBCCC Hindu College', 'DRBCCC344', 'Khar West, Mumbai, Maharashtra 400052', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(345, 'Jaihind College Junior College', 'JCJC345', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(346, 'SIES Graduate School of Commerce', 'SGSC346', 'Sion West, Mumbai, Maharashtra 400022', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(347, 'K.P.T.’s College of Science & Commerce', 'KPTCSC347', 'Dahisar East, Mumbai, Maharashtra 400068', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(348, 'Neves Junior College', 'NJC348', 'Neves, Vasai East, Maharashtra 401208', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(349, 'Ryan International Junior College (Andheri)', 'RIJCA349', 'Andheri West, Mumbai, Maharashtra 400058', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(350, 'Soul City College of Arts & Commerce', 'SCAC350', 'Goregaon East, Mumbai, Maharashtra 400063', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(351, 'Wiley Institute of Technology', 'WIT351', 'Kurla East, Mumbai, Maharashtra 400024', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(352, 'Universal Junior College', 'UJC352', 'Vasai West, Mumbai, Maharashtra 401202', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(353, 'Valia College of Commerce & Arts', 'VCCA353', 'Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1);
INSERT INTO `colleges` (`college_id`, `college_name`, `college_code`, `address`, `created_at`, `updated_at`, `status`) VALUES
(354, 'VESP Mandal’s College of Commerce & Science', 'VMCCS354', 'Veer Nirmalankar Marg, Mumbai', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(355, 'Xavier’s Junior College', 'XJC355', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(356, 'Yashwantrao Chavan College of Science', 'YCCS356', 'Dombivli West, Maharashtra 421202', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 0),
(357, 'Zee Institute of Media Arts', 'ZIMA357', 'Andheri West, Mumbai, Maharashtra 400053', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(358, 'Thakur Vidya Mandir & Junior College', 'TVMJC358', 'Kandivali East, Mumbai, Maharashtra 400101', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(359, 'Sidhharth Management College', 'SMC359', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(360, 'Ryan International College of Commerce', 'RICC360', 'Malad West, Mumbai, Maharashtra 400064', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(361, 'Budha Charitable & Educational Trust’s Junior College', 'BCETJC361', 'Borivali West, Mumbai, Maharashtra 400092', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(362, 'Chembur English High School & Jr College', 'CEHSJC362', 'Chembur, Mumbai, Maharashtra 400071', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(363, 'Don Bosco Junior College', 'DBJC363', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(364, 'Gokuldas Dagdu Shah Junior College', 'GDSJC364', 'Bandra East, Mumbai, Maharashtra 400051', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(365, 'I.E.S. Junior College', 'IESJC365', 'Nerul, Navi Mumbai, Maharashtra 400706', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(366, 'Indira Memorial Trust’s Junior College', 'IMTJC366', 'Vile Parle East, Mumbai, Maharashtra 400057', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(367, 'Jayaram College of Commerce & Science', 'JCCS367', 'Thane West, Maharashtra 400607', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(368, 'Kalina Junior College', 'KJC368', 'Kalina, Santacruz East, Mumbai, Maharashtra 400098', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(369, 'Lokmanya Tilak College of Science', 'LTCS369', 'Grant Road, Mumbai, Maharashtra 400007', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(370, 'Mithibai College Junior College', 'MCJC370', 'Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(371, 'National Junior College', 'NJC371', 'Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(372, 'Parle Tilak Vidyalaya & Ra. Junior College', 'PTVRC372', 'Parle East, Mumbai, Maharashtra 400057', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(373, 'SIES College Junior College', 'SCJC373', 'Sion West, Mumbai, Maharashtra 400022', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(374, 'SM Shetty College Junior College', 'SSCJC374', 'Santacruz East, Mumbai, Maharashtra 400055', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(375, 'St. Andrew’s Junior College (Kurla)', 'SAJCK375', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(376, 'St. Xavier’s Junior College (CST)', 'SXJC376', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 16:26:33', '2025-07-11 16:26:33', 1),
(377, 'Bhavan\'s College Junior College', 'BCJC377', 'Munshi Nagar, Andheri West, Mumbai, Maharashtra 400058', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(378, 'Kirti M. Doongursee College', 'KMDC378', 'Dadar West, Mumbai, Maharashtra 400028', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(379, 'Kirti M. Doongursee College Junior College', 'KMDCJC379', 'Dadar West, Mumbai, Maharashtra 400028', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(380, 'Ismail Yusuf College', 'IYC380', 'Jogeshwari East, Mumbai, Maharashtra 400060', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(381, 'Garware College Junior College', 'GCJC381', 'Karjat (Oops—outside Mumbai)', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 0),
(382, 'Gokhale College of Commerce & Economics', 'GCCE382', 'Wadala West, Mumbai, Maharashtra 400031', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(383, 'Shri Bhagubhai Mafatlal Polytechnic', 'SBMP383', 'Vile Parle East, Mumbai, Maharashtra 400057', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(384, 'Royal College of Arts & Commerce', 'RCAC384', 'Ambernath (Oops—outside Mumbai)', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 0),
(385, 'Saint Paul Junior College', 'SPJC385', 'Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(386, 'S. Yasawant Junior College', 'SYJC386', 'Borivali West, Mumbai, Maharashtra 400092', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(387, 'Shankarrao Chavan Government College', 'SCGC387', 'Thane West, Maharashtra 400601', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(388, 'Sant Gajanan Maharaj College of Engineering', 'SGMCE388', 'Mahim, Mumbai, Maharashtra 400016', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(389, 'SIES College of Arts, Science & Commerce (Junior)', 'SASC391', 'Sion West, Mumbai, Maharashtra 400022', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(390, 'Shobhaben Pratapbhai Patel School of Pharmacy', 'SPPSP390', 'Borivali West, Mumbai, Maharashtra 400092', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(391, 'Rustomjee College of Commerce & Arts', 'RCCA391', 'Kandivali West, Mumbai, Maharashtra 400067', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(392, 'R. D. National College', 'RDNC392', 'Bandstand, Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(393, 'R. D. National College Junior College', 'RDNCJC393', 'Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(394, 'R. A. Podar Junior College', 'RAPJC394', 'Matunga East, Mumbai, Maharashtra 400019', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(395, 'Ramniranjan Anandilal Podar College Junior College', 'RAPCJC395', 'Matunga East, Mumbai, Maharashtra 400019', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(396, 'Siddharth College Junior College', 'SCJC396', 'Fort, Mumbai, Maharashtra 400001', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(397, 'Shree L. S. Raheja College Junior College', 'SLRJC397', 'Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(398, 'L. S. Raheja Institute of Polytechnic', 'LSRIP398', 'Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(399, 'Santacruz College Junior College', 'SCJC399', 'Santacruz East, Mumbai, Maharashtra 400055', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(400, 'S K Somaiya Junior College', 'SKSJC400', 'Vidyavihar East, Mumbai, Maharashtra 400077', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(401, 'Usha Kiran B.Ed College', 'UKBC401', 'Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(402, 'Universal School of Sciences', 'USS402', 'Vasai West, Mumbai, Maharashtra 401202', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(403, 'Vaze Junior College', 'VJC403', 'Mulund West, Mumbai, Maharashtra 400080', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(404, 'Wellington College Junior', 'WCJ404', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(405, 'Wilson Junior College (Kurla)', 'WJC405', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(406, 'Yashwantrao Chavan College Junior', 'YCCJ406', 'Dombivli West, Maharashtra 421202', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 0),
(407, 'Zee TV Film Institute', 'ZTFI407', 'Andheri West, Mumbai, Maharashtra 400053', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(408, 'Victoria Jubilee Film & TV Institute', 'VJFTI408', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(409, 'Thadomal Shahani Junior College', 'TSJC409', 'Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(410, 'Rajiv Gandhi College Commerce', 'RGCC410', 'Nerul, Navi Mumbai, Maharashtra 400706', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(411, 'Rizvi College Junior College', 'RCJC411', 'Carter Road, Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(412, 'Institute of Banking & Finance', 'IBF412', 'Fort, Mumbai, Maharashtra 400001', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(413, 'Institute of Insurance & Risk Management', 'IIRM413', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(414, 'National Insurance Academy', 'NIA414', 'CST, Mumbai, Maharashtra 400023', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(415, 'The British College of Applied Studies', 'BCAS415', 'Goregaon East, Mumbai, Maharashtra 400063', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(416, 'School of Social Work', 'SSW416', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(417, 'Dr. Babasaheb Ambedkar Junior College of Arts', 'DBAJCAA417', 'Mankhurd, Mumbai, Maharashtra 400088', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(418, 'Dinshind Junior College', 'DJC418', 'Borivali East, Mumbai, Maharashtra 400066', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(419, 'Fr. Agnel Junior College', 'FAJC419', 'Vasai East, Mumbai, Maharashtra 401202', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(420, 'Hansraj Moraji Public School & Junior College', 'HMJC420', 'Juhu, Mumbai, Maharashtra 400049', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(421, 'Panvel Junior College', 'PJC421', 'Panvel, Navi Mumbai, Maharashtra 410206', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(422, 'Tilak College', 'TC422', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(423, 'Thakur Junior College', 'TJC423', 'Kandivali West, Mumbai, Maharashtra 400067', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(424, 'Sies Law College', 'SLC424', 'Sion West, Mumbai, Maharashtra 400022', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(425, 'Guru Nanak Khalsa Junior College', 'GNKJC425', 'Matunga East, Mumbai, Maharashtra 400019', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 1),
(426, 'IMT Ghaziabad Institute of Management', 'IMTGI426', 'Ghaziabad (Oops—outside Mumbai)', '2025-07-11 16:27:19', '2025-07-11 16:27:19', 0),
(427, 'KJ Somaiya Junior College of Science', 'KJSJCS427', 'Vidyavihar East, Mumbai, Maharashtra 400077', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(428, 'SIES Junior College of Arts & Commerce', 'SJAC428', 'Sion West, Mumbai, Maharashtra 400022', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(429, 'Thakur Vidya Mandir Junior College (Science)', 'TVMJCS429', 'Kandivali East, Mumbai, Maharashtra 400101', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(430, 'Bhavan\'s College of Science & Commerce', 'BCSC430', 'Munshi Nagar, Andheri West, Mumbai, Maharashtra 400058', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(431, 'Ryan International Junior College of Science', 'RIJCS431', 'Malad West, Mumbai, Maharashtra 400064', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(432, 'Fr. Agnel Junior College (Commerce)', 'FAJCC432', 'Vasai East, Mumbai, Maharashtra 401208', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(433, 'Universal Junior College (Arts)', 'UJCAA433', 'Vasai West, Mumbai, Maharashtra 401202', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(434, 'Xavier’s Junior Science College', 'XJSC434', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(435, 'Sophia Junior College of Commerce', 'SJCC435', 'Sion East, Mumbai, Maharashtra 400022', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(436, 'Pillai Junior College (Commerce)', 'PJC436', 'Panvel, Navi Mumbai, Maharashtra 410206', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(437, 'Natasha\'s Entertainment & Media Institute', 'NEMI437', 'Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(438, 'RV College of Education', 'RVCE438', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(439, 'Lokmanya Tilak Junior College of Science', 'LTJCS439', 'Grant Road, Mumbai, Maharashtra 400007', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(440, 'St. Joseph’s Junior College of Arts', 'SJJCA440', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(441, 'AISS Junior College', 'AISSJC441', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(442, 'IES Junior College of Commerce', 'IJCCom442', 'Nerul, Navi Mumbai, Maharashtra 400706', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(443, 'Carmel Junior College of Commerce', 'CJC443', 'Nuvem (Oops—outside Mumbai)', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 0),
(444, 'Dr. Ambedkar Junior College of Science', 'DAJCS444', 'Mankhurd, Mumbai, Maharashtra 400088', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(445, 'SM Shetty Junior College (Science)', 'SSJCS445', 'Santacruz East, Mumbai, Maharashtra 400055', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(446, 'Dav College of Arts & Commerce', 'DCAC446', 'Mandvi (Oops—outside Mumbai)', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 0),
(447, 'Wadia Junior College of Arts', 'WJCA447', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(448, 'St. Andrew’s Junior Commerce College', 'SAJCC448', 'Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(449, 'Xavier’s Junior Science & Commerce', 'XJSC449', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(450, 'Bhavans Junior College of Arts', 'BJCAA450', 'Andheri West, Mumbai, Maharashtra 400058', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(451, 'Indus Junior College', 'IJC451', 'Andheri West, Mumbai, Maharashtra 400058', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(452, 'Universal Film Institute', 'UFI452', 'Vasai West, Mumbai, Maharashtra 401202', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(453, 'Sophia Film & Media Institute', 'SFMI453', 'Sion East, Mumbai, Maharashtra 400022', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(454, 'Vidyalankar Junior College of Commerce', 'VJCC454', 'Wadala East, Mumbai, Maharashtra 400037', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(455, 'MET Junior College (Commerce)', 'METJC455', 'Chembur, Mumbai, Maharashtra 400071', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(456, 'KJ Somaiya College of Education & Research', 'KJSER456', 'Vidyavihar East, Mumbai, Maharashtra 400077', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(457, 'WeSchool Junior College', 'WJSC457', 'Lower Parel, Mumbai, Maharashtra 400013', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(458, 'Ryan Junior College (Science & Commerce)', 'RJC458', 'Malad West, Mumbai, Maharashtra 400064', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(459, 'Balaji Film Academy', 'BFA459', 'Kurla East, Mumbai, Maharashtra 400024', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(460, 'Victoria Junior College', 'VJC460', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(461, 'Zee Junior College of Arts', 'ZJCA461', 'Andheri West, Mumbai, Maharashtra 400053', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(462, 'Thadomal Shahani Junior Science College', 'TSJSC462', 'Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(463, 'Pillai Junior College of Science', 'PJCS463', 'Panvel, Navi Mumbai, Maharashtra 410206', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(464, 'Ismail Yusuf Junior College', 'IYJC464', 'Jogeshwari East, Mumbai, Maharashtra 400060', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(465, 'Kirti M. Doongursee Junior College (Science)', 'KDSJCS465', 'Dadar West, Mumbai, Maharashtra 400028', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(466, 'SIES Junior College of Pharmacy', 'SJPharm466', 'Sion West, Mumbai, Maharashtra 400022', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(467, 'Sophia Junior College of Science', 'SJC467', 'Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(468, 'St. Theresa’s Junior College of Arts', 'STJCA468', 'Santacruz East, Mumbai, Maharashtra 400055', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(469, 'Vaze Junior College of Science', 'VJCS469', 'Mulund West, Mumbai, Maharashtra 400080', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(470, 'Wilson Junior College of Commerce', 'WJCC470', 'Girgaon, Marine Lines, Mumbai, Maharashtra 400020', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(471, 'YMCA Junior College', 'YMCAJC471', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(472, 'Yashwantrao Chavan Junior College of Science', 'YCJCS472', 'Dombivli West, Maharashtra 421202', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 0),
(473, 'Anjuman-I-Islam\'s Junior College of Commerce', 'AIJCC473', 'Panvel, Navi Mumbai, Maharashtra 410206', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(474, 'Ryan Junior College of Vocational Studies', 'RJCVS474', 'Malad West, Mumbai, Maharashtra 400064', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(475, 'Sophia Junior Commerce & Science', 'SJCS475', 'Sion East, Mumbai, Maharashtra 400022', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(476, 'Thakur Junior Commerce College', 'TJC476', 'Kandivali West, Mumbai, Maharashtra 400067', '2025-07-11 16:28:04', '2025-07-11 16:28:04', 1),
(514, 'Bhavans College of Education', 'BCE477', 'Andheri West, Mumbai, Maharashtra 400058', '2025-07-11 16:34:13', '2025-07-11 16:34:13', 1),
(515, 'Bhavans Junior College of Science', 'BJCS478', 'Andheri West, Mumbai, Maharashtra 400058', '2025-07-11 16:34:13', '2025-07-11 16:34:13', 1),
(516, 'H.R. College of Commerce & Economics Junior College', 'HRCCJ479', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:34:13', '2025-07-11 16:34:13', 1),
(517, 'Ismail Yusuf Junior College of Commerce', 'IYJC480', 'Jogeshwari East, Mumbai, Maharashtra 400060', '2025-07-11 16:34:13', '2025-07-11 16:34:13', 1),
(518, 'Kishinchand Chellaram Junior College of Science', 'KCCJCS481', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:34:13', '2025-07-11 16:34:13', 1),
(519, 'K.P.T.\'s Senior College of Commerce & Science', 'KPTCSC482', 'Dahisar East, Mumbai, Maharashtra 400068', '2025-07-11 16:34:13', '2025-07-11 16:34:13', 1),
(520, 'Mithibai College – Multimedia Studies', 'MCM483', 'Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 16:34:13', '2025-07-11 16:34:13', 1),
(521, 'Mumbai College of Commerce & Economics', 'MCCE484', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:34:13', '2025-07-11 16:34:13', 1),
(522, 'Ryan Junior College of Arts', 'RJCA485', 'Malad West, Mumbai, Maharashtra 400064', '2025-07-11 16:34:13', '2025-07-11 16:34:13', 1),
(523, 'Ryan Junior College of Science', 'RJCSo486', 'Malad West, Mumbai, Maharashtra 400064', '2025-07-11 16:34:13', '2025-07-11 16:34:13', 1),
(524, 'St. Theresa’s Junior Science College', 'STJSC487', 'Santacruz East, Mumbai, Maharashtra 400055', '2025-07-11 16:35:33', '2025-07-11 16:35:33', 1),
(525, 'Sophia Junior College of Vocational Studies', 'SJCVS488', 'Bandra West, Mumbai, Maharashtra 400050', '2025-07-11 16:35:33', '2025-07-11 16:35:33', 1),
(526, 'SIES Junior College of Science', 'SJCSc489', 'Sion West, Mumbai, Maharashtra 400022', '2025-07-11 16:35:33', '2025-07-11 16:35:33', 1),
(527, 'The Bombay Teacher’s Training College', 'BTTC490', 'Girgaon, Marine Lines, Mumbai, Maharashtra 400020', '2025-07-11 16:35:33', '2025-07-11 16:35:33', 1),
(528, 'Tilak College of Arts', 'TCA491', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 16:35:33', '2025-07-11 16:35:33', 1),
(529, 'Universal Junior College of Commerce', 'UJCC492', 'Vasai West, Mumbai, Maharashtra 401202', '2025-07-11 16:35:33', '2025-07-11 16:35:33', 1),
(530, 'Wilson Junior College (Commerce)', 'WJCC493', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 16:35:33', '2025-07-11 16:35:33', 1),
(531, 'Xavier’s Junior College of Arts', 'XJCoA494', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:35:33', '2025-07-11 16:35:33', 1),
(532, 'YMCA Junior College of Commerce & Economics', 'YMCAJCCE495', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:35:33', '2025-07-11 16:35:33', 1),
(533, 'Zee Junior College of Commerce', 'ZJCC496', 'Andheri West, Mumbai, Maharashtra 400053', '2025-07-11 16:35:33', '2025-07-11 16:35:33', 1),
(534, 'Amity Global School & Junior College', 'AGSJC497', 'Bandra Kurla Complex, Mumbai, Maharashtra 400051', '2025-07-11 16:35:33', '2025-07-11 16:35:33', 1),
(535, 'Balaji Junior College', 'BJC498', 'Kurla East, Mumbai, Maharashtra 400024', '2025-07-11 16:35:33', '2025-07-11 16:35:33', 1),
(536, 'Carmel Junior College of Commerce & Science', 'CJCOS500', 'Nuvem (outside Mumbai)', '2025-07-11 16:35:33', '2025-07-11 16:35:33', 0),
(537, 'D.Y. Patil Junior College of Science', 'DYPCS501', 'Nerul, Navi Mumbai, Maharashtra 400706', '2025-07-11 16:35:33', '2025-07-11 16:35:33', 1),
(538, 'Dr. Vithalrao Vikhe Patil Junior College', 'DVVPJC502', 'Sion East, Mumbai, Maharashtra 400022', '2025-07-11 16:35:33', '2025-07-11 16:35:33', 1),
(539, 'Fr. Agnel Junior College of Commerce', 'FAJCoC503', 'Vasai East, Mumbai, Maharashtra 401208', '2025-07-11 16:35:33', '2025-07-11 16:35:33', 1),
(540, 'Garodia Education Society’s Junior College', 'GESJC504', 'Ghatkopar East, Mumbai, Maharashtra 400077', '2025-07-11 16:35:33', '2025-07-11 16:35:33', 1),
(541, 'Hansraj Moraji Junior College of Commerce', 'HMJCC505', 'Juhu, Mumbai, Maharashtra 400049', '2025-07-11 16:35:33', '2025-07-11 16:35:33', 1),
(542, 'Hiranandani Foundation Junior College', 'HFJC506', 'Powai, Mumbai, Maharashtra 400076', '2025-07-11 16:35:33', '2025-07-11 16:35:33', 1),
(543, 'Indira Junior College', 'IJC507', 'Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 16:35:33', '2025-07-11 16:35:33', 1),
(544, 'Ismail Yusuf Junior College of Science', 'IYJCS508', 'Jogeshwari East, Mumbai, Maharashtra 400060', '2025-07-11 16:35:33', '2025-07-11 16:35:33', 1),
(545, 'Jawaharlal Nehru Junior College', 'JNJC509', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 16:35:33', '2025-07-11 16:35:33', 1),
(546, 'Kalina Junior Commerce College', 'KJCC510', 'Santacruz East, Mumbai, Maharashtra 400055', '2025-07-11 16:35:33', '2025-07-11 16:35:33', 1),
(547, 'Lokmanya Tilak Junior Commerce College', 'LTJCC511', 'Grant Road, Mumbai, Maharashtra 400007', '2025-07-11 16:35:33', '2025-07-11 16:35:33', 1),
(548, 'Mithibai Junior College of Science & Commerce', 'MJCScC512', 'Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 16:35:33', '2025-07-11 16:35:33', 1),
(549, 'Mumbai College Junior College', 'MCJC513', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:35:33', '2025-07-11 16:35:33', 1),
(553, 'Bhavan’s Junior College of Commerce', 'BJCC527', 'Munshi Nagar, Andheri West, Mumbai, Maharashtra 400058', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(554, 'Fr. Agnel Junior College of Science', 'FAJCS528', 'Vasai East, Mumbai, Maharashtra 401208', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(555, 'Garodia International Centre for Learning', 'GICL529', 'Ghatkopar East, Mumbai, Maharashtra 400077', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(556, 'Hansraj Moraji Junior College of Arts & Commerce', 'HMJCA530', 'Juhu, Mumbai, Maharashtra 400049', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(557, 'Hiranandani Foundation Junior College of Commerce', 'HFJCC531', 'Powai, Mumbai, Maharashtra 400076', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(558, 'Indira Junior College of Science', 'IJCSc532', 'Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(559, 'Jawaharlal Nehru Junior College of Commerce', 'JNJCC533', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(560, 'Kalina Junior College of Science', 'KJCS534', 'Santacruz East, Mumbai, Maharashtra 400055', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(561, 'Lokmanya Tilak Junior College of Arts', 'LTJCA535', 'Grant Road, Mumbai, Maharashtra 400007', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(562, 'Mithibai Junior College of Arts', 'MJCAs536', 'Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(563, 'Mumbai College Junior Junior College', 'MCJJC537', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(564, 'Ryan Junior College of Commerce & Science', 'RJCCS538', 'Malad West, Mumbai, Maharashtra 400064', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(565, 'St. Theresa’s Junior Commerce College', 'STJCC539', 'Santacruz East, Mumbai, Maharashtra 400055', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(566, 'Sophia Junior College of Arts', 'SJCoA540', 'Sion East, Mumbai, Maharashtra 400022', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(567, 'Tilak Junior College of Commerce', 'TJCC541', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(568, 'Universal Junior College of Arts', 'UJCA542', 'Vasai West, Mumbai, Maharashtra 401202', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(569, 'Victoria Junior College of Science', 'VJCS543', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(570, 'Wilson Junior College of Science', 'WJCS544', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(571, 'YMCA Junior College of Arts', 'YMCAJCA545', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(572, 'Zee Junior College of Arts & Commerce', 'ZJAC546', 'Andheri West, Mumbai, Maharashtra 400053', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(573, 'Amity Junior College of Commerce', 'AJCC547', 'Bandra Kurla Complex, Mumbai, Maharashtra 400051', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(574, 'Balaji Junior College of Arts', 'BJCA548', 'Kurla East, Mumbai, Maharashtra 400024', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(575, 'Bhavan’s Junior College of Science & Commerce', 'BJCSC549', 'Andheri West, Mumbai, Maharashtra 400058', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(576, 'Bhavans Centre for Communication Arts', 'BCCA550', 'Andheri West, Mumbai, Maharashtra 400058', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(577, 'Diocesan Junior College', 'DJC551', 'Marine Lines, Mumbai, Maharashtra 400020', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(578, 'Fr. Agnel Junior College of Vocational Studies', 'FAJCVS552', 'Vasai East, Mumbai, Maharashtra 401208', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(579, 'Garodia International Junior College', 'GIJC553', 'Ghatkopar East, Mumbai, Maharashtra 400077', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(580, 'Hansraj Moraji Junior College of Science', 'HMJCS554', 'Juhu, Mumbai, Maharashtra 400049', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(581, 'Hiranandani Foundation Junior College of Science', 'HFJCS555', 'Powai, Mumbai, Maharashtra 400076', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(582, 'Indira Junior College of Arts', 'IJCAr556', 'Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(583, 'Jawaharlal Nehru Junior College of Science', 'JNJCS557', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(584, 'Kalina Junior College of Arts', 'KJCA558', 'Santacruz East, Mumbai, Maharashtra 400055', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(585, 'Lokmanya Tilak Junior College of Vocational Studies', 'LTJCVS559', 'Grant Road, Mumbai, Maharashtra 400007', '2025-07-11 16:39:04', '2025-07-11 16:39:04', 1),
(586, 'Mithibai Junior College of Vocational Studies', 'MJCVS560', 'Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 16:40:16', '2025-07-11 16:40:16', 1),
(587, 'Mumbai College Junior Junior Junior College', 'MCJJJC561', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:40:16', '2025-07-11 16:40:16', 1),
(588, 'Ryan Junior College of Arts & Commerce', 'RJCAC562', 'Malad West, Mumbai, Maharashtra 400064', '2025-07-11 16:40:16', '2025-07-11 16:40:16', 1),
(589, 'St. Theresa’s Junior Arts & Commerce College', 'STJAC563', 'Santacruz East, Mumbai, Maharashtra 400055', '2025-07-11 16:40:16', '2025-07-11 16:40:16', 1),
(590, 'Sophia Junior Science & Commerce College', 'SJSCc564', 'Sion East, Mumbai, Maharashtra 400022', '2025-07-11 16:40:16', '2025-07-11 16:40:16', 1),
(591, 'Tilak Junior College of Vocational Studies', 'TJCVS565', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 16:40:16', '2025-07-11 16:40:16', 1),
(592, 'Universal Junior College of Commerce & Science', 'UJCCS566', 'Vasai West, Mumbai, Maharashtra 401202', '2025-07-11 16:40:16', '2025-07-11 16:40:16', 1),
(593, 'Victoria Junior College of Arts & Commerce', 'VJCAc567', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 16:40:16', '2025-07-11 16:40:16', 1),
(594, 'Wilson Junior College of Commerce & Science', 'WJCCS568', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 16:40:16', '2025-07-11 16:40:16', 1),
(595, 'YMCA Junior College of Commerce', 'YMCAJC569', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:40:16', '2025-07-11 16:40:16', 1),
(596, 'Zee Junior College of Vocational Studies', 'ZJCVS570', 'Andheri West, Mumbai, Maharashtra 400053', '2025-07-11 16:40:16', '2025-07-11 16:40:16', 1),
(597, 'Amity Junior College of Science', 'AJCS571', 'Bandra Kurla Complex, Mumbai, Maharashtra 400051', '2025-07-11 16:40:16', '2025-07-11 16:40:16', 1),
(598, 'Balaji Junior College of Vocational Studies', 'BJCVS572', 'Kurla East, Mumbai, Maharashtra 400024', '2025-07-11 16:40:16', '2025-07-11 16:40:16', 1),
(599, 'Bhavan’s Junior College of Vocational Studies', 'BJCVS573', 'Andheri West, Mumbai, Maharashtra 400058', '2025-07-11 16:40:16', '2025-07-11 16:40:16', 1),
(600, 'Bhavans Centre for Media Arts', 'BCMA574', 'Andheri West, Mumbai, Maharashtra 400058', '2025-07-11 16:40:16', '2025-07-11 16:40:16', 1),
(601, 'Diocesan Junior College of Commerce', 'DJCC575', 'Marine Lines, Mumbai, Maharashtra 400020', '2025-07-11 16:40:16', '2025-07-11 16:40:16', 1),
(602, 'Fr. Agnel Junior College of Sport Studies', 'FAJCSS576', 'Vasai East, Mumbai, Maharashtra 401208', '2025-07-11 16:40:16', '2025-07-11 16:40:16', 1),
(603, 'KJ Somaiya Junior College of Commerce', 'KJSJC577', 'Vidyavihar East, Mumbai, Maharashtra 400077', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(604, 'SIES Junior College of Management Studies', 'SJCMS578', 'Sion West, Mumbai, Maharashtra 400022', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(605, 'Thakur Junior College of Commerce & Science', 'TJCACS579', 'Kandivali West, Mumbai, Maharashtra 400067', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(606, 'Bhavan’s Junior College of Film Studies', 'BJCFS580', 'Andheri West, Mumbai, Maharashtra 400058', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(607, 'Ryan Junior College of Vocational Technology', 'RJCVT581', 'Malad West, Mumbai, Maharashtra 400064', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(608, 'Universal Junior College of Hotel Management', 'UJCHM582', 'Vasai West, Mumbai, Maharashtra 401202', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(609, 'Victoria Junior College of Technical Education', 'VJCTE583', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(610, 'Wilson Junior College of Management', 'WJCM584', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(611, 'YMCA Junior College of Hotel Management', 'YMCAJCHM585', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(612, 'Zee Junior College of Media Studies', 'ZJCMS586', 'Andheri West, Mumbai, Maharashtra 400053', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(613, 'Amity Junior College of Management', 'AJCM587', 'Bandra Kurla Complex, Mumbai, Maharashtra 400051', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(614, 'Balaji Junior College of Arts & Management', 'BJAM588', 'Kurla East, Mumbai, Maharashtra 400024', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(615, 'Bhavan’s Centre for Hospitality Studies', 'BCHS589', 'Andheri West, Mumbai, Maharashtra 400058', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(616, 'Diocesan Junior College of Hospitality Management', 'DJCHM590', 'Marine Lines, Mumbai, Maharashtra 400020', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(617, 'Fr. Agnel Junior College of Commerce & Technology', 'FAJCCT591', 'Vasai East, Mumbai, Maharashtra 401208', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(618, 'Garodia International Junior College of Technology', 'GIJCT592', 'Ghatkopar East, Mumbai, Maharashtra 400077', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(619, 'Hansraj Moraji Junior College of Technical Education', 'HMJCTE593', 'Juhu, Mumbai, Maharashtra 400049', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(620, 'Hiranandani Foundation Junior College of Technology', 'HFJCT594', 'Powai, Mumbai, Maharashtra 400076', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(621, 'Indira Junior College of Commerce & Technology', 'IJCCt595', 'Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(622, 'Jawaharlal Nehru Junior College of Media Studies', 'JNJCMS596', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(623, 'Kalina Junior College of Technical Education', 'KJCTE597', 'Santacruz East, Mumbai, Maharashtra 400055', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(624, 'Lokmanya Tilak Junior College of Media Arts', 'LTJCMA598', 'Grant Road, Mumbai, Maharashtra 400007', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(625, 'Mithibai Junior College of Multimedia Studies', 'MJCM599', 'Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(626, 'Mumbai College Junior College of Media', 'MCJCM600', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(627, 'Ryan Junior College of Media & Communication', 'RJCMC601', 'Malad West, Mumbai, Maharashtra 400064', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(628, 'St. Theresa’s Junior College of Technical Studies', 'STJCTS602', 'Santacruz East, Mumbai, Maharashtra 400055', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(629, 'Sophia Junior College of Hotel Management', 'SJCHM603', 'Sion East, Mumbai, Maharashtra 400022', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(630, 'Tilak Junior College of Media Studies', 'TJCMS604', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(631, 'Universal Junior College of Media Arts', 'UJCMA605', 'Vasai West, Mumbai, Maharashtra 401202', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(632, 'Victoria Junior College of Media & Technology', 'VJCMT606', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(633, 'Wilson Junior College of Media Studies', 'WJCMT607', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(634, 'YMCA Junior College of Media Arts', 'YMCAJCMA608', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(635, 'Zee Junior College of Technical Studies', 'ZJCTS609', 'Andheri West, Mumbai, Maharashtra 400053', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(636, 'Amity Junior College of Technical Education', 'AJCTE610', 'Bandra Kurla Complex, Mumbai, Maharashtra 400051', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(637, 'Balaji Junior College of Hospitality Studies', 'BJCHS611', 'Kurla East, Mumbai, Maharashtra 400024', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(638, 'Bhavan’s Junior College of Hospitality Management', 'BJCHM612', 'Andheri West, Mumbai, Maharashtra 400058', '2025-07-11 16:44:46', '2025-07-11 16:44:46', 1),
(639, 'Diocesan Junior College of Technical Education', 'DJCTE613', 'Marine Lines, Mumbai, Maharashtra 400020', '2025-07-11 16:45:46', '2025-07-11 16:45:46', 1),
(640, 'Fr. Agnel Junior College of Culinary Arts', 'FAJCCA614', 'Vasai East, Mumbai, Maharashtra 401208', '2025-07-11 16:45:46', '2025-07-11 16:45:46', 1),
(641, 'Garodia International Junior College of Hospitality', 'GIJCH615', 'Ghatkopar East, Mumbai, Maharashtra 400077', '2025-07-11 16:45:46', '2025-07-11 16:45:46', 1),
(642, 'Hansraj Moraji Junior College of Culinary Arts', 'HMJCCA616', 'Juhu, Mumbai, Maharashtra 400049', '2025-07-11 16:45:46', '2025-07-11 16:45:46', 1),
(643, 'Hiranandani Foundation Junior College of Culinary Studies', 'HFJCCS617', 'Powai, Mumbai, Maharashtra 400076', '2025-07-11 16:45:46', '2025-07-11 16:45:46', 1),
(644, 'Indira Junior College of Hospitality Management', 'IJCHM618', 'Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 16:45:46', '2025-07-11 16:45:46', 1),
(645, 'Jawaharlal Nehru Junior College of Culinary Arts', 'JNJCCA619', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 16:45:46', '2025-07-11 16:45:46', 1),
(646, 'Kalina Junior College of Culinary Arts', 'KJCCA620', 'Santacruz East, Mumbai, Maharashtra 400055', '2025-07-11 16:45:46', '2025-07-11 16:45:46', 1),
(647, 'Lokmanya Tilak Junior College of Hospitality', 'LTJCH621', 'Grant Road, Mumbai, Maharashtra 400007', '2025-07-11 16:45:46', '2025-07-11 16:45:46', 1),
(648, 'Mithibai Junior College of Culinary Arts', 'MJCCA622', 'Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 16:45:46', '2025-07-11 16:45:46', 1),
(649, 'Mumbai College Junior College of Hospitality', 'MCJCH623', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:45:46', '2025-07-11 16:45:46', 1),
(650, 'Ryan Junior College of Culinary Arts & Science', 'RJCAS624', 'Malad West, Mumbai, Maharashtra 400064', '2025-07-11 16:45:46', '2025-07-11 16:45:46', 1),
(651, 'St. Theresa’s Junior College of Culinary Science', 'STJCCS625', 'Santacruz East, Mumbai, Maharashtra 400055', '2025-07-11 16:45:46', '2025-07-11 16:45:46', 1),
(652, 'Sophia Junior College of Culinary Arts', 'SJCCA626', 'Sion East, Mumbai, Maharashtra 400022', '2025-07-11 16:45:46', '2025-07-11 16:45:46', 1),
(653, 'Tilak Junior College of Culinary Arts', 'TJCCA627', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 16:47:22', '2025-07-11 16:47:22', 1),
(654, 'Universal Junior College of Culinary Studies', 'UJCCS628', 'Vasai West, Mumbai, Maharashtra 401202', '2025-07-11 16:47:22', '2025-07-11 16:47:22', 1),
(655, 'Victoria Junior College of Culinary Science', 'VJCCS629', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 16:47:22', '2025-07-11 16:47:22', 1),
(656, 'Wilson Junior College of Hospitality Studies', 'WJCHS630', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 16:47:22', '2025-07-11 16:47:22', 1),
(657, 'YMCA Junior College of Culinary Science', 'YMCAJCS631', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:47:22', '2025-07-11 16:47:22', 1),
(658, 'Zee Junior College of Culinary Arts', 'ZJCCA632', 'Andheri West, Mumbai, Maharashtra 400053', '2025-07-11 16:47:22', '2025-07-11 16:47:22', 1),
(659, 'Amity Junior College of Hospitality Studies', 'AJCHS633', 'Bandra Kurla Complex, Mumbai, Maharashtra 400051', '2025-07-11 16:47:22', '2025-07-11 16:47:22', 1),
(660, 'Balaji Junior College of Media & Arts', 'BJCMA634', 'Kurla East, Mumbai, Maharashtra 400024', '2025-07-11 16:47:22', '2025-07-11 16:47:22', 1),
(661, 'Bhavan’s Junior College of Event Management', 'BJCEM635', 'Andheri West, Mumbai, Maharashtra 400058', '2025-07-11 16:47:23', '2025-07-11 16:47:23', 1),
(662, 'Diocesan Junior College of Event Planning', 'DJCEP636', 'Marine Lines, Mumbai, Maharashtra 400020', '2025-07-11 16:47:23', '2025-07-11 16:47:23', 1),
(663, 'Fr. Agnel Junior College of Performing Arts', 'FAJCPA637', 'Vasai East, Mumbai, Maharashtra 401208', '2025-07-11 16:47:23', '2025-07-11 16:47:23', 1),
(664, 'Garodia Junior College of Film & Communication', 'GJCFC638', 'Ghatkopar East, Mumbai, Maharashtra 400077', '2025-07-11 16:47:23', '2025-07-11 16:47:23', 1),
(665, 'Hansraj Moraji Junior College of Journalism', 'HMJCJ639', 'Juhu, Mumbai, Maharashtra 400049', '2025-07-11 16:47:23', '2025-07-11 16:47:23', 1),
(666, 'Hiranandani Foundation Junior College of Visual Arts', 'HFJCVA640', 'Powai, Mumbai, Maharashtra 400076', '2025-07-11 16:47:23', '2025-07-11 16:47:23', 1),
(667, 'Indira Junior College of Photography', 'IJCP641', 'Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 16:47:23', '2025-07-11 16:47:23', 1),
(668, 'Jawaharlal Nehru Junior College of Visual Arts', 'JNJCVA642', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 16:47:23', '2025-07-11 16:47:23', 1),
(669, 'Kalina Junior College of Event Management', 'KJCEM643', 'Santacruz East, Mumbai, Maharashtra 400055', '2025-07-11 16:47:23', '2025-07-11 16:47:23', 1),
(670, 'Lokmanya Tilak Junior College of Performing Arts', 'LTJCPA644', 'Grant Road, Mumbai, Maharashtra 400007', '2025-07-11 16:47:23', '2025-07-11 16:47:23', 1),
(671, 'Mithibai Junior College of Animation & Design', 'MJCAD645', 'Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 16:47:23', '2025-07-11 16:47:23', 1),
(672, 'Mumbai College Junior College of Design', 'MCJCD646', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:47:23', '2025-07-11 16:47:23', 1),
(673, 'Ryan Junior College of Photography & Media', 'RJCPM647', 'Malad West, Mumbai, Maharashtra 400064', '2025-07-11 16:47:23', '2025-07-11 16:47:23', 1),
(674, 'St. Theresa’s Junior College of Performing Arts', 'STJCPA648', 'Santacruz East, Mumbai, Maharashtra 400055', '2025-07-11 16:47:23', '2025-07-11 16:47:23', 1),
(675, 'Sophia Junior College of Communication Design', 'SJCCD649', 'Sion East, Mumbai, Maharashtra 400022', '2025-07-11 16:47:23', '2025-07-11 16:47:23', 1),
(676, 'Tilak Junior College of Event Planning', 'TJCEP650', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 16:47:23', '2025-07-11 16:47:23', 1),
(677, 'Universal Junior College of Animation', 'UJCA651', 'Vasai West, Mumbai, Maharashtra 401202', '2025-07-11 16:49:25', '2025-07-11 16:49:25', 1),
(678, 'Victoria Junior College of Performing Arts', 'VJCPA652', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 16:49:25', '2025-07-11 16:49:25', 1),
(679, 'Wilson Junior College of Visual Communication', 'WJCVC653', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 16:49:25', '2025-07-11 16:49:25', 1),
(680, 'YMCA Junior College of Animation & Design', 'YMCAJCAD654', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:49:25', '2025-07-11 16:49:25', 1),
(681, 'Zee Junior College of Event Management', 'ZJCEM655', 'Andheri West, Mumbai, Maharashtra 400053', '2025-07-11 16:49:25', '2025-07-11 16:49:25', 1),
(682, 'Amity Junior College of Visual Arts', 'AJCVA656', 'Bandra Kurla Complex, Mumbai, Maharashtra 400051', '2025-07-11 16:49:25', '2025-07-11 16:49:25', 1),
(683, 'Balaji Junior College of Media & Journalism', 'BJCMJ657', 'Kurla East, Mumbai, Maharashtra 400024', '2025-07-11 16:49:25', '2025-07-11 16:49:25', 1),
(684, 'Bhavan’s Junior College of Digital Arts', 'BJCDA658', 'Andheri West, Mumbai, Maharashtra 400058', '2025-07-11 16:49:25', '2025-07-11 16:49:25', 1),
(685, 'Diocesan Junior College of Performing Arts', 'DJCPA659', 'Marine Lines, Mumbai, Maharashtra 400020', '2025-07-11 16:49:25', '2025-07-11 16:49:25', 1),
(686, 'Fr. Agnel Junior College of Animation', 'FAJCA660', 'Vasai East, Mumbai, Maharashtra 401208', '2025-07-11 16:49:25', '2025-07-11 16:49:25', 1),
(687, 'Garodia Junior College of Performing Arts', 'GJCPA661', 'Ghatkopar East, Mumbai, Maharashtra 400077', '2025-07-11 16:49:25', '2025-07-11 16:49:25', 1),
(688, 'Hansraj Moraji Junior College of Digital Media', 'HMJCDM662', 'Juhu, Mumbai, Maharashtra 400049', '2025-07-11 16:49:25', '2025-07-11 16:49:25', 1),
(689, 'Hiranandani Foundation Junior College of Film & Media', 'HFJCFM663', 'Powai, Mumbai, Maharashtra 400076', '2025-07-11 16:49:25', '2025-07-11 16:49:25', 1),
(690, 'Indira Junior College of Event Media', 'IJCEM664', 'Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 16:49:25', '2025-07-11 16:49:25', 1),
(691, 'Jawaharlal Nehru Junior College of Performing Arts', 'JNJCPA665', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 16:49:25', '2025-07-11 16:49:25', 1),
(692, 'Kalina Junior College of Digital Design', 'KJCDD666', 'Santacruz East, Mumbai, Maharashtra 400055', '2025-07-11 16:49:25', '2025-07-11 16:49:25', 1),
(693, 'Lokmanya Tilak Junior College of Creative Arts', 'LTJCCA667', 'Grant Road, Mumbai, Maharashtra 400007', '2025-07-11 16:49:25', '2025-07-11 16:49:25', 1),
(694, 'Mithibai Junior College of Photography', 'MJCP668', 'Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 16:49:25', '2025-07-11 16:49:25', 1),
(695, 'Mumbai College Junior College of Performing Arts', 'MCJCPA669', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 16:49:25', '2025-07-11 16:49:25', 1),
(696, 'Ryan Junior College of Journalism', 'RJCJ670', 'Malad West, Mumbai, Maharashtra 400064', '2025-07-11 16:49:25', '2025-07-11 16:49:25', 1),
(697, 'St. Theresa’s Junior College of Digital Communication', 'STJCDC671', 'Santacruz East, Mumbai, Maharashtra 400055', '2025-07-11 16:49:25', '2025-07-11 16:49:25', 1),
(698, 'Sophia Junior College of Creative Media', 'SJCCM672', 'Sion East, Mumbai, Maharashtra 400022', '2025-07-11 16:49:25', '2025-07-11 16:49:25', 1),
(699, 'Tilak Junior College of Communication Studies', 'TJCCS673', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 16:49:25', '2025-07-11 16:49:25', 1),
(700, 'Universal Junior College of Visual Storytelling', 'UJCVS674', 'Vasai West, Mumbai, Maharashtra 401202', '2025-07-11 16:49:25', '2025-07-11 16:49:25', 1),
(701, 'Victoria Junior College of Film & Media', 'VJCFM675', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 16:49:25', '2025-07-11 16:49:25', 1),
(702, 'Wilson Junior College of Creative Arts', 'WJCCA676', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 16:49:25', '2025-07-11 16:49:25', 1),
(703, 'YMCA Junior College of Creative Media', 'YMCAJCM677', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 17:19:25', '2025-07-11 17:19:25', 1),
(704, 'Zee Junior College of Visual Communication', 'ZJCVc678', 'Andheri West, Mumbai, Maharashtra 400053', '2025-07-11 17:19:25', '2025-07-11 17:19:25', 1),
(705, 'Amity Junior College of Digital Arts', 'AJCDA679', 'Bandra Kurla Complex, Mumbai, Maharashtra 400051', '2025-07-11 17:19:25', '2025-07-11 17:19:25', 1),
(706, 'Balaji Junior College of Photography', 'BJCP680', 'Kurla East, Mumbai, Maharashtra 400024', '2025-07-11 17:19:25', '2025-07-11 17:19:25', 1),
(707, 'Bhavan’s Junior College of Screen Arts', 'BJCSA681', 'Andheri West, Mumbai, Maharashtra 400058', '2025-07-11 17:19:25', '2025-07-11 17:19:25', 1),
(708, 'Diocesan Junior College of Screen Media', 'DJCsm682', 'Marine Lines, Mumbai, Maharashtra 400020', '2025-07-11 17:19:25', '2025-07-11 17:19:25', 1),
(709, 'Fr. Agnel Junior College of Visual Studies', 'FAJCVS683', 'Vasai East, Mumbai, Maharashtra 401208', '2025-07-11 17:19:25', '2025-07-11 17:19:25', 1),
(710, 'Garodia Junior College of Digital Design', 'GJCDD684', 'Ghatkopar East, Mumbai, Maharashtra 400077', '2025-07-11 17:19:25', '2025-07-11 17:19:25', 1),
(711, 'Hansraj Moraji Junior College of Screen Media', 'HMJCSM685', 'Juhu, Mumbai, Maharashtra 400049', '2025-07-11 17:19:25', '2025-07-11 17:19:25', 1),
(712, 'Hiranandani Junior College of Digital Storytelling', 'HJCDS686', 'Powai, Mumbai, Maharashtra 400076', '2025-07-11 17:19:25', '2025-07-11 17:19:25', 1),
(713, 'Indira Junior College of Screen Arts', 'IJCsa687', 'Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 17:19:25', '2025-07-11 17:19:25', 1),
(714, 'Jawaharlal Nehru Junior College of Digital Media', 'JNJCDM688', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 17:19:25', '2025-07-11 17:19:25', 1),
(715, 'Kalina Junior College of Photography & Media', 'KJCp&media689', 'Santacruz East, Mumbai, Maharashtra 400055', '2025-07-11 17:19:25', '2025-07-11 17:19:25', 1),
(716, 'Lokmanya Tilak Junior College of Digital Storytelling', 'LTJCDS690', 'Grant Road, Mumbai, Maharashtra 400007', '2025-07-11 17:19:25', '2025-07-11 17:19:25', 1),
(717, 'Mithibai Junior College of Screen Arts', 'MJCSA691', 'Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 17:19:25', '2025-07-11 17:19:25', 1),
(718, 'Mumbai College Junior Junior College of Digital Arts', 'MCJCDa692', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 17:19:25', '2025-07-11 17:19:25', 1),
(719, 'Ryan Junior College of Screen Media', 'RJCsm693', 'Malad West, Mumbai, Maharashtra 400064', '2025-07-11 17:19:25', '2025-07-11 17:19:25', 1),
(720, 'St. Theresa’s Junior College of Screen Arts', 'STJCsa694', 'Santacruz East, Mumbai, Maharashtra 400055', '2025-07-11 17:19:25', '2025-07-11 17:19:25', 1),
(721, 'Sophia Junior College of Digital Journalism', 'SJCDJ695', 'Sion East, Mumbai, Maharashtra 400022', '2025-07-11 17:19:25', '2025-07-11 17:19:25', 1),
(722, 'Tilak Junior College of Screen Media', 'TJCSM696', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 17:25:44', '2025-07-11 17:25:44', 1),
(723, 'Universal Junior College of Photography & Visual Arts', 'UJCPVA697', 'Vasai West, Mumbai, Maharashtra 401202', '2025-07-11 17:25:44', '2025-07-11 17:25:44', 1),
(724, 'Victoria Junior College of Visual Journalism', 'VJCVJ698', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 17:25:44', '2025-07-11 17:25:44', 1),
(725, 'Wilson Junior College of Photography & Design', 'WJCPD699', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 17:25:44', '2025-07-11 17:25:44', 1),
(726, 'YMCA Junior College of Visual Media', 'YMCAJCVM700', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 17:25:44', '2025-07-11 17:25:44', 1);
INSERT INTO `colleges` (`college_id`, `college_name`, `college_code`, `address`, `created_at`, `updated_at`, `status`) VALUES
(727, 'Zee Junior College of Photography & Film', 'ZJCPF701', 'Andheri West, Mumbai, Maharashtra 400053', '2025-07-11 17:25:44', '2025-07-11 17:25:44', 1),
(728, 'Amity Junior College of Animation & Graphics', 'AJCAG702', 'Bandra Kurla Complex, Mumbai, Maharashtra 400051', '2025-07-11 17:25:44', '2025-07-11 17:25:44', 1),
(729, 'Balaji Junior College of Screen Communication', 'BJCSC703', 'Kurla East, Mumbai, Maharashtra 400024', '2025-07-11 17:25:44', '2025-07-11 17:25:44', 1),
(730, 'Bhavan’s Junior College of Visual Expression', 'BJCVE704', 'Andheri West, Mumbai, Maharashtra 400058', '2025-07-11 17:25:44', '2025-07-11 17:25:44', 1),
(731, 'Diocesan Junior College of Digital Expression', 'DJCDE705', 'Marine Lines, Mumbai, Maharashtra 400020', '2025-07-11 17:25:44', '2025-07-11 17:25:44', 1),
(732, 'Fr. Agnel Junior College of Creative Technology', 'FAJCT706', 'Vasai East, Mumbai, Maharashtra 401208', '2025-07-11 17:25:44', '2025-07-11 17:25:44', 1),
(733, 'Garodia Junior College of Animation & FX', 'GJCAFX707', 'Ghatkopar East, Mumbai, Maharashtra 400077', '2025-07-11 17:25:44', '2025-07-11 17:25:44', 1),
(734, 'Hansraj Moraji Junior College of Motion Media', 'HMJCMM708', 'Juhu, Mumbai, Maharashtra 400049', '2025-07-11 17:25:44', '2025-07-11 17:25:44', 1),
(735, 'Hiranandani Foundation Junior College of VFX Arts', 'HFJCVFX709', 'Powai, Mumbai, Maharashtra 400076', '2025-07-11 17:25:44', '2025-07-11 17:25:44', 1),
(736, 'Indira Junior College of Film Studies', 'IJCFS710', 'Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 17:25:44', '2025-07-11 17:25:44', 1),
(737, 'Jawaharlal Nehru Junior College of Animation', 'JNJCA711', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 17:25:44', '2025-07-11 17:25:44', 1),
(738, 'Kalina Junior College of Digital Imaging', 'KJCDI712', 'Santacruz East, Mumbai, Maharashtra 400055', '2025-07-11 17:25:44', '2025-07-11 17:25:44', 1),
(739, 'Lokmanya Tilak Junior College of Visual Narrative', 'LTJCVN713', 'Grant Road, Mumbai, Maharashtra 400007', '2025-07-11 17:25:44', '2025-07-11 17:25:44', 1),
(740, 'Mithibai Junior College of Visual Journalism', 'MJCVJ714', 'Vile Parle West, Mumbai, Maharashtra 400056', '2025-07-11 17:25:44', '2025-07-11 17:25:44', 1),
(741, 'Mumbai College Junior College of Creative Arts', 'MCJCCA715', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 17:25:44', '2025-07-11 17:25:44', 1),
(742, 'Ryan Junior College of Film Communication', 'RJCFM716', 'Malad West, Mumbai, Maharashtra 400064', '2025-07-11 17:25:44', '2025-07-11 17:25:44', 1),
(743, 'St. Theresa’s Junior College of Animation', 'STJCA717', 'Santacruz East, Mumbai, Maharashtra 400055', '2025-07-11 17:25:44', '2025-07-11 17:25:44', 1),
(744, 'Sophia Junior College of Digital Expression', 'SJCDE718', 'Sion East, Mumbai, Maharashtra 400022', '2025-07-11 17:25:44', '2025-07-11 17:25:44', 1),
(745, 'Tilak Junior College of Motion Arts', 'TJCMA719', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 17:25:44', '2025-07-11 17:25:44', 1),
(746, 'Universal Junior College of Screen Design', 'UJCSd720', 'Vasai West, Mumbai, Maharashtra 401202', '2025-07-11 17:25:44', '2025-07-11 17:25:44', 1),
(747, 'Victoria Junior College of Film Studies', 'VJCFS721', 'Parel, Mumbai, Maharashtra 400012', '2025-07-11 17:25:45', '2025-07-11 17:25:45', 1),
(748, 'Wilson Junior College of Digital Film', 'WJCDF722', 'Kurla West, Mumbai, Maharashtra 400070', '2025-07-11 17:25:45', '2025-07-11 17:25:45', 1),
(749, 'YMCA Junior College of Film Design', 'YMCAJCFD723', 'Churchgate, Mumbai, Maharashtra 400020', '2025-07-11 17:25:45', '2025-07-11 17:25:45', 1),
(750, 'Zee Junior College of Motion Pictures', 'ZJCMP724', 'Andheri West, Mumbai, Maharashtra 400053', '2025-07-11 17:25:45', '2025-07-11 17:25:45', 1),
(751, 'Amity Junior College of Visual Innovation', 'AJCVI725', 'Bandra Kurla Complex, Mumbai, Maharashtra 400051', '2025-07-11 17:25:45', '2025-07-11 17:25:45', 1),
(752, 'Balaji Junior College of Film Artistry', 'BJCFA726', 'Kurla East, Mumbai, Maharashtra 400024', '2025-07-11 17:25:45', '2025-07-11 17:25:45', 1);

-- --------------------------------------------------------

--
-- Table structure for table `college_coordinators`
--

CREATE TABLE `college_coordinators` (
  `college_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `competitions`
--

CREATE TABLE `competitions` (
  `competition_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `competition_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `max_participants` int(11) DEFAULT NULL,
  `registration_deadline` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `competition_applications`
--

CREATE TABLE `competition_applications` (
  `application_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `competition_id` int(11) NOT NULL,
  `application_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `message_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('new','read','replied','archived') DEFAULT 'new'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`message_id`, `name`, `email`, `subject`, `message`, `submitted_at`, `status`) VALUES
(1, 'yashodip ugavekar', 'ugavekaryashodip23@gmail.com', 'Remove the admin', 'Revoke the role of Mr. Abhishek Bhat from admin of universal event portal', '2025-06-30 15:06:02', 'new'),
(4, 'yashodip ugavekar', 'ugavekaryashodip23@gmail.com', 'Remove the admin', 'sfda', '2025-06-30 16:10:47', 'new'),
(5, 'Yashodip Jagdish Ugavekar', 'ugavekaryashodip23@gmail.com', 'Ugavekar', 'Ugavekar', '2025-07-01 03:20:43', 'new'),
(6, 'Yashodip Jagdish Ugavekar', 'ugavekaryashodip23@gmail.com', 'apply for the coordinator role', 'I am the co-ordinatior', '2025-07-04 13:45:54', 'new');

-- --------------------------------------------------------

--
-- Table structure for table `coordinator_requests`
--

CREATE TABLE `coordinator_requests` (
  `request_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `college_id` int(11) NOT NULL,
  `justification` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reviewed_by_user_id` int(11) DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `short_name` varchar(255) NOT NULL,
  `college_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`, `short_name`, `college_id`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Arts', 'ART', 35, '2025-07-02 04:04:10', '2025-07-02 04:04:10', 1),
(2, 'Commerce', 'COM', 35, '2025-07-02 04:04:10', '2025-07-02 04:04:10', 1),
(3, 'Science', 'SCI', 35, '2025-07-02 04:04:10', '2025-07-02 04:04:10', 1),
(4, 'Zoology', 'ZOO', 35, '2025-07-02 04:04:10', '2025-07-02 04:04:10', 1),
(5, 'Botany', 'BOT', 35, '2025-07-02 04:04:10', '2025-07-02 04:04:10', 1),
(6, 'Mathematics', 'MATH', 35, '2025-07-02 04:04:10', '2025-07-02 04:04:10', 1),
(7, 'Physics', 'PHY', 35, '2025-07-02 04:04:10', '2025-07-02 04:04:10', 1),
(8, 'Chemistry', 'CHEM', 35, '2025-07-02 04:04:10', '2025-07-02 04:04:10', 1),
(9, 'Computer Science', 'CS', 35, '2025-07-02 04:04:10', '2025-07-02 04:04:10', 1),
(10, 'Information Technology', 'IT', 35, '2025-07-02 04:04:10', '2025-07-02 04:04:10', 1),
(11, 'Management Studies', 'MS', 35, '2025-07-02 04:04:10', '2025-07-02 04:04:10', 1),
(12, 'Accounting and Finance', 'AF', 35, '2025-07-02 04:04:10', '2025-07-02 04:04:10', 1);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date NOT NULL,
  `event_time` time DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `is_departmental` tinyint(1) DEFAULT 0,
  `dept_id` int(11) DEFAULT NULL,
  `is_competition_event` tinyint(1) DEFAULT 0,
  `provides_lunch` tinyint(1) DEFAULT 0,
  `coordinator_user_id` int(11) NOT NULL,
  `event_leader_user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_organizers`
--

CREATE TABLE `event_organizers` (
  `organizer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `role` varchar(100) DEFAULT NULL,
  `assigned_by_user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_registrations`
--

CREATE TABLE `event_registrations` (
  `registration_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `opted_for_lunch` tinyint(1) DEFAULT 0,
  `attended` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reset_passwords`
--

CREATE TABLE `reset_passwords` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `description`, `created_at`, `status`) VALUES
(1, 'student', 'Standard college student', '2025-06-29 06:57:16', 1),
(2, 'coordinator', 'College coordinator responsible for college/department level events', '2025-06-29 06:57:16', 1),
(3, 'event_leader', 'Specific leader for an event', '2025-06-29 06:57:16', 1),
(4, 'organizer', 'General organizer/volunteer for an event', '2025-06-29 06:57:16', 1),
(5, 'admin', 'System administrator with full access', '2025-06-29 06:57:16', 1);

-- --------------------------------------------------------

--
-- Table structure for table `student_college_details`
--

CREATE TABLE `student_college_details` (
  `student_detail_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `college_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `enrollment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `dob` date DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `college_id` int(11) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `photourl` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `middle_name`, `last_name`, `email`, `password_hash`, `dob`, `contact_number`, `college_id`, `dept_id`, `role_id`, `created_at`, `updated_at`, `photourl`, `status`) VALUES
(1, 'Yashodip', 'Jagdish', 'Ugavekar', 'ugavekaryashodip23@gmail.com', '$2y$10$EUbbZVaNSumXgVJO8WVoEOTOF0cMtafeXgEPRIEdqymimWJ0Oyiw.', '2003-12-23', '8010565102', 35, 10, 5, '2025-07-02 14:00:54', '2025-07-03 15:04:41', NULL, 1),
(2, 'Lajari', 'Manohar', 'Gawade', 'lajarigawade42@gmail.com', '$2y$10$oQldsXuYxWUybn5DzYuUIe2JhI94TNrC1ChQY23iK4iPFkZy/I37u', '2005-04-11', '8007270223', 35, 10, 5, '2025-07-02 14:10:39', '2025-07-02 14:15:44', NULL, 1),
(3, 'Abhishek', 'Ganapatif', 'Bhat', 'abhishekbhat014@gmail.com', '$2y$10$Zcgf2aDkjv2Gt3VSADaZBOVDHcz0McMVOR0Cp.bQ3MRhwLGGWVvTy', '2005-01-03', '9823369562', 35, 10, 5, '2025-07-02 14:12:36', '2025-07-10 15:46:32', 'assets/images/AbhishekAdmin.jpeg', 1),
(4, 'Yojana', 'Santosh', 'Gawade', 'gawadeyojana010@gmail.com', '$2y$10$Eh.iv3iAhhkujJvPoyWsk.NTzOczmRcHbw5d9iW2K3kPDlYmrEHdu', '2005-11-05', '8999057576', 35, 10, 5, '2025-07-02 14:14:22', '2025-07-06 05:05:41', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `app_settings`
--
ALTER TABLE `app_settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Indexes for table `colleges`
--
ALTER TABLE `colleges`
  ADD PRIMARY KEY (`college_id`),
  ADD UNIQUE KEY `college_name` (`college_name`),
  ADD UNIQUE KEY `college_code` (`college_code`);

--
-- Indexes for table `college_coordinators`
--
ALTER TABLE `college_coordinators`
  ADD PRIMARY KEY (`college_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `competitions`
--
ALTER TABLE `competitions`
  ADD PRIMARY KEY (`competition_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `competition_applications`
--
ALTER TABLE `competition_applications`
  ADD PRIMARY KEY (`application_id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`competition_id`),
  ADD KEY `competition_id` (`competition_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `coordinator_requests`
--
ALTER TABLE `coordinator_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`college_id`),
  ADD KEY `college_id` (`college_id`),
  ADD KEY `reviewed_by_user_id` (`reviewed_by_user_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`),
  ADD UNIQUE KEY `department_name` (`department_name`,`college_id`),
  ADD KEY `college_id` (`college_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `department_id` (`dept_id`),
  ADD KEY `coordinator_user_id` (`coordinator_user_id`),
  ADD KEY `event_leader_user_id` (`event_leader_user_id`);

--
-- Indexes for table `event_organizers`
--
ALTER TABLE `event_organizers`
  ADD PRIMARY KEY (`organizer_id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`event_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `assigned_by_user_id` (`assigned_by_user_id`);

--
-- Indexes for table `event_registrations`
--
ALTER TABLE `event_registrations`
  ADD PRIMARY KEY (`registration_id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`event_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `reset_passwords`
--
ALTER TABLE `reset_passwords`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_otp_unique` (`email`,`otp`),
  ADD KEY `expires_at_idx` (`expires_at`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `student_college_details`
--
ALTER TABLE `student_college_details`
  ADD PRIMARY KEY (`student_detail_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `college_id` (`college_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_college` (`college_id`),
  ADD KEY `fk_department` (`dept_id`),
  ADD KEY `fk_role` (`role_id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `colleges`
--
ALTER TABLE `colleges`
  MODIFY `college_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=753;

--
-- AUTO_INCREMENT for table `competitions`
--
ALTER TABLE `competitions`
  MODIFY `competition_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `competition_applications`
--
ALTER TABLE `competition_applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `coordinator_requests`
--
ALTER TABLE `coordinator_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `event_organizers`
--
ALTER TABLE `event_organizers`
  MODIFY `organizer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_registrations`
--
ALTER TABLE `event_registrations`
  MODIFY `registration_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reset_passwords`
--
ALTER TABLE `reset_passwords`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `student_college_details`
--
ALTER TABLE `student_college_details`
  MODIFY `student_detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `college_coordinators`
--
ALTER TABLE `college_coordinators`
  ADD CONSTRAINT `college_coordinators_ibfk_1` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`college_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `college_coordinators_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `competitions`
--
ALTER TABLE `competitions`
  ADD CONSTRAINT `competitions_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE;

--
-- Constraints for table `competition_applications`
--
ALTER TABLE `competition_applications`
  ADD CONSTRAINT `competition_applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `competition_applications_ibfk_2` FOREIGN KEY (`competition_id`) REFERENCES `competitions` (`competition_id`) ON DELETE CASCADE;

--
-- Constraints for table `coordinator_requests`
--
ALTER TABLE `coordinator_requests`
  ADD CONSTRAINT `coordinator_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coordinator_requests_ibfk_2` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`college_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coordinator_requests_ibfk_3` FOREIGN KEY (`reviewed_by_user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_ibfk_1` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`college_id`) ON DELETE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`dept_id`) REFERENCES `departments` (`department_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `events_ibfk_2` FOREIGN KEY (`coordinator_user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `events_ibfk_3` FOREIGN KEY (`event_leader_user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `event_organizers`
--
ALTER TABLE `event_organizers`
  ADD CONSTRAINT `event_organizers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_organizers_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_organizers_ibfk_3` FOREIGN KEY (`assigned_by_user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `event_registrations`
--
ALTER TABLE `event_registrations`
  ADD CONSTRAINT `event_registrations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_registrations_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_college_details`
--
ALTER TABLE `student_college_details`
  ADD CONSTRAINT `student_college_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_college_details_ibfk_2` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`college_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_college_details_ibfk_3` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_college` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`college_id`),
  ADD CONSTRAINT `fk_department` FOREIGN KEY (`dept_id`) REFERENCES `departments` (`department_id`),
  ADD CONSTRAINT `fk_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
