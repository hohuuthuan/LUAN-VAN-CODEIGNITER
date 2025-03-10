-- MySQL dump 10.13  Distrib 8.0.38, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: pesticide_shop
-- ------------------------------------------------------
-- Server version	8.0.37

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `batches`
--

DROP TABLE IF EXISTS `batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `batches` (
  `Batch_ID` int NOT NULL AUTO_INCREMENT,
  `ProductID` bigint NOT NULL,
  `Quantity` bigint NOT NULL,
  `Import_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Expiry_date` datetime NOT NULL,
  `Import_price` bigint NOT NULL,
  `SupplierID` int NOT NULL,
  `remaining_quantity` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`Batch_ID`),
  KEY `ProductID` (`ProductID`),
  KEY `SupplierID` (`SupplierID`),
  CONSTRAINT `batches_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`),
  CONSTRAINT `batches_ibfk_2` FOREIGN KEY (`SupplierID`) REFERENCES `suppliers` (`SupplierID`),
  CONSTRAINT `batches_chk_1` CHECK ((`Quantity` >= 0)),
  CONSTRAINT `batches_chk_2` CHECK ((`Import_price` >= 0))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `batches`
--

LOCK TABLES `batches` WRITE;
/*!40000 ALTER TABLE `batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brand`
--

DROP TABLE IF EXISTS `brand`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `brand` (
  `BrandID` bigint NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Image` varchar(255) DEFAULT NULL,
  `Slug` varchar(255) NOT NULL,
  `Publish` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`BrandID`),
  UNIQUE KEY `Slug` (`Slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brand`
--

LOCK TABLES `brand` WRITE;
/*!40000 ALTER TABLE `brand` DISABLE KEYS */;
INSERT INTO `brand` VALUES (1,'Bayer','Thương hiệu thuốc bảo vệ thực vật',NULL,'bayer',1),(2,'Syngenta','Công ty sản xuất phân bón và hóa chất',NULL,'syngenta',1);
/*!40000 ALTER TABLE `brand` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `category` (
  `CategoryID` bigint NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Image` varchar(255) DEFAULT NULL,
  `Slug` varchar(255) NOT NULL,
  `Publish` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`CategoryID`),
  UNIQUE KEY `Slug` (`Slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'Thuốc trừ sâu','Diệt côn trùng và sâu bệnh',NULL,'thuoc-tru-sau',1),(2,'Phân bón','Cung cấp dinh dưỡng cho cây trồng',NULL,'phan-bon',1);
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `discount`
--

DROP TABLE IF EXISTS `discount`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `discount` (
  `DiscountID` int NOT NULL AUTO_INCREMENT,
  `Coupon_code` varchar(50) NOT NULL,
  `Discount_type` enum('Percentage','Fixed') NOT NULL,
  `Discount_value` bigint NOT NULL,
  `Min_order_value` bigint NOT NULL DEFAULT '0',
  `Max_discount` bigint DEFAULT NULL,
  `Start_date` datetime NOT NULL,
  `End_date` datetime NOT NULL,
  PRIMARY KEY (`DiscountID`),
  UNIQUE KEY `Coupon_code` (`Coupon_code`),
  CONSTRAINT `discount_chk_1` CHECK ((`Discount_value` >= 0)),
  CONSTRAINT `discount_chk_2` CHECK ((`Min_order_value` >= 0)),
  CONSTRAINT `discount_chk_3` CHECK ((`Max_discount` >= 0))
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discount`
--

LOCK TABLES `discount` WRITE;
/*!40000 ALTER TABLE `discount` DISABLE KEYS */;
INSERT INTO `discount` VALUES (1,'SALE10','Percentage',10,500000,NULL,'2025-03-01 00:00:00','2025-12-31 00:00:00'),(2,'VIP100','Fixed',100000,300000,NULL,'2025-03-01 00:00:00','2025-12-31 00:00:00');
/*!40000 ALTER TABLE `discount` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_detail`
--

DROP TABLE IF EXISTS `order_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_detail` (
  `Order_Code` varchar(20) NOT NULL,
  `ProductID` bigint NOT NULL,
  `Batch_ID` int NOT NULL,
  `Quantity` bigint NOT NULL,
  `Selling_price` bigint NOT NULL,
  `Applied_discount` bigint NOT NULL DEFAULT '0',
  `Subtotal` bigint NOT NULL,
  `Date_order` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Order_Code`,`ProductID`,`Batch_ID`),
  KEY `ProductID` (`ProductID`),
  KEY `Batch_ID` (`Batch_ID`),
  CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`Order_Code`) REFERENCES `orders` (`Order_Code`),
  CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`),
  CONSTRAINT `order_detail_ibfk_3` FOREIGN KEY (`Batch_ID`) REFERENCES `batches` (`Batch_ID`),
  CONSTRAINT `order_detail_chk_1` CHECK ((`Quantity` >= 0)),
  CONSTRAINT `order_detail_chk_2` CHECK ((`Selling_price` >= 0)),
  CONSTRAINT `order_detail_chk_3` CHECK ((`Subtotal` >= 0))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_detail`
--

LOCK TABLES `order_detail` WRITE;
/*!40000 ALTER TABLE `order_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `OrderID` int NOT NULL AUTO_INCREMENT,
  `Order_Code` varchar(20) NOT NULL,
  `Order_Status` varchar(255) NOT NULL DEFAULT 'Pending',
  `Payment_Status` varchar(255) NOT NULL DEFAULT 'Unpaid',
  `UserID` int NOT NULL,
  `TotalAmount` bigint NOT NULL,
  `DiscountID` int DEFAULT NULL,
  `Date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`OrderID`),
  UNIQUE KEY `Order_Code` (`Order_Code`),
  KEY `UserID` (`UserID`),
  KEY `DiscountID` (`DiscountID`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`),
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`DiscountID`) REFERENCES `discount` (`DiscountID`),
  CONSTRAINT `orders_chk_1` CHECK ((`TotalAmount` >= 0))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product` (
  `ProductID` bigint NOT NULL AUTO_INCREMENT,
  `Name` varchar(200) NOT NULL,
  `Description` varchar(500) DEFAULT NULL,
  `Product_uses` varchar(255) DEFAULT NULL,
  `Unit` varchar(50) NOT NULL,
  `Selling_price` bigint NOT NULL,
  `Promotion` int DEFAULT '0',
  `Publish` int NOT NULL DEFAULT '1',
  `Image` varchar(255) DEFAULT NULL,
  `Slug` varchar(255) NOT NULL,
  `CategoryID` bigint NOT NULL,
  `BrandID` bigint NOT NULL,
  `Date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`ProductID`),
  UNIQUE KEY `Slug` (`Slug`),
  KEY `CategoryID` (`CategoryID`),
  KEY `BrandID` (`BrandID`),
  CONSTRAINT `product_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`CategoryID`),
  CONSTRAINT `product_ibfk_2` FOREIGN KEY (`BrandID`) REFERENCES `brand` (`BrandID`),
  CONSTRAINT `product_chk_1` CHECK ((`Selling_price` >= 0)),
  CONSTRAINT `product_chk_2` CHECK (((`Promotion` >= 0) and (`Promotion` <= 100)))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (1,'Thuốc trừ sâu X','Diệt sâu hiệu quả','Trừ sâu bọ','Lọ',150000,0,1,NULL,'thuoc-tru-sau-x',1,1,'2025-03-03 20:25:19',NULL);
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchases`
--

DROP TABLE IF EXISTS `purchases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchases` (
  `Purchase_ID` int NOT NULL AUTO_INCREMENT,
  `Batch_ID` int NOT NULL,
  `SupplierID` int NOT NULL,
  `Quantity` bigint NOT NULL,
  `Purchase_price` bigint NOT NULL,
  `Purchase_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Purchase_ID`),
  KEY `Batch_ID` (`Batch_ID`),
  KEY `SupplierID` (`SupplierID`),
  CONSTRAINT `purchases_ibfk_1` FOREIGN KEY (`Batch_ID`) REFERENCES `batches` (`Batch_ID`),
  CONSTRAINT `purchases_ibfk_2` FOREIGN KEY (`SupplierID`) REFERENCES `suppliers` (`SupplierID`),
  CONSTRAINT `purchases_chk_1` CHECK ((`Quantity` >= 0)),
  CONSTRAINT `purchases_chk_2` CHECK ((`Purchase_price` >= 0))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchases`
--

LOCK TABLES `purchases` WRITE;
/*!40000 ALTER TABLE `purchases` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role` (
  `Role_ID` int NOT NULL AUTO_INCREMENT,
  `Role_name` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  PRIMARY KEY (`Role_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,'Admin','Quản trị viên toàn hệ thống'),(2,'Customer','Khách hàng thông thường'),(3,'Supplier','Nhà cung cấp sản phẩm');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `SupplierID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Contact` varchar(255) DEFAULT NULL,
  `Phone` varchar(20) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  PRIMARY KEY (`SupplierID`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `UserID` int NOT NULL AUTO_INCREMENT,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(500) NOT NULL,
  `Role_ID` int NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Phone` varchar(20) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Status` varchar(255) NOT NULL DEFAULT 'Active',
  `Avatar` varchar(255) DEFAULT NULL,
  `Token_Code` varchar(11) DEFAULT NULL,
  `Date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `Email` (`Email`),
  KEY `Role_ID` (`Role_ID`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`Role_ID`) REFERENCES `role` (`Role_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin@example.com','hashed_password',1,'Admin','0123456789','Hà Nội','Active',NULL,NULL,'2025-03-03 20:25:19',NULL),(2,'user1@example.com','hashed_password',2,'Nguyễn Văn A','0987654321','Hồ Chí Minh','Active',NULL,NULL,'2025-03-03 20:25:19',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-03-10 16:54:01
