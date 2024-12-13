-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 26, 2024 at 03:55 PM
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
-- Database: `carrental`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `UserName` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `updationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `UserName`, `Password`, `updationDate`) VALUES
(1, 'admin', '63ce5df4a6b95d049b47c7d6b67e7c4b', '2024-06-28 02:54:09');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `timestamp`) VALUES
(17, 56, 47, 'Hi', '2024-07-18 17:27:22'),
(18, 47, 56, 'Hello..nice to see you', '2024-07-18 17:28:04'),
(19, 47, 56, 'I need a ride to Badulla. Do you have ride?', '2024-07-18 17:28:50'),
(20, 56, 47, 'Yes ...i will post it. Check website by evening.', '2024-07-18 17:29:40');

-- --------------------------------------------------------

--
-- Table structure for table `ride_requests`
--

CREATE TABLE `ride_requests` (
  `ride_request_id` int(11) NOT NULL,
  `ride_id` int(11) NOT NULL,
  `pickup_point` varchar(255) NOT NULL,
  `number_of_seats` int(11) NOT NULL,
  `passenger_name` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `start_place` varchar(255) NOT NULL,
  `end_place` varchar(255) NOT NULL,
  `date_of_travel` date NOT NULL,
  `time_of_travel` time NOT NULL,
  `driver` varchar(255) NOT NULL,
  `Status` enum('Pending','Confirmed','Rejected') DEFAULT 'Pending',
  `cost` decimal(10,2) NOT NULL,
  `payment_status` varchar(50) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ride_requests`
--

