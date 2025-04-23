
use pesticide_version6;


CREATE TABLE `role` (
  `Role_ID` int NOT NULL AUTO_INCREMENT,
  `Role_name` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  PRIMARY KEY (`Role_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
INSERT INTO `role` VALUES (1,'Admin','Quản trị viên toàn hệ thống'),(2,'Customer','Khách hàng thông thường'),(3,'Collaborator','Cộng tác viên');



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
  `Date_created` datetime NOT NULL,
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `Email` (`Email`),
  KEY `Role_ID` (`Role_ID`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`Role_ID`) REFERENCES `role` (`Role_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `brand` (
  `BrandID` bigint NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Image` varchar(255) DEFAULT NULL,
  `Slug` varchar(255) NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`BrandID`),
  UNIQUE KEY `Slug` (`Slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `brand` VALUES (1,'Danh mục 2','Mô tả thương hiệu','1741612061cabybara.jpg','danh-muc-2',1),
(2,'Syngenta','Công ty sản xuất phân bón và hóa chất','1743061541.png','syngenta',0),
(3,'Tập đoàn 1','Mô tả tập đoàn 1','1729689990ctu.png','tap-doan-1',1),
(4,'Tập đoàn 2','Mô tả tập đoàn 2','1729689990ctu.png','tap-doan-2',1);




CREATE TABLE `category` (
  `CategoryID` bigint NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Image` varchar(255) DEFAULT NULL,
  `Slug` varchar(255) NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`CategoryID`),
  UNIQUE KEY `Slug` (`Slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `category` VALUES (1,'Thuốc trừ sâu','Diệt côn trùng và sâu bệnh',NULL,'thuoc-tru-sau',1),
(2,'Phân bón','Cung cấp dinh dưỡng cho cây trồng',NULL,'phan-bon',1),
(3,'Danh mục 1','Mô tả danh mục 1','1744276314cabybara.jpg','danh-muc-1',1),
(4,'Danh mục 2','Mô tả danh mục 2','1744276307ctu.png','danh-muc-2',1);



-- CREATE TABLE `comment` (
--   `id` int NOT NULL AUTO_INCREMENT,
--   `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
--   `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
--   `comment` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
--   `status` int NOT NULL,
--   `product_id_comment` int NOT NULL,
--   `date_cmt` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
--   PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- INSERT INTO `comment` VALUES (1,'Thuan','Thuan@gmail.com','Rat hai long',1,1,'2025-01-01');




CREATE TABLE `discount` (
  `DiscountID` int NOT NULL AUTO_INCREMENT,
  `Coupon_code` varchar(50) NOT NULL,
  `Discount_type` enum('Percentage','Fixed') NOT NULL,
  `Discount_value` bigint NOT NULL,
  `Min_order_value` bigint NOT NULL DEFAULT '0',
  `Start_date` datetime NOT NULL,
  `End_date` datetime NOT NULL,
  `Remaining_quantity` int NOT NULL DEFAULT '0',
  `Status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`DiscountID`),
  UNIQUE KEY `Coupon_code` (`Coupon_code`),
  CONSTRAINT `discount_chk_1` CHECK ((`Discount_value` >= 0)),
  CONSTRAINT `discount_chk_2` CHECK ((`Min_order_value` >= 0)),
  CONSTRAINT `discount_chk_3` CHECK ((`Max_discount` >= 0))
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `discount` VALUES (1,'SALE10','Percentage',10,500000,NULL,'2025-03-01 00:00:00','2025-12-31 00:00:00', 10, 1),
(2,'VIP100','Fixed',100000,300000,NULL,'2025-03-01 00:00:00','2025-12-31 00:00:00', 10, 1);




CREATE TABLE `product` (
  `ProductID` bigint NOT NULL AUTO_INCREMENT,
  `Product_Code` varchar(50) NOT NULL,
  `Name` varchar(200) NOT NULL,
  `Description` varchar(500) DEFAULT NULL,
  `Product_uses` varchar(255) DEFAULT NULL,
  `Unit` varchar(50) NOT NULL,
  `Selling_price` bigint NOT NULL,
  `Promotion` int DEFAULT '0',
  `Image` varchar(255) DEFAULT NULL,
  `Slug` varchar(255) NOT NULL,
  `CategoryID` bigint NOT NULL,
  `BrandID` bigint NOT NULL,
  `Date_created` datetime NOT NULL,
  `Status` int DEFAULT '0',
  `Deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`ProductID`),
  UNIQUE KEY `Slug` (`Slug`),
  KEY `CategoryID` (`CategoryID`),
  KEY `BrandID` (`BrandID`),
  CONSTRAINT `product_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`CategoryID`),
  CONSTRAINT `product_ibfk_2` FOREIGN KEY (`BrandID`) REFERENCES `brand` (`BrandID`),
  CONSTRAINT `product_chk_1` CHECK ((`Selling_price` >= 0)),
  CONSTRAINT `product_chk_2` CHECK (((`Promotion` >= 0) and (`Promotion` <= 100)))
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ProductID BIGINT,
    UserID INT,
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active TINYINT DEFAULT 0,
    FOREIGN KEY (ProductID) REFERENCES product(ProductID) ON DELETE CASCADE,
    FOREIGN KEY (UserID) REFERENCES users(UserID) ON DELETE SET NULL
);  



CREATE TABLE `suppliers` (
  `SupplierID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Contact` varchar(255) DEFAULT NULL,
  `Phone` varchar(20) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Status` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`SupplierID`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `warehouse_receipt` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `tax_identification_number` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `name_of_delivery_person` varchar(100) NOT NULL,
  `delivery_unit` varchar(100) DEFAULT NULL,
  `address` text,
  `delivery_note_number` varchar(255) DEFAULT NULL,
  `warehouse_from` varchar(100) DEFAULT NULL,
  `supplier_id` int DEFAULT NULL,
  `sub_total` bigint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_supplier_id` (`supplier_id`),
  CONSTRAINT `warehouse_receipt_fk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`SupplierID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `warehouse_receipt_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Receipt_id` bigint NOT NULL,
  `ProductID` bigint NOT NULL,
  `Product_Code` varchar(50) DEFAULT NULL,
  `Unit` varchar(50) NOT NULL,
  `Import_price` bigint NOT NULL,
  `Exp_date` datetime NOT NULL,
  `Quantity_doc` bigint NOT NULL,
  `Quantity_actual` bigint NOT NULL,
  `Notes` text,
  PRIMARY KEY (`id`),
  KEY `receipt_id` (`Receipt_id`),
  KEY `idx_product_id` (`ProductID`),
  KEY `idx_receipt_id` (`Receipt_id`),
  CONSTRAINT `warehouse_receipt_items_fk_1` FOREIGN KEY (`Receipt_id`) REFERENCES `warehouse_receipt` (`id`) ON DELETE CASCADE,
  CONSTRAINT `warehouse_receipt_items_fk_2` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`) ON DELETE CASCADE,
  CONSTRAINT `warehouse_receipt_items_chk_1` CHECK ((`Quantity_doc` >= 0)),
  CONSTRAINT `warehouse_receipt_items_chk_2` CHECK ((`Quantity_actual` >= 0))
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



-- CREATE TABLE `warehouse_receipt_signatures` (
--   `id` int NOT NULL AUTO_INCREMENT,
--   `receipt_id` bigint NOT NULL,
--   `position` enum('C.H.Trưởng','Người giao hàng','Cung ứng','Thủ kho','Người lập phiếu') NOT NULL,
--   `signer_name` varchar(100) DEFAULT NULL,
--   `signed_at` timestamp NULL DEFAULT NULL,
--   PRIMARY KEY (`id`),
--   KEY `receipt_id` (`receipt_id`),
--   CONSTRAINT `warehouse_receipt_signatures_fk_1` FOREIGN KEY (`receipt_id`) REFERENCES `warehouse_receipt` (`id`) ON DELETE CASCADE
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `batches` (
  `Batch_ID` int NOT NULL AUTO_INCREMENT,
  `Warehouse_Receipt_ID` bigint NOT NULL,
  `ProductID` bigint NOT NULL,
  `Quantity` bigint NOT NULL,
  `Import_date` datetime NOT NULL,
  `Expiry_date` datetime NOT NULL,
  `Import_price` bigint NOT NULL,
  `SupplierID` int NOT NULL,
  `remaining_quantity` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`Batch_ID`),
  KEY `ProductID` (`ProductID`),
  KEY `SupplierID` (`SupplierID`),
  KEY `batches_ibfk_3` (`Warehouse_Receipt_ID`),
  CONSTRAINT `batches_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`),
  CONSTRAINT `batches_ibfk_2` FOREIGN KEY (`SupplierID`) REFERENCES `suppliers` (`SupplierID`),
  CONSTRAINT `batches_ibfk_3` FOREIGN KEY (`Warehouse_Receipt_ID`) REFERENCES `warehouse_receipt` (`id`),
  CONSTRAINT `batches_chk_1` CHECK ((`Quantity` >= 0)),
  CONSTRAINT `batches_chk_2` CHECK ((`Import_price` >= 0))
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `shipping` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `checkout_method` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;





CREATE TABLE `orders` (
  `OrderID` int NOT NULL AUTO_INCREMENT,
  `Order_Code` varchar(20) NOT NULL,
  `Order_Status` int NOT NULL DEFAULT '0',
  `Payment_Status` int DEFAULT '0',
  `UserID` int NOT NULL,
  `TotalAmount` bigint NOT NULL,
  `DiscountID` int DEFAULT NULL,
  `Date_Order` datetime NOT NULL,
  `Date_delivered` datetime DEFAULT NULL,
  `Payment_date_successful` datetime DEFAULT NULL,
  `ShippingID` int DEFAULT NULL,
  PRIMARY KEY (`OrderID`),
  UNIQUE KEY `Order_Code` (`Order_Code`),
  KEY `UserID` (`UserID`),
  KEY `DiscountID` (`DiscountID`),
  KEY `fk_shipping` (`ShippingID`),
  KEY `idx_payment_date` (`Payment_date_successful`),
  CONSTRAINT `fk_shipping` FOREIGN KEY (`ShippingID`) REFERENCES `shipping` (`id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`),
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`DiscountID`) REFERENCES `discount` (`DiscountID`),
  CONSTRAINT `orders_chk_1` CHECK ((`TotalAmount` >= 0))
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `order_detail` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Order_Code` varchar(20) NOT NULL,
  `ProductID` bigint NOT NULL,
  `Quantity` bigint NOT NULL,
  `Selling_price` bigint NOT NULL,
  `Subtotal` bigint NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Order_Code` (`Order_Code`),
  KEY `ProductID` (`ProductID`),
  KEY `idx_order_code` (`Order_Code`),
  CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`Order_Code`) REFERENCES `orders` (`Order_Code`),
  CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`),
  CONSTRAINT `order_detail_chk_1` CHECK ((`Quantity` >= 0)),
  CONSTRAINT `order_detail_chk_2` CHECK ((`Selling_price` >= 0)),
  CONSTRAINT `order_detail_chk_3` CHECK ((`Subtotal` >= 0))
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



CREATE TABLE `order_batches` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_detail_id` int NOT NULL,
  `batch_id` int NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_detail_id` (`order_detail_id`),
  KEY `batch_id` (`batch_id`),
  CONSTRAINT `order_batches_ibfk_1` FOREIGN KEY (`order_detail_id`) REFERENCES `order_detail` (`id`),
  CONSTRAINT `order_batches_ibfk_2` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`Batch_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;





CREATE TABLE `vnpay` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `ShippingID` int DEFAULT NULL,
  `vnp_Amount` varchar(50) DEFAULT NULL,
  `vnp_BankCode` varchar(50) DEFAULT NULL,
  `vnp_BankTranNo` varchar(50) DEFAULT NULL,
  `vnp_CardType` varchar(50) DEFAULT NULL,
  `vnp_OrderInfo` varchar(50) DEFAULT NULL,
  `vnp_PayDate` datetime DEFAULT NULL,
  `vnp_ResponseCode` varchar(50) DEFAULT NULL,
  `vnp_TmnCode` varchar(50) DEFAULT NULL,
  `vnp_TransactionNo` varchar(50) DEFAULT NULL,
  `vnp_TransactionStatus` varchar(50) DEFAULT NULL,
  `vnp_TxnRef` varchar(50) DEFAULT NULL,
  `vnp_SecureHash` text,
  PRIMARY KEY (`id`),
  KEY `shipping_method_vnpay` (`ShippingID`),
  KEY `shipping_method_vnpay_ordercode` (`vnp_TxnRef`),
  CONSTRAINT `shipping_method_vnpay` FOREIGN KEY (`ShippingID`) REFERENCES `shipping` (`id`),
  CONSTRAINT `shipping_method_vnpay_ordercode` FOREIGN KEY (`vnp_TxnRef`) REFERENCES `orders` (`Order_Code`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE `sliders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
