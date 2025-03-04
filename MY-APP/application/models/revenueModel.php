<?php

class revenueModel extends CI_Model
{
    // Lấy doanh thu theo ngày (ngày hiện tại)
    public function getRevenueByDay()
    {
        $this->db->select('DATE(date_delivered) as date, SUM(subtotal) as total');
        $this->db->where('DATE(date_delivered)', date('Y-m-d')); // Lọc theo ngày hiện tại
        $this->db->group_by('DATE(date_delivered)');
        $query = $this->db->get('revenue');

        return $query->result() ?: []; // Trả về mảng rỗng nếu không có dữ liệu
    }

    // Lấy doanh thu theo tháng hiện tại
    public function getRevenueByMonth()
    {
        $this->db->select('YEAR(date_delivered) as year, MONTH(date_delivered) as month, SUM(subtotal) as total');
        $this->db->where('MONTH(date_delivered)', date('m')); // Lọc theo tháng hiện tại
        $this->db->where('YEAR(date_delivered)', date('Y')); // Lọc theo năm hiện tại
        $this->db->group_by(['YEAR(date_delivered)', 'MONTH(date_delivered)']);
        $query = $this->db->get('revenue');

        return $query->result() ?: []; // Trả về mảng rỗng nếu không có dữ liệu
    }

    // Lấy doanh thu theo năm tùy chỉnh
    public function getRevenueByCustomYear($year)
    {
        $this->db->select('YEAR(date_delivered) as year, SUM(subtotal) as total');
        $this->db->where('YEAR(date_delivered)', $year);
        $this->db->group_by('YEAR(date_delivered)');
        $query = $this->db->get('revenue');

        return $query->result() ?: []; 
    }

    // Lấy doanh thu cho một ngày cụ thể
    public function getRevenueByCustomDate($date)
    {
        $this->db->select('DATE(date_delivered) as date, SUM(subtotal) as total');
        $this->db->where('DATE(date_delivered)', $date); // Lọc theo ngày cụ thể
        $this->db->group_by('DATE(date_delivered)');
        $query = $this->db->get('revenue');

        return $query->result() ?: [];
    }

    // Lấy doanh thu theo tháng tùy chỉnh (tháng và năm cụ thể)
    public function getRevenueByCustomMonth($custom_month)
    {
        $this->db->select('YEAR(date_delivered) as year, MONTH(date_delivered) as month, SUM(subtotal) as total');
        $this->db->where('DATE_FORMAT(date_delivered, "%Y-%m") =', $custom_month);
        $this->db->group_by('YEAR(date_delivered), MONTH(date_delivered)');
        $query = $this->db->get('revenue');
        return $query->result();
    }
    
}
?>