INSERT INTO `ride_requests` (`ride_request_id`, `ride_id`, `pickup_point`, `number_of_seats`, `passenger_name`, `message`, `start_place`, `end_place`, `date_of_travel`, `time_of_travel`, `driver`, `Status`, `cost`, `payment_status`) VALUES
(43, 17, 'Kale Stop, Sri Lanka', 1, 'Thivani', 'test', 'Kandy, Sri Lanka', 'Badulla, Sri Lanka', '2024-07-21', '09:00:00', 'Logenthiran Visagan', 'Confirmed', 2000.00, 'Completed'),
(45, 19, 'XGJJ+R8 Puliyankulam, Sri Lanka', 1, 'Thivani', 'utyutr', 'Jaffna, Sri Lanka', 'Colombo, Sri Lanka', '2024-07-27', '08:00:00', 'Logenthiran Visagan', 'Pending', 3000.00, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `tblbooking`
--

CREATE TABLE `tblbooking` (
  `id` int(11) NOT NULL,
  `BookingNumber` bigint(12) DEFAULT NULL,
  `userEmail` varchar(100) DEFAULT NULL,
  `VehicleId` int(11) DEFAULT NULL,
  `FromDate` varchar(20) DEFAULT NULL,
  `ToDate` varchar(20) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `Status` int(11) DEFAULT NULL,
  `PostingDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `LastUpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `payment_status` enum('Pending','Completed','Failed') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblbooking`
--

INSERT INTO `tblbooking` (`id`, `BookingNumber`, `userEmail`, `VehicleId`, `FromDate`, `ToDate`, `message`, `Status`, `PostingDate`, `LastUpdationDate`, `payment_status`) VALUES
(27, 404164315, 'logenthiranvisagan@gmail.com', 3, '2024-07-19T08:00', '2024-07-21T08:00', 'Trip with family.', 1, '2024-07-18 15:06:12', '2024-07-18 15:07:10', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `tblbrands`
--

CREATE TABLE `tblbrands` (
  `id` int(11) NOT NULL,
  `BrandName` varchar(120) NOT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblbrands`
--

INSERT INTO `tblbrands` (`id`, `BrandName`, `CreationDate`, `UpdationDate`) VALUES
(1, 'Maruti', '2024-05-18 16:24:34', '2024-05-19 06:42:23'),
(2, 'BMW', '2024-05-18 16:24:50', NULL),
(3, 'Audi', '2024-05-18 16:25:03', NULL),
(4, 'Nissan', '2024-05-18 16:25:13', NULL),
(5, 'Toyota', '2024-05-18 16:25:24', NULL),
(7, 'Volkswagon', '2024-05-18 06:22:13', '2024-05-19 14:14:09');

-- --------------------------------------------------------

--
-- Table structure for table `tblcontactusinfo`
--

CREATE TABLE `tblcontactusinfo` (
  `id` int(11) NOT NULL,
  `Address` tinytext DEFAULT NULL,
  `EmailId` varchar(255) DEFAULT NULL,
  `ContactNo` char(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblcontactusinfo`
--

INSERT INTO `tblcontactusinfo` (`id`, `Address`, `EmailId`, `ContactNo`) VALUES
(1, 'Somasundram Road, Anaikoddai, Jaffna', 'vssinfo@gmail.com', '0212255675');

-- --------------------------------------------------------

--
-- Table structure for table `tblcontactusquery`
--

CREATE TABLE `tblcontactusquery` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `EmailId` varchar(120) DEFAULT NULL,
  `ContactNumber` char(11) DEFAULT NULL,
  `Message` longtext DEFAULT NULL,
  `PostingDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblcontactusquery`
--

INSERT INTO `tblcontactusquery` (`id`, `name`, `EmailId`, `ContactNumber`, `Message`, `PostingDate`, `status`) VALUES
(9, 'Logenthiran Visagan', 'logenthiranvisagan10@gmail.com', '0777222298', 'How can i cancel ride?', '2024-07-18 15:15:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblpages`
--

CREATE TABLE `tblpages` (
  `id` int(11) NOT NULL,
  `PageName` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT '',
  `detail` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblpages`
--

INSERT INTO `tblpages` (`id`, `PageName`, `type`, `detail`) VALUES
(1, 'Terms and Conditions', 'terms', '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        \r\n<p><span style=\"font-size: 1em; text-align: justify;\">By accessing or using GoPool, you agree to the following terms and conditions.</span><br></p><ol><li><p style=\"text-align: justify;\"><strong>Eligibility</strong>: To use our services, you must be at least 18 years old (or the legal age to drive in your jurisdiction) and possess a valid driver\'s license.</p></li><li><p style=\"text-align: justify;\"><strong>Account Creation</strong>: When creating an account, you agree to provide accurate and current information and are responsible for keeping your account credentials confidential.</p></li><li><p style=\"text-align: justify;\"><strong>Vehicle Use and Return</strong>: Our rental vehicles are intended for personal or business use and must be returned in the same condition, barring normal wear and tear. <span style=\"text-decoration-line: underline;\">Users are liable for any damage, loss, or fines incurred during the rental period.</span></p></li><li><p style=\"text-align: justify;\"><strong>Ride-Sharing Drivers</strong>: Ride-sharing drivers must comply with all traffic laws, maintain appropriate insurance, and ensure their vehicles are properly registered.</p></li><li><p style=\"text-align: justify;\"><strong>Payment</strong>:&nbsp;<span style=\"font-size: 1em;\">Payment for rental and ride share is due at the time of booking or as specified.&nbsp;</span><span style=\"text-decoration-line: underline; font-size: 1em;\">10% will be deducted from each passenger\'s payment in ride-sharing.</span></p></li><li><p style=\"text-align: justify;\"><strong>Cancellation and Refunds</strong>:&nbsp;<span style=\"font-size: 1em;\">&nbsp;No refunds will be issued once payments are made. In the case of emergency cancellations, you must contact GoPool for further decession.</span></p></li><li><p style=\"text-align: justify;\"><strong>Cost and Bargaining</strong>: <span style=\"text-decoration-line: underline;\">There is no bargaining in ride-share costs</span>. The cost per person is decided fully by the driver, and GoPool is not responsible for it.</p></li><li><p style=\"text-align: justify;\"><strong>Dispute Resolution</strong>: Disputes related to our services will be resolved through binding arbitration, with class action lawsuits waived.</p></li><li><p style=\"text-align: justify;\"><strong>Modifications</strong>: We reserve the right to modify these terms at any time, with changes taking effect upon posting on our website.&nbsp;<span style=\"font-size: 1em;\">For questions or concerns about these terms, contact us at </span><a rel=\"noreferrer\" style=\"font-size: 1em; background-color: rgb(255, 255, 255);\">vssinfo@gmail.com</a><span style=\"font-size: 1em;\">.</span></p></li></ol>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        '),
(2, 'Privacy Policy', 'privacy', '																														<p><br></p><h3 style=\"text-align: justify;\"><p class=\"MsoNormal\" style=\"text-align: center; margin: 15pt 0in 7.5pt; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;\"><span style=\"font-size: 18pt; font-family: Helvetica, sans-serif;\"><span style=\"font-weight: bold;\">Information We Collect</span><o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\" style=\"text-align: center; margin: 0in 0in 0.0001pt 0.25in; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;\"><span style=\"font-size: 10.5pt; font-family: Helvetica, sans-serif;\">&nbsp;Name, email, phone number, profile\r\npicture, location data, payment info, ride history.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\" style=\"text-align: center; margin: 0in 0in 0.0001pt 0.25in; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;\"><br></p>\r\n\r\n<p class=\"MsoNormal\" style=\"text-align: center; margin: 15pt 0in 7.5pt; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;\"><span style=\"font-size: 18pt; font-family: Helvetica, sans-serif;\"><span style=\"font-weight: bold;\">How We Use Your\r\nInformation</span><o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\" style=\"text-align: center; margin: 0in 0in 0.0001pt 0.25in; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;\"><span style=\"font-size: 10.5pt; font-family: Helvetica, sans-serif;\">Provide and manage our\r\nservices.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\" style=\"text-align: center; margin: 0in 0in 0.0001pt 0.25in; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;\"><span style=\"font-size: 10.5pt; font-family: Helvetica, sans-serif;\">Process payments and\r\ntransactions.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\" style=\"text-align: center; margin: 0in 0in 0.0001pt 0.25in; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;\"><span style=\"font-size: 10.5pt; font-family: Helvetica, sans-serif;\">Improve and\r\npersonalize user experience.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\" style=\"text-align: center; margin: 0in 0in 0.0001pt 0.25in; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;\"><span style=\"font-size: 10.5pt; font-family: Helvetica, sans-serif;\">Communicate updates\r\nand support messages.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\" style=\"text-align: center; margin: 0in 0in 0.0001pt 0.25in; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;\"><span style=\"font-size: 10.5pt; font-family: Helvetica, sans-serif;\">Analyze trends to\r\nenhance our services.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\" style=\"text-align: center; margin: 15pt 0in 7.5pt; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;\"><span style=\"font-size: 18pt; font-family: Helvetica, sans-serif;\"><span style=\"font-weight: bold;\">Data Security</span><o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\" align=\"center\" style=\"margin-bottom: 0.3in; text-align: center; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;\"><span style=\"font-size: 10.5pt; font-family: Helvetica, sans-serif;\">We\r\nemploy security measures to protect your data but cannot guarantee complete\r\nsecurity.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\" style=\"text-align: center; margin: 15pt 0in 7.5pt; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;\"><span style=\"font-size: 18pt; font-family: Helvetica, sans-serif;\"><span style=\"font-weight: bold;\">Your Rights</span><o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\" style=\"text-align: center; margin-bottom: 0.3in; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;\"><span style=\"font-size: 10.5pt; font-family: Helvetica, sans-serif;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; You may have rights to access, correct,\r\nor delete your personal information.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\" style=\"text-align: center; margin-bottom: 0.3in; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;\"><b><u><span style=\"font-family: Helvetica, sans-serif;\">For questions or concerns, contact us.<o:p></o:p></span></u></b></p></h3>\r\n										\r\n										\r\n										'),
(3, 'About Us ', 'aboutus', '																																																		<div><div style=\"text-align: justify;\"><span style=\"color: rgb(52, 52, 52); font-family: Arial, Helvetica, sans-serif;\">GoPool is more than just a ride-sharing app; it\'s a community-driven solution for <span style=\"font-weight: bold;\">efficient, affordable, and eco-friendly transportation</span>. We\'re dedicated to connecting people who share similar commutes, making their daily journeys smoother, greener, and more enjoyable.</span></div><div style=\"text-align: justify;\"><span style=\"color: rgb(52, 52, 52); font-family: Arial, Helvetica, sans-serif;\"><br></span></div><div style=\"text-align: justify;\"><span style=\"color: rgb(52, 52, 52); font-family: Arial, Helvetica, sans-serif;\">At GoPool, we believe in the power of collaboration. By pooling resources and sharing rides, we reduce traffic congestion, lower carbon emissions, and save money for our users. Whether you\'re commuting to work, heading to an event, or running errands around town, GoPool connects you with like-minded individuals, turning every trip into a chance to make a positive impact.&nbsp;</span><span style=\"color: rgb(52, 52, 52); font-family: Arial, Helvetica, sans-serif; font-size: 1em;\">Join GoPool today and be a part of the movement towards smarter, more sustainable transportation. Together, we can create a better future, one ride at a time.</span></div></div>\r\n										\r\n										\r\n										\r\n										\r\n										\r\n										'),
(11, 'FAQs', 'faqs', '                                                        <div style=\"text-align: center;\"><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-weight: bold; text-align: justify;\">Q--&gt;Do we need driving license to register to GoPool?</span></div>                                                                                                                                                                        																														<div><div style=\"text-align: center;\"><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif;\">&nbsp;<span style=\"font-weight: bold;\">A--&gt;</span><span style=\"font-style: italic;\">&nbsp;Not Excatly. You can use your NIC also to register.</span></span></div><div style=\"text-align: center;\"><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\"><br></span></div></div>                                                                                                                                                            ');

-- --------------------------------------------------------

--
-- Table structure for table `tblpayments`
--

CREATE TABLE `tblpayments` (
  `payment_id` int(11) NOT NULL,
  `payer_name` varchar(50) NOT NULL,
  `service_type` varchar(50) NOT NULL,
  `service_number` int(25) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `transaction_id` varchar(100) NOT NULL,
  `payment_status` varchar(20) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ride_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblpayments`
--

INSERT INTO `tblpayments` (`payment_id`, `payer_name`, `service_type`, `service_number`, `amount`, `currency`, `payment_method`, `transaction_id`, `payment_status`, `created_at`, `updated_at`, `ride_id`) VALUES
(23, 'Logenthiran Visagan', 'Rental', 404164315, 20000.00, 'LKR', 'Card', 'pi_3PdvvARt9Hrs8PLR0jdxSyhj', 'Completed', '2024-07-18 20:37:10', '2024-07-18 20:37:10', NULL),
(25, 'Thivani', 'Ride', 43, 2000.00, 'LKR', 'Card', 'pi_3PdxSrRt9Hrs8PLR0kDvGOKT', 'Completed', '2024-07-18 22:17:16', '2024-07-18 22:17:16', 17),
(26, 'Thivani', 'Ride', 44, 3000.00, 'LKR', 'Card', 'pi_3Pgo73Rt9Hrs8PLR03C5XiHi', 'Completed', '2024-07-26 18:53:21', '2024-07-26 18:53:21', 18);

-- --------------------------------------------------------

--
-- Table structure for table `tblrating`
--

CREATE TABLE `tblrating` (
  `id` int(11) NOT NULL,
  `postedby` varchar(255) NOT NULL,
  `Rating` int(11) NOT NULL CHECK (`Rating` >= 1 and `Rating` <= 5),
  `review` text DEFAULT NULL,
  `rating_type` enum('rental','driver') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `DriverName` varchar(100) NOT NULL,
  `VehicleId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblrating`
--

INSERT INTO `tblrating` (`id`, `postedby`, `Rating`, `review`, `rating_type`, `created_at`, `DriverName`, `VehicleId`) VALUES
(38, 'Logenthiran Visagan', 5, 'Good car.', 'rental', '2024-07-18 15:08:07', '', 3),
(40, 'Thivani', 4, 'Good driver', 'driver', '2024-07-18 16:51:50', 'Logenthiran Visagan', 0),
(41, 'Thivani', 3, 'Average', 'driver', '2024-07-18 17:12:41', 'Logenthiran Visagan', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblrides`
--

CREATE TABLE `tblrides` (
  `ride_id` int(11) NOT NULL,
  `start_place` varchar(255) NOT NULL,
  `end_place` varchar(255) NOT NULL,
  `postedby` varchar(255) NOT NULL,
  `capacity` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `date_of_travel` date NOT NULL,
  `time_of_travel` time NOT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `distance` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `start_lat` float(10,6) DEFAULT NULL,
  `start_lng` float(10,6) DEFAULT NULL,
  `trip_status` enum('not_started','in_progress','completed') DEFAULT 'not_started',
  `price_per_person` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblrides`
--

INSERT INTO `tblrides` (`ride_id`, `start_place`, `end_place`, `postedby`, `capacity`, `message`, `date_of_travel`, `time_of_travel`, `duration`, `distance`, `created_at`, `start_lat`, `start_lng`, `trip_status`, `price_per_person`) VALUES
(17, 'Kandy, Sri Lanka', 'Badulla, Sri Lanka', 'Logenthiran Visagan', 1, 'Going for wedding.', '2024-07-21', '09:00:00', '3 hours 0 mins', '116 km', '2024-07-18 15:11:02', 6.983683, 81.074287, 'completed', 2000.00),
(19, 'Jaffna, Sri Lanka', 'Colombo, Sri Lanka', 'Logenthiran Visagan', 2, 'test', '2024-07-27', '08:00:00', '7 hours 32 mins', '403 km', '2024-07-26 13:28:44', 6.983428, 81.074898, 'in_progress', 3000.00);

-- --------------------------------------------------------

--
-- Table structure for table `tblrideshare_vehicles`
--

CREATE TABLE `tblrideshare_vehicles` (
  `id` int(11) NOT NULL,
  `VehiclesTitle` varchar(150) DEFAULT NULL,
  `VehiclesBrand` varchar(50) DEFAULT NULL,
  `VehiclesOverview` longtext DEFAULT NULL,
  `VehicleNumber` varchar(20) DEFAULT NULL,
  `FuelType` varchar(100) DEFAULT NULL,
  `ModelYear` int(6) DEFAULT NULL,
  `SeatingCapacity` int(11) DEFAULT NULL,
  `Vimage1` varchar(120) DEFAULT NULL,
  `Vimage2` varchar(120) DEFAULT NULL,
  `Vimage3` varchar(120) DEFAULT NULL,
  `Vimage4` varchar(120) DEFAULT NULL,
  `Vimage5` varchar(120) DEFAULT NULL,
  `AirConditioner` int(11) DEFAULT NULL,
  `PowerDoorLocks` int(11) DEFAULT NULL,
  `AntiLockBrakingSystem` int(11) DEFAULT NULL,
  `BrakeAssist` int(11) DEFAULT NULL,
  `PowerSteering` int(11) DEFAULT NULL,
  `DriverAirbag` int(11) DEFAULT NULL,
  `PassengerAirbag` int(11) DEFAULT NULL,
  `PowerWindows` int(11) DEFAULT NULL,
  `CDPlayer` int(11) DEFAULT NULL,
  `CentralLocking` int(11) DEFAULT NULL,
  `CrashSensor` int(11) DEFAULT NULL,
  `LeatherSeats` int(11) DEFAULT NULL,
  `RegDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `PostedBy` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblrideshare_vehicles`
--

INSERT INTO `tblrideshare_vehicles` (`id`, `VehiclesTitle`, `VehiclesBrand`, `VehiclesOverview`, `VehicleNumber`, `FuelType`, `ModelYear`, `SeatingCapacity`, `Vimage1`, `Vimage2`, `Vimage3`, `Vimage4`, `Vimage5`, `AirConditioner`, `PowerDoorLocks`, `AntiLockBrakingSystem`, `BrakeAssist`, `PowerSteering`, `DriverAirbag`, `PassengerAirbag`, `PowerWindows`, `CDPlayer`, `CentralLocking`, `CrashSensor`, `LeatherSeats`, `RegDate`, `UpdationDate`, `PostedBy`) VALUES
(4, 'Wigo', 'Toyoto', 'Best car to travel for long distance. High comfort.', 'CAR 4256', 'Petrol', 2019, 4, 'Wigo1.jpg', 'Wigo2.jpg', 'Wigo3.jpg', 'Wigo4.jpg', 'Wigo5.jpg', 1, 1, NULL, NULL, NULL, 1, 1, 1, 1, 1, 1, 1, '2024-07-18 15:09:39', NULL, 'Logenthiran Visagan');

-- --------------------------------------------------------

--
-- Table structure for table `tblsubscribers`
--

CREATE TABLE `tblsubscribers` (
  `id` int(11) NOT NULL,
  `SubscriberEmail` varchar(120) DEFAULT NULL,
  `PostingDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbltestimonial`
--

CREATE TABLE `tbltestimonial` (
  `id` int(11) NOT NULL,
  `UserEmail` varchar(100) NOT NULL,
  `Testimonial` mediumtext NOT NULL,
  `PostingDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbltestimonial`
--

INSERT INTO `tbltestimonial` (`id`, `UserEmail`, `Testimonial`, `PostingDate`, `status`) VALUES
(1, 'logenthiranvisagan@gmail.com', 'I am satisfied with their service great job.', '2024-05-24 14:30:12', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblusers`
--

CREATE TABLE `tblusers` (
  `id` int(11) NOT NULL,
  `FullName` varchar(120) DEFAULT NULL,
  `EmailId` varchar(100) DEFAULT NULL,
  `Password` varchar(100) DEFAULT NULL,
  `ContactNo` char(11) DEFAULT NULL,
  `DOB` varchar(100) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `Selfie` varchar(120) DEFAULT NULL,
  `NIC` varchar(120) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `Status` enum('pending','confirmed') DEFAULT 'pending',
  `StripeId` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblusers`
--

INSERT INTO `tblusers` (`id`, `FullName`, `EmailId`, `Password`, `ContactNo`, `DOB`, `Address`, `Selfie`, `NIC`, `RegDate`, `UpdationDate`, `Status`, `StripeId`) VALUES
(47, 'Thivani', 'logenthiranvisagan10@gmail.com', '$2y$10$BvnWLPBk1X0hPfr.0hSdxe0n55vvxOfLiRPnLqvGkOA9w1IbcOAiW', '0775666206', '1999-08-19', 'Kanakapuram, Killinochchi ', 'IMG_0010.JPG', '688078.jpg', '2024-06-29 02:59:03', '2024-07-13 16:17:21', 'confirmed', NULL),
(56, 'Logenthiran Visagan', 'logenthiranvisagan@gmail.com', '$2y$10$5QEWz7Lgyn4ZQD1oelzCaef44ILSByddv/Gf3a0MwgoZ.bPYL1YBi', '0777222298', '1999-08-19', 'No5,Passra Main Road,Badulla', 'visa.jpg', 'License.jpg', '2024-07-09 04:38:06', '2024-07-14 14:34:40', 'confirmed', 'acct_1Pc9xNRrMlRltVMc');

-- --------------------------------------------------------

--
-- Table structure for table `tblvehicles`
--

CREATE TABLE `tblvehicles` (
  `id` int(11) NOT NULL,
  `VehiclesTitle` varchar(150) DEFAULT NULL,
  `VehiclesBrand` int(11) DEFAULT NULL,
  `VehiclesOverview` longtext DEFAULT NULL,
  `PricePerDay` int(11) DEFAULT NULL,
  `FuelType` varchar(100) DEFAULT NULL,
  `ModelYear` int(6) DEFAULT NULL,
  `AvailableDistrict` varchar(50) NOT NULL,
  `SeatingCapacity` int(11) DEFAULT NULL,
  `Vimage1` varchar(120) DEFAULT NULL,
  `Vimage2` varchar(120) DEFAULT NULL,
  `Vimage3` varchar(120) DEFAULT NULL,
  `Vimage4` varchar(120) DEFAULT NULL,
  `Vimage5` varchar(120) DEFAULT NULL,
  `AirConditioner` int(11) DEFAULT NULL,
  `PowerDoorLocks` int(11) DEFAULT NULL,
  `AntiLockBrakingSystem` int(11) DEFAULT NULL,
  `BrakeAssist` int(11) DEFAULT NULL,
  `PowerSteering` int(11) DEFAULT NULL,
  `DriverAirbag` int(11) DEFAULT NULL,
  `PassengerAirbag` int(11) DEFAULT NULL,
  `PowerWindows` int(11) DEFAULT NULL,
  `CDPlayer` int(11) DEFAULT NULL,
  `CentralLocking` int(11) DEFAULT NULL,
  `CrashSensor` int(11) DEFAULT NULL,
  `LeatherSeats` int(11) DEFAULT NULL,
  `RegDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblvehicles`
--

INSERT INTO `tblvehicles` (`id`, `VehiclesTitle`, `VehiclesBrand`, `VehiclesOverview`, `PricePerDay`, `FuelType`, `ModelYear`, `AvailableDistrict`, `SeatingCapacity`, `Vimage1`, `Vimage2`, `Vimage3`, `Vimage4`, `Vimage5`, `AirConditioner`, `PowerDoorLocks`, `AntiLockBrakingSystem`, `BrakeAssist`, `PowerSteering`, `DriverAirbag`, `PassengerAirbag`, `PowerWindows`, `CDPlayer`, `CentralLocking`, `CrashSensor`, `LeatherSeats`, `RegDate`, `UpdationDate`) VALUES
(1, 'Maruti Suzuki Wagon R ', 1, 'Maruti Wagon R Latest Updates\r\n\r\nMaruti Suzuki has launched the BS6 Wagon R S-CNG in India. The LXI CNG and LXI (O) CNG variants now cost Rs 5.25 lakh and Rs 5.32 lakh respectively, up by Rs 19,000. Maruti claims a fuel economy of 32.52km per kg. The CNG Wagon R’s continuation in the BS6 era is part of the carmaker’s ‘Mission Green Million’ initiative announced at Auto Expo 2020.\r\n\r\nPreviously, the carmaker had updated the 1.0-litre powertrain to meet BS6 emission norms. It develops 68PS of power and 90Nm of torque, same as the BS4 unit. However, the updated motor now returns 21.79 kmpl, which is a little less than the BS4 unit’s 22.5kmpl claimed figure. Barring the CNG variants, the prices of the Wagon R 1.0-litre have been hiked by Rs 8,000.', 5000, 'Diesel', 2019, 'Jaffna', 5, 'rear-3-4-left-589823254_930x620.jpg', 'tail-lamp-1666712219_930x620.jpg', 'rear-3-4-right-520328200_930x620.jpg', 'steering-close-up-1288209207_930x620.jpg', 'boot-with-standard-luggage-202327489_930x620.jpg', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, NULL, 1, '2024-06-24 07:04:35', '2024-07-16 16:29:33'),
(2, 'BMW 5 Series', 2, 'BMW 5 Series price starts at ? 55.4 Lakh and goes upto ? 68.39 Lakh. The price of Petrol version for 5 Series ranges between ? 55.4 Lakh - ? 60.89 Lakh and the price of Diesel version for 5 Series ranges between ? 60.89 Lakh - ? 68.39 Lakh.', 10000, 'Petrol', 2018, 'Kilinochchi', 5, 'BMW-5-Series-Exterior-102005.jpg', 'BMW-5-Series-New-Exterior-89729.jpg', 'BMW-5-Series-Exterior-102006.jpg', 'BMW-5-Series-Interior-102021.jpg', 'BMW-5-Series-Interior-102022.jpg', 1, 1, 1, 1, 1, 1, 1, 1, NULL, 1, 1, 1, '2024-06-24 07:12:02', '2024-07-06 04:18:45'),
(3, 'Audi Q8', 3, 'As per ARAI, the mileage of Q8 is 0 kmpl. Real mileage of the vehicle varies depending upon the driving habits. City and highway mileage figures also vary depending upon the road conditions.', 10000, 'Petrol', 2017, 'Jaffna', 5, 'audi-q8-front-view4.jpg', '1920x1080_MTC_XL_framed_Audi-Odessa-Armaturen_Spiegelung_CC_v05.jpg', 'audi1.jpg', '1audiq8.jpg', 'audi-q8-front-view4.jpeg', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2024-06-24 07:19:21', '2024-07-06 04:16:07'),
(4, 'Nissan Kicks', 4, 'Latest Update: Nissan has launched the Kicks 2020 with a new turbocharged petrol engine. You can read more about it here.\r\n\r\nNissan Kicks Price and Variants: The Kicks is available in four variants: XL, XV, XV Premium, and XV Premium(O).', 6000, 'Petrol', 2020, 'Kilinochchi', 5, 'front-left-side-47.jpg', 'kicksmodelimage.jpg', 'download.jpg', 'kicksmodelimage.jpg', '', 1, NULL, NULL, 1, NULL, NULL, 1, 1, NULL, NULL, NULL, 1, '2024-06-25 07:25:28', NULL),
(5, 'Nissan GT-R', 4, ' The GT-R packs a 3.8-litre V6 twin-turbocharged petrol, which puts out 570PS of max power at 6800rpm and 637Nm of peak torque. The engine is mated to a 6-speed dual-clutch transmission in an all-wheel-drive setup. The 2+2 seater GT-R sprints from 0-100kmph in less than 3', 2000, 'Petrol', 2019, 'Vavuniya', 5, 'Nissan-GTR-Right-Front-Three-Quarter-84895.jpg', 'Best-Nissan-Cars-in-India-New-and-Used-1.jpg', '2bb3bc938e734f462e45ed83be05165d.jpg', '2020-nissan-gtr-rakuda-tan-semi-aniline-leather-interior.jpg', 'images.jpg', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2024-06-25 07:34:17', NULL),
(6, 'Nissan Sunny 2020', 4, 'Value for money product and it was so good It is more spacious than other sedans It looks like a luxurious car.', 4000, 'CNG', 2018, 'Jaffna', 5, 'Nissan-Sunny-Right-Front-Three-Quarter-48975_ol.jpg', 'images (1).jpg', 'Nissan-Sunny-Interior-114977.jpg', 'nissan-sunny-8a29f53-500x375.jpg', 'new-nissan-sunny-photo.jpg', 1, 1, NULL, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2024-06-25 09:12:49', NULL),
(7, 'Toyota Fortuner', 5, 'Toyota Fortuner Features: It is a premium seven-seater SUV loaded with features such as LED projector headlamps with LED DRLs, LED fog lamp, and power-adjustable and foldable ORVMs. Inside, the Fortuner offers features such as power-adjustable driver seat, automatic climate control, push-button stop/start, and cruise control.\r\n\r\nToyota Fortuner Safety Features: The Toyota Fortuner gets seven airbags, hill assist control, vehicle stability control with brake assist, and ABS with EBD.', 3000, 'Petrol', 2020, 'Jaffna', 5, '2015_Toyota_Fortuner_(New_Zealand).jpg', 'toyota-fortuner-legender-rear-quarters-6e57.jpg', 'zw-toyota-fortuner-2020-2.jpg', 'download (1).jpg', '', NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, NULL, 1, 1, 1, '2024-06-28 09:17:46', NULL),
(8, 'Maruti Suzuki Vitara Brezza', 1, 'The new Vitara Brezza is a well-rounded package that is feature-loaded and offers good drivability. And it is backed by Maruti’s vast service network, which ensures a peace of mind to customers. The petrol motor could have been more refined and offered more pep.', 3000, 'Petrol', 2018, 'Vavuniya', 5, 'marutisuzuki-vitara-brezza-right-front-three-quarter3.jpg', 'marutisuzuki-vitara-brezza-rear-view37.jpg', 'marutisuzuki-vitara-brezza-dashboard10.jpg', 'marutisuzuki-vitara-brezza-boot-space59.jpg', 'marutisuzuki-vitara-brezza-boot-space28.jpg', NULL, 1, 1, 1, NULL, NULL, 1, NULL, NULL, NULL, 1, NULL, '2024-06-28 09:23:11', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ride_requests`
--
ALTER TABLE `ride_requests`
  ADD PRIMARY KEY (`ride_request_id`);

--
-- Indexes for table `tblbooking`
--
ALTER TABLE `tblbooking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblbrands`
--
ALTER TABLE `tblbrands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblcontactusinfo`
--
ALTER TABLE `tblcontactusinfo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblcontactusquery`
--
ALTER TABLE `tblcontactusquery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblpages`
--
ALTER TABLE `tblpages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblpayments`
--
ALTER TABLE `tblpayments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `tblrating`
--
ALTER TABLE `tblrating`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblrides`
--
ALTER TABLE `tblrides`
  ADD PRIMARY KEY (`ride_id`);

--
-- Indexes for table `tblrideshare_vehicles`
--
ALTER TABLE `tblrideshare_vehicles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblsubscribers`
--
ALTER TABLE `tblsubscribers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbltestimonial`
--
ALTER TABLE `tbltestimonial`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblusers`
--
ALTER TABLE `tblusers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblvehicles`
--
ALTER TABLE `tblvehicles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `ride_requests`
--
ALTER TABLE `ride_requests`
  MODIFY `ride_request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `tblbooking`
--
ALTER TABLE `tblbooking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tblbrands`
--
ALTER TABLE `tblbrands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tblcontactusinfo`
--
ALTER TABLE `tblcontactusinfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblcontactusquery`
--
ALTER TABLE `tblcontactusquery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tblpages`
--
ALTER TABLE `tblpages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tblpayments`
--
ALTER TABLE `tblpayments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tblrating`
--
ALTER TABLE `tblrating`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `tblrides`
--
ALTER TABLE `tblrides`
  MODIFY `ride_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tblrideshare_vehicles`
--
ALTER TABLE `tblrideshare_vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblsubscribers`
--
ALTER TABLE `tblsubscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbltestimonial`
--
ALTER TABLE `tbltestimonial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblusers`
--
ALTER TABLE `tblusers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `tblvehicles`
--
ALTER TABLE `tblvehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
