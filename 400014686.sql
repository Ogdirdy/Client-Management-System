-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 16, 2025 at 01:18 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `400014686`
--

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `ClientId` int NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(60) DEFAULT NULL,
  `LastName` varchar(60) DEFAULT NULL,
  `Address` varchar(120) DEFAULT NULL,
  `ContactName` varchar(60) DEFAULT NULL,
  `ContactNumber` varchar(12) DEFAULT NULL,
  `Type` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ClientId`)
) ENGINE=MyISAM AUTO_INCREMENT=1000005 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`ClientId`, `FirstName`, `LastName`, `Address`, `ContactName`, `ContactNumber`, `Type`) VALUES
(1000001, 'Kadeem', 'Williams', 'St. Michael, Bridgetown', 'Renee Clarke', '1246-1234567', 'Residential'),
(1000002, 'Sharon', 'Harrison', 'Christ Church, Oistins', 'Troy Thompson', '1246-9876543', 'Commercial'),
(1000003, 'Darian', 'Blackman', 'St. James, Holetown', 'Monique Jordan', '1246-5557890', 'Residential'),
(1000004, 'Janelle', 'Mason', 'St. Philip, Six Cross Roads', 'Clinton Nurse', '1246-2345678', 'Commercial');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
CREATE TABLE IF NOT EXISTS `employee` (
  `EmployeeId` int NOT NULL,
  `SSN` varchar(9) DEFAULT NULL,
  `FirstName` varchar(60) DEFAULT NULL,
  `LastName` varchar(60) DEFAULT NULL,
  `Address` varchar(120) DEFAULT NULL,
  `DateHired` date DEFAULT NULL,
  PRIMARY KEY (`EmployeeId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`EmployeeId`, `SSN`, `FirstName`, `LastName`, `Address`, `DateHired`) VALUES
(2000001, '123456789', 'Jason', 'Grant', 'St. Michael, Bridgetown', '2020-03-15'),
(2000002, '987654321', 'Tamika', 'Barrow', 'Christ Church, Oistins', '2019-07-22'),
(2000003, '456123789', 'Andre', 'Forde', 'St. James, Holetown', '2021-01-10'),
(2000004, '321654987', 'Nia', 'Haynes', 'St. Philip, Six Roads', '2022-06-01');

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

