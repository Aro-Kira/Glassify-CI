-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2025 at 12:55 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `glassify-test`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `Appointment_ID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `Admin_ID` int(11) NOT NULL,
  `Customer_ID` int(11) NOT NULL,
  `Scheduled_Date` date NOT NULL,
  `Start_time` datetime DEFAULT NULL,
  `End_time` datetime DEFAULT NULL,
  `Notes` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `Cart_ID` int(11) NOT NULL,
  `Product_ID` int(11) NOT NULL,
  `CustomizationID` int(11) DEFAULT NULL,
  `Quantity` int(11) NOT NULL,
  `Added_Date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `Customer_ID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customization`
--

CREATE TABLE `customization` (
  `CustID` int(11) NOT NULL,
  `Product_ID` int(11) NOT NULL,
  `Customer_ID` int(11) NOT NULL,
  `Dimensions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`Dimensions`)),
  `GlassShape` enum('Rectangle','Square','Triangle','Pentagon') DEFAULT NULL,
  `GlassType` enum('Tempered','Laminated','Double','Low-E','Tinted','Frosted') DEFAULT NULL,
  `GlassThickness` enum('4mm','6mm','8mm','10mm','12mm') DEFAULT NULL,
  `EdgeWork` enum('Polish','Mitered','Beveled','Seamed') DEFAULT NULL,
  `FrameType` enum('Aluminum','Wood') DEFAULT NULL,
  `Engraving` varchar(200) DEFAULT NULL,
  `DesignRef` varchar(255) DEFAULT NULL,
  `EstimatePrice` decimal(10,2) DEFAULT NULL,
  `CreatedDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `ModifiedDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `Inventory_ID` int(11) NOT NULL,
  `Product_ID` int(11) NOT NULL,
  `SalesRep_ID` int(11) NOT NULL,
  `QuantityInStock` int(11) NOT NULL DEFAULT 0,
  `MinimumStockLevel` int(11) DEFAULT 0,
  `MaximumStockLevel` int(11) DEFAULT 1000,
  `LastUpdated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `UpdatedBy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `inventory`
--
DELIMITER $$
CREATE TRIGGER `update_product_status` AFTER INSERT ON `inventory` FOR EACH ROW BEGIN
    IF NEW.QuantityInStock = 0 THEN
        UPDATE Product SET Status = 'Out of Stock'
        WHERE Product_ID = NEW.Product_ID;

    ELSEIF NEW.QuantityInStock <= NEW.MinimumStockLevel THEN
        UPDATE Product SET Status = 'Low Stock'
        WHERE Product_ID = NEW.Product_ID;

    ELSE
        UPDATE Product SET Status = 'In Stock'
        WHERE Product_ID = NEW.Product_ID;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_product_status_on_update` AFTER UPDATE ON `inventory` FOR EACH ROW BEGIN
    IF NEW.QuantityInStock = 0 THEN
        UPDATE Product SET Status = 'Out of Stock'
        WHERE Product_ID = NEW.Product_ID;

    ELSEIF NEW.QuantityInStock <= NEW.MinimumStockLevel THEN
        UPDATE Product SET Status = 'Low Stock'
        WHERE Product_ID = NEW.Product_ID;

    ELSE
        UPDATE Product SET Status = 'In Stock'
        WHERE Product_ID = NEW.Product_ID;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `issuereport`
--

CREATE TABLE `issuereport` (
  `Issue_ID` int(11) NOT NULL,
  `Customer_ID` int(11) NOT NULL,
  `Order_ID` int(11) NOT NULL,
  `First_Name` varchar(50) NOT NULL,
  `Last_Name` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `PhoneNum` varchar(13) NOT NULL,
  `Category` enum('Order Issue','Billing Issue','Delivery Issue') DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `Report_Date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `OrderID` int(11) NOT NULL,
  `Customer_ID` int(11) NOT NULL,
  `SalesRep_ID` int(11) NOT NULL,
  `OrderDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `TotalAmount` decimal(12,2) NOT NULL,
  `Status` enum('Pending','Approved','In Fabrication','Ready for Installation','Completed','Cancelled','Returned') DEFAULT 'Pending',
  `PaymentStatus` enum('Pending','Paid','Partial','Refunded') DEFAULT 'Pending',
  `DeliveryAddress` varchar(255) DEFAULT NULL,
  `SpecialInstructions` varchar(255) DEFAULT NULL,
  `QuotationPDFUrl` varchar(255) DEFAULT NULL,
  `ContractPDFUrl` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `Payment_ID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `Amount` decimal(10,2) NOT NULL,
  `Payment_Date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Transaction_ID` varchar(100) DEFAULT NULL,
  `Status` enum('Pending','Paid','Failed','Refunded') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `Product_ID` int(11) NOT NULL,
  `ProductName` varchar(100) NOT NULL,
  `Category` varchar(50) NOT NULL,
  `Material` enum('Glass','Aluminum') NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `ImageUrl` varchar(255) DEFAULT NULL,
  `DateAdded` timestamp NOT NULL DEFAULT current_timestamp(),
  `Status` enum('In Stock','Out of Stock','Low Stock') DEFAULT 'Out of Stock'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`Product_ID`, `ProductName`, `Category`, `Material`, `Price`, `ImageUrl`, `DateAdded`, `Status`) VALUES
(1, 'test', 'sliding-windows', 'Aluminum', '2000.00', '94238694b5d7a113af9ad379b1ef0fc2.jpg', '2025-11-19 17:15:34', ''),
(2, 'Round Mirrorr', 'mirrors', 'Glass', '100.00', '82fd096bc469ff4d4ee82fb915dc9948.jpg', '2025-11-20 18:25:01', ''),
(3, 'Round Mirrorr', 'mirrors', 'Glass', '100.00', '071475aebd05e4433f8d0e8df9292724.jpg', '2025-11-20 18:25:01', '');

-- --------------------------------------------------------

--
-- Table structure for table `projectschedule`
--

CREATE TABLE `projectschedule` (
  `Schedule_ID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `Admin_ID` int(11) NOT NULL,
  `Project_Name` varchar(100) NOT NULL,
  `Start_Date` date NOT NULL,
  `End_Date` date NOT NULL,
  `Status` enum('Scheduled','In progress','Completed','Delayed') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotation`
--

CREATE TABLE `quotation` (
  `QuotationID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `Quotation_num` varchar(20) NOT NULL,
  `Total_amount` decimal(10,2) DEFAULT NULL,
  `Tax_amount` decimal(10,2) DEFAULT NULL,
  `Terms_conditions` varchar(255) DEFAULT NULL,
  `Pdf_url` varchar(255) DEFAULT NULL,
  `Created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(11) NOT NULL,
  `First_Name` varchar(50) NOT NULL,
  `Last_Name` varchar(50) NOT NULL,
  `Middle_Name` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `PhoneNum` varchar(13) NOT NULL,
  `Role` enum('Admin','Sales Representative','Inventory Officer','Customer') NOT NULL,
  `Status` enum('Active','Inactive') DEFAULT 'Active',
  `Date_Created` timestamp NOT NULL DEFAULT current_timestamp(),
  `Date_Updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`Appointment_ID`),
  ADD KEY `OrderID` (`OrderID`),
  ADD KEY `Admin_ID` (`Admin_ID`),
  ADD KEY `Customer_ID` (`Customer_ID`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`Cart_ID`),
  ADD KEY `Product_ID` (`Product_ID`),
  ADD KEY `CustomizationID` (`CustomizationID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`Customer_ID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `customization`
--
ALTER TABLE `customization`
  ADD PRIMARY KEY (`CustID`),
  ADD KEY `Product_ID` (`Product_ID`),
  ADD KEY `Customer_ID` (`Customer_ID`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`Inventory_ID`),
  ADD KEY `Product_ID` (`Product_ID`),
  ADD KEY `UpdatedBy` (`UpdatedBy`);

--
-- Indexes for table `issuereport`
--
ALTER TABLE `issuereport`
  ADD PRIMARY KEY (`Issue_ID`),
  ADD KEY `Customer_ID` (`Customer_ID`),
  ADD KEY `Order_ID` (`Order_ID`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `Customer_ID` (`Customer_ID`),
  ADD KEY `SalesRep_ID` (`SalesRep_ID`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`Payment_ID`),
  ADD KEY `OrderID` (`OrderID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`Product_ID`);

--
-- Indexes for table `projectschedule`
--
ALTER TABLE `projectschedule`
  ADD PRIMARY KEY (`Schedule_ID`),
  ADD KEY `OrderID` (`OrderID`),
  ADD KEY `Admin_ID` (`Admin_ID`);

--
-- Indexes for table `quotation`
--
ALTER TABLE `quotation`
  ADD PRIMARY KEY (`QuotationID`),
  ADD UNIQUE KEY `Quotation_num` (`Quotation_num`),
  ADD KEY `OrderID` (`OrderID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `Appointment_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `Cart_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `Customer_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customization`
--
ALTER TABLE `customization`
  MODIFY `CustID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `Inventory_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `issuereport`
--
ALTER TABLE `issuereport`
  MODIFY `Issue_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `Payment_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `Product_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `projectschedule`
--
ALTER TABLE `projectschedule`
  MODIFY `Schedule_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotation`
--
ALTER TABLE `quotation`
  MODIFY `QuotationID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `order` (`OrderID`),
  ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`Admin_ID`) REFERENCES `user` (`UserID`),
  ADD CONSTRAINT `appointment_ibfk_3` FOREIGN KEY (`Customer_ID`) REFERENCES `customer` (`Customer_ID`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`Product_ID`) REFERENCES `product` (`Product_ID`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`CustomizationID`) REFERENCES `customization` (`CustID`);

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `customization`
--
ALTER TABLE `customization`
  ADD CONSTRAINT `customization_ibfk_1` FOREIGN KEY (`Product_ID`) REFERENCES `product` (`Product_ID`),
  ADD CONSTRAINT `customization_ibfk_2` FOREIGN KEY (`Customer_ID`) REFERENCES `customer` (`Customer_ID`);

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`Product_ID`) REFERENCES `product` (`Product_ID`),
  ADD CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`UpdatedBy`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `issuereport`
--
ALTER TABLE `issuereport`
  ADD CONSTRAINT `issuereport_ibfk_1` FOREIGN KEY (`Customer_ID`) REFERENCES `customer` (`Customer_ID`),
  ADD CONSTRAINT `issuereport_ibfk_2` FOREIGN KEY (`Order_ID`) REFERENCES `order` (`OrderID`);

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`Customer_ID`) REFERENCES `customer` (`Customer_ID`),
  ADD CONSTRAINT `order_ibfk_2` FOREIGN KEY (`SalesRep_ID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `order` (`OrderID`);

--
-- Constraints for table `projectschedule`
--
ALTER TABLE `projectschedule`
  ADD CONSTRAINT `projectschedule_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `order` (`OrderID`),
  ADD CONSTRAINT `projectschedule_ibfk_2` FOREIGN KEY (`Admin_ID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `quotation`
--
ALTER TABLE `quotation`
  ADD CONSTRAINT `quotation_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `order` (`OrderID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
