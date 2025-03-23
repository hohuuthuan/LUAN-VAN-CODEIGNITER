use pesticide_version2;


-- Thống kê doanh thu theo từng ngày
SELECT 
    DATE(o.Date_created) AS order_date,
    SUM(od.Subtotal) AS total_revenue
FROM 
    orders o
JOIN 
    order_detail od ON o.Order_Code = od.Order_Code
GROUP BY 
    DATE(o.Date_created)
ORDER BY 
    order_date;

-- Thống kê theo ngày cụ thể
SELECT 
    SUM(TotalAmount) AS total_revenue
FROM 
    orders
WHERE 
    Payment_Status = 1
    AND Date_created BETWEEN '2025-03-01' AND '2025-03-31';




-- Thống kê doanh thu theo tuần
SELECT 
    YEAR(o.Date_created) AS order_year,
    WEEK(o.Date_created, 1) AS order_week,
    SUM(od.Subtotal) AS total_revenue
FROM 
    orders o
JOIN 
    order_detail od ON o.Order_Code = od.Order_Code
GROUP BY 
    YEAR(o.Date_created), WEEK(o.Date_created, 1)
ORDER BY 
    order_year, order_week;
    
-- Thống kê doanh thu theo tháng
SELECT 
    YEAR(o.Date_created) AS order_year,
    MONTH(o.Date_created) AS order_month,
    SUM(od.Subtotal) AS total_revenue
FROM 
    orders o
JOIN 
    order_detail od ON o.Order_Code = od.Order_Code
GROUP BY 
    YEAR(o.Date_created), MONTH(o.Date_created)
ORDER BY 
    order_year, order_month;
    
-- Thống kê doanh thu theo năm
    SELECT 
    YEAR(o.Date_created) AS order_year,
    SUM(od.Subtotal) AS total_revenue
FROM 
    orders o
JOIN 
    order_detail od ON o.Order_Code = od.Order_Code
GROUP BY 
    YEAR(o.Date_created)
ORDER BY 
    order_year;
    

-- Thống kê theo ngày cụ thể
SELECT 
    DATE(o.COD_date_delivered) AS COD_date_delivered,
    SUM(od.Subtotal) AS total_revenue
FROM 
    orders o
JOIN 
    order_detail od ON o.Order_Code = od.Order_Code
WHERE 
	
    DATE(o.Date_created) = '2025-03-14'
GROUP BY 
    DATE(o.Date_created);
    
-- Thống kê theo tháng cụ thể
SELECT 
    YEAR(o.Date_created) AS order_year,
    MONTH(o.Date_created) AS order_month,
    SUM(od.Subtotal) AS total_revenue
FROM 
    orders o
JOIN 
    order_detail od ON o.Order_Code = od.Order_Code
WHERE 
    YEAR(o.Date_created) = 2023 AND MONTH(o.Date_created) = 3 
GROUP BY 
    YEAR(o.Date_created), MONTH(o.Date_created);
    
-- Thống kê theo năm cụ thể
SELECT 
    YEAR(o.Date_created) AS order_year,
    SUM(od.Subtotal) AS total_revenue
FROM 
    orders o
JOIN 
    order_detail od ON o.Order_Code = od.Order_Code
WHERE 
    YEAR(o.Date_created) = 2023  -- Thay đổi năm theo yêu cầu
GROUP BY 
    YEAR(o.Date_created);
    

-- Thống kê theo ngày bắt đầu và kết thúc
SELECT 
    DATE(o.Date_created) AS order_date,
    SUM(od.Subtotal) AS total_revenue
FROM 
    orders o
JOIN 
    order_detail od ON o.Order_Code = od.Order_Code
WHERE 
    DATE(o.Date_created) BETWEEN '2023-03-01' AND '2023-03-31'  -- Thay đổi ngày bắt đầu và kết thúc theo yêu cầu
GROUP BY 
    DATE(o.Date_created)
ORDER BY 
    order_date;
    
-- Thống kê theo tháng bắt đầu và kết thúc
SELECT 
    YEAR(o.Date_created) AS order_year,
    MONTH(o.Date_created) AS order_month,
    SUM(od.Subtotal) AS total_revenue
FROM 
    orders o
JOIN 
    order_detail od ON o.Order_Code = od.Order_Code
WHERE 
    o.Date_created BETWEEN '2023-01-01' AND '2025-03-31' 
GROUP BY 
    YEAR(o.Date_created), MONTH(o.Date_created)
ORDER BY 
    order_year, order_month;
    
-- Thống kê theo năm bắt đầu và năm kết thúc
SELECT 
    YEAR(o.Date_created) AS order_year,
    SUM(od.Subtotal) AS total_revenue
FROM 
    orders o
JOIN 
    order_detail od ON o.Order_Code = od.Order_Code
WHERE 
    o.Date_created BETWEEN '2021-01-01' AND '2025-12-31'
GROUP BY 
    YEAR(o.Date_created)
ORDER BY 
    order_year;

    
    
    


-- Thống kê doanh thu
SELECT 
    SUM(od.Subtotal) AS total_revenue
FROM 
    orders o
JOIN 
    order_detail od ON o.Order_Code = od.Order_Code
WHERE 
    o.Date_created BETWEEN '2025-03-01' AND '2025-03-31';  -- Thay đổi ngày theo yêu cầu
    
SELECT 
    SUM(b.Import_price * ob.quantity) AS total_cost
FROM 
    order_batches ob
JOIN 
    batches b ON ob.batch_id = b.Batch_ID
JOIN 
    order_detail od ON ob.order_detail_id = od.id
JOIN 
    orders o ON od.Order_Code = o.Order_Code
WHERE 
    o.Date_created BETWEEN '2025-03-01' AND '2025-03-31';  -- Thay đổi ngày theo yêu cầu
    

SELECT 
    (SELECT SUM(od.Subtotal) FROM orders o JOIN order_detail od ON o.Order_Code = od.Order_Code WHERE o.Date_created BETWEEN '2025-03-01' AND '2025-03-31') 
    -
    (SELECT SUM(b.Import_price * ob.quantity) FROM order_batches ob JOIN batches b ON ob.batch_id = b.Batch_ID JOIN order_detail od ON ob.order_detail_id = od.id JOIN orders o ON od.Order_Code = o.Order_Code WHERE o.Date_created BETWEEN '2025-03-01' AND '2025-03-31') AS total_profit;





-- Thống kê đơn hàng theo ngày giao hàng
SELECT 
    o.Order_Code,
    od.COD_date_delivered,
    SUM(od.Subtotal) AS total_revenue
FROM 
    orders o
JOIN 
    order_detail od ON o.Order_Code = od.Order_Code
WHERE 
    o.Payment_Status = 1
GROUP BY 
    o.Order_Code, od.COD_date_delivered
ORDER BY 
    od.COD_date_delivered;