DROP TABLE IF EXISTS `equipment`;
CREATE TABLE IF NOT EXISTS `equipment` (
  `SerialNo` int NOT NULL,
  `Name` varchar(60) DEFAULT NULL,
  `Brand` varchar(50) DEFAULT NULL,
  `DateOfPurchase` date DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
  `UsageCost` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`SerialNo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`SerialNo`, `Name`, `Brand`, `DateOfPurchase`, `Price`, `UsageCost`) VALUES
(500001, 'Drill', 'Milwaukee', '2022-06-10', 150.00, 10.00),
(500002, 'Paint Sprayer', 'Graco', '2021-09-15', 250.00, 15.00),
(500003, 'Pipe Wrench', 'RIDGID', '2020-03-20', 75.00, 5.00),
(500004, 'Solar Panel Kit', 'SunPower', '2023-02-12', 1200.00, 50.00);

-- --------------------------------------------------------

--
-- Table structure for table `equipmentusage`
--

DROP TABLE IF EXISTS `equipmentusage`;
CREATE TABLE IF NOT EXISTS `equipmentusage` (
  `SerialNo` int NOT NULL,
  `TaskId` int NOT NULL,
  `DateAssigned` date DEFAULT NULL,
  `DateReturned` date DEFAULT NULL,
  `UsageFee` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`SerialNo`,`TaskId`),
  KEY `TaskId` (`TaskId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `equipmentusage`
--

INSERT INTO `equipmentusage` (`SerialNo`, `TaskId`, `DateAssigned`, `DateReturned`, `UsageFee`) VALUES
(500001, 4000001, '2025-09-10', '2025-09-12', 20.00),
(500002, 4000003, '2025-09-15', '2025-09-16', 30.00),
(500003, 4000002, '2025-09-12', '2025-09-13', 10.00),
(500004, 4000004, '2025-09-20', '2025-09-22', 100.00);

-- --------------------------------------------------------

--
-- Table structure for table `material`
--

DROP TABLE IF EXISTS `material`;
CREATE TABLE IF NOT EXISTS `material` (
  `MaterialId` int NOT NULL,
  `MaterialName` varchar(60) DEFAULT NULL,
  `UnitCost` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`MaterialId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `material`
--

INSERT INTO `material` (`MaterialId`, `MaterialName`, `UnitCost`) VALUES
(6000001, 'Copper Pipe', 25.00),
(6000002, 'Paint - Turquoise', 40.00),
(6000003, 'AC Filter', 15.00),
(6000004, 'Solar Panel Cell', 200.00);

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

DROP TABLE IF EXISTS `project`;
CREATE TABLE IF NOT EXISTS `project` (
  `RequestId` int NOT NULL,
  `TaskId` int NOT NULL,
  `EmployeeId` int NOT NULL,
  `HoursWorked` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`RequestId`,`TaskId`,`EmployeeId`),
  KEY `TaskId` (`TaskId`),
  KEY `EmployeeId` (`EmployeeId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`RequestId`, `TaskId`, `EmployeeId`, `HoursWorked`) VALUES
(3000001, 4000001, 2000001, 10.50),
(3000002, 4000002, 2000002, 8.00),
(3000003, 4000003, 2000003, 12.00),
(3000004, 4000004, 2000004, 15.00);

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

DROP TABLE IF EXISTS `request`;
CREATE TABLE IF NOT EXISTS `request` (
  `RequestId` int NOT NULL AUTO_INCREMENT,
  `ClientId` int DEFAULT NULL,
  `DateOfRequest` date DEFAULT NULL,
  `Description` varchar(400) DEFAULT NULL,
  `EstimatedCost` decimal(10,2) DEFAULT NULL,
  `StartDate` date DEFAULT NULL,
  `FinalCost` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`RequestId`),
  KEY `ClientId` (`ClientId`)
) ENGINE=MyISAM AUTO_INCREMENT=3000005 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`RequestId`, `ClientId`, `DateOfRequest`, `Description`, `EstimatedCost`, `StartDate`, `FinalCost`) VALUES
(3000001, 1000001, '2025-09-01', 'Install new Air conditioning system', 1500.00, '2025-09-10', 1600.00),
(3000002, 1000002, '2025-09-05', 'Repair plumbing leaks', 800.00, '2025-09-12', 850.00),
(3000003, 1000003, '2025-09-07', 'Paint office building', 1200.00, '2025-09-15', 1250.00),
(3000004, 1000004, '2025-09-10', 'Install Solar panels', 5000.00, '2025-09-20', 5100.00);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE IF NOT EXISTS `tasks` (
  `TaskId` int NOT NULL,
  `RequestId` int DEFAULT NULL,
  `ServiceType` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`TaskId`),
  KEY `RequestId` (`RequestId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`TaskId`, `RequestId`, `ServiceType`) VALUES
(4000001, 3000001, 'HVAC Installation'),
(4000002, 3000002, 'Plumbing'),
(4000003, 3000003, 'Painting'),
(4000004, 3000004, 'Solar Installation');

-- --------------------------------------------------------

--
-- Table structure for table `usedmaterial`
--

DROP TABLE IF EXISTS `usedmaterial`;
CREATE TABLE IF NOT EXISTS `usedmaterial` (
  `TaskId` int NOT NULL,
  `MaterialId` int NOT NULL,
  `MaterialCost` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`TaskId`,`MaterialId`),
  KEY `MaterialId` (`MaterialId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `usedmaterial`
--

INSERT INTO `usedmaterial` (`TaskId`, `MaterialId`, `MaterialCost`) VALUES
(4000001, 6000003, 45.00),
(4000002, 6000001, 75.00),
(4000003, 6000002, 120.00),
(4000004, 6000004, 600.00);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
