
-- Xác định doanh thu
SELECT SUM(od.Quantity * od.SellingPrice) AS TotalRevenue
FROM order_detail od;

-- Xác định giá vốn
SELECT SUM(od.Quantity * b.Import_price) AS TotalCOGS
FROM order_detail od
JOIN batches b ON od.Batch_ID = b.Batch_ID;


-- Tính lợi nhuận
SELECT 
    (SUM(od.Quantity * od.SellingPrice) - SUM(od.Quantity * b.Import_price)) AS Profit
FROM order_detail od
JOIN batches b ON od.Batch_ID = b.Batch_ID;


-- Thống kê lợi nhuận theo ngày
SELECT 
    DATE(o.OrderDate) AS Date,
    SUM(od.Quantity * od.SellingPrice) AS TotalRevenue,
    SUM(od.Quantity * b.Import_price) AS TotalCOGS,
    (SUM(od.Quantity * od.SellingPrice) - SUM(od.Quantity * b.Import_price)) AS Profit
FROM orders o
JOIN order_detail od ON o.OrderID = od.OrderID
JOIN batches b ON od.Batch_ID = b.Batch_ID
GROUP BY DATE(o.OrderDate)
ORDER BY Date DESC;


-- Thống kê lợi nhuận theo tháng
SELECT 
    DATE_FORMAT(o.OrderDate, '%Y-%m') AS Month,
    SUM(od.Quantity * od.SellingPrice) AS TotalRevenue,
    SUM(od.Quantity * b.Import_price) AS TotalCOGS,
    (SUM(od.Quantity * od.SellingPrice) - SUM(od.Quantity * b.Import_price)) AS Profit
FROM orders o
JOIN order_detail od ON o.OrderID = od.OrderID
JOIN batches b ON od.Batch_ID = b.Batch_ID
GROUP BY DATE_FORMAT(o.OrderDate, '%Y-%m')
ORDER BY Month DESC;

-- 10 sản phẩm có lợi nhuận cao nhất
SELECT 
    p.ProductName,
    SUM(od.Quantity * od.SellingPrice) AS TotalRevenue,
    SUM(od.Quantity * b.Import_price) AS TotalCOGS,
    (SUM(od.Quantity * od.SellingPrice) - SUM(od.Quantity * b.Import_price)) AS Profit
FROM order_detail od
JOIN batches b ON od.Batch_ID = b.Batch_ID
JOIN products p ON b.ProductID = p.ProductID
GROUP BY p.ProductID
ORDER BY Profit DESC
LIMIT 10;



-- Kiếm tra số lương còn lại theo từng lô
SELECT 
    b.BatchID, 
    b.ProductID, 
    b.Quantity AS StockRemaining
FROM batches b
WHERE b.ProductID = 'P001' 
ORDER BY b.ExpiryDate ASC;

