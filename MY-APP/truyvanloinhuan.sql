use pesticide_version4;


-- Thống kê doanh thu hôm nay

SELECT SUM(TotalAmount) AS TotalRevenue
FROM orders
WHERE DATE(Payment_date_successful) = CURDATE() 
AND Payment_Status = 1;

-- Thống kê doanh thu theo ngày cụ thể
SET @date = '2025-03-25';
SELECT SUM(TotalAmount) AS TotalRevenue
FROM orders
WHERE DATE(Payment_date_successful) = @date
AND Payment_Status = 1;

-- Thống kê doanh thu từ ngày bắt đầu đến ngày kết thúc
SET @start_date = '2025-03-01';
SET @end_date = '2025-03-25';
SELECT DATE(Payment_date_successful) AS Date, SUM(TotalAmount) AS TotalRevenue
FROM orders
WHERE DATE(Payment_date_successful) BETWEEN @start_date AND @end_date
AND Payment_Status = 1
GROUP BY DATE(Payment_date_successful)
ORDER BY DATE(Payment_date_successful);




use pesticide_version4;

-- Thống kê doanh thu tháng hiện tại
SELECT SUM(TotalAmount) AS TotalRevenue
FROM orders
WHERE YEAR(Payment_date_successful) = YEAR(CURDATE())
AND MONTH(Payment_date_successful) = MONTH(CURDATE())
AND Payment_Status = 1;

-- Thống kê doanh thu theo từng tháng của năm hiện tại
SELECT YEAR(Payment_date_successful) AS Year, MONTH(Payment_date_successful) AS Month, 
       SUM(TotalAmount) AS TotalRevenue
FROM orders
WHERE YEAR(Payment_date_successful) = YEAR(CURDATE())
AND Payment_Status = 1
GROUP BY YEAR(Payment_date_successful), MONTH(Payment_date_successful)
ORDER BY YEAR(Payment_date_successful), MONTH(Payment_date_successful);

-- Thống kê doanh thu từ tháng bắt đầu đến tháng kết thúc
SET @start_date = '2025-02-01';
SET @end_date = '2025-05-01';
SELECT YEAR(Payment_date_successful) AS Year, MONTH(Payment_date_successful) AS Month, 
       SUM(TotalAmount) AS TotalRevenue
FROM orders
WHERE CONCAT(YEAR(Payment_date_successful), LPAD(MONTH(Payment_date_successful), 2, '0')) 
      BETWEEN CONCAT(YEAR(@start_date), LPAD(MONTH(@start_date), 2, '0')) 
      AND CONCAT(YEAR(@end_date), LPAD(MONTH(@end_date), 2, '0'))
AND Payment_Status = 1
GROUP BY YEAR(Payment_date_successful), MONTH(Payment_date_successful)
ORDER BY YEAR(Payment_date_successful), MONTH(Payment_date_successful);




-- Thống kê doanh thu năm hiện tại
SELECT SUM(TotalAmount) AS TotalRevenue
FROM orders
WHERE YEAR(Payment_date_successful) = YEAR(CURDATE())
AND Payment_Status = 1;

-- Thống kê doanh thu theo từng năm

SELECT YEAR(Payment_date_successful) AS Year, SUM(TotalAmount) AS TotalRevenue
FROM orders
WHERE Payment_Status = 1
GROUP BY YEAR(Payment_date_successful)
ORDER BY YEAR(Payment_date_successful);

-- Thống kê doanh thu từ năm bắt đầu đến năm kết thúc
SET @start_year = 2022;
SET @end_year = 2025;
SELECT YEAR(Payment_date_successful) AS Year, SUM(TotalAmount) AS TotalRevenue
FROM orders
WHERE YEAR(Payment_date_successful) BETWEEN @start_year AND @end_year
AND Payment_Status = 1
GROUP BY YEAR(Payment_date_successful)
ORDER BY YEAR(Payment_date_successful);




-- Lợi nhuận
-- Tổng chi phí nhập kho
SELECT 
    SUM(wri.Quantity_actual * wri.Import_price) AS Total_Cost
FROM warehouse_receipt_items wri;

-- Tổng doanh thu
SELECT 
    SUM(od.Quantity * p.Selling_price) AS Total_Revenue
FROM order_detail od
JOIN product p ON od.ProductID = p.ProductID;

-- Tính lợi nhuận
SELECT 
    (SELECT SUM(od.Quantity * p.Selling_price) 
     FROM order_detail od
     JOIN product p ON od.ProductID = p.ProductID) 
    - 
    (SELECT SUM(wri.Quantity_actual * wri.Import_price) 
     FROM warehouse_receipt_items wri) 
    AS Total_Profit;



