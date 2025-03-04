<?php
defined('BASEPATH') or exit('No direct script access allowed');

class revenueController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('revenueModel');
        $this->load->library('session');
    }

    public function checkLogin()
    {
        if (!$this->session->userdata('logged_in_admin')) {
            redirect(base_url('login'));
        }
    }

    public function index()
    {

        $this->config->config['pageTitle'] = 'Revenue';

        $data['daily_revenue'] = $this->revenueModel->getRevenueByDay();
        $data['monthly_revenue'] = $this->revenueModel->getRevenueByMonth();

        $data["template"] = "revenue/index";
        $data["title"] = "Thống kê doanh thu";
        $this->load->view("admin-layout/admin-layout", $data);


    }



    public function customRevenue()
    {
        $this->config->config['pageTitle'] = 'Revenue';
        $data['daily_revenue'] = $this->revenueModel->getRevenueByDay();
        $data['monthly_revenue'] = $this->revenueModel->getRevenueByMonth();
        // Lấy dữ liệu ngày, tháng, năm từ input
        $day = $this->input->post('day');
        $month = $this->input->post('month');
        $year = $this->input->post('year');

        if (!empty($day) && !empty($month) && !empty($year)) {
            $custom_date = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $result = $data['custom_revenue'] = $this->revenueModel->getRevenueByCustomDate($custom_date);
            // Hiển thị thông báo hành động 
            if ($result && $data['custom_revenue'] != []) {
                $this->session->set_flashdata('success', 'Đã thống kê thành công');
            } else {
                $this->session->set_flashdata('error', 'Không thành công, vui lòng kiểm tra lại');
            }

        } elseif (!empty($month) && !empty($year) && $day == '') {
            $custom_month = sprintf('%04d-%02d', $year, $month);
            $result = $data['custom_revenue'] = $this->revenueModel->getRevenueByCustomMonth($custom_month);
            // Hiển thị thông báo hành động 
            if ($result && $data['custom_revenue'] != []) {
                $this->session->set_flashdata('success', 'Đã thống kê thành công');
            } else {
                $this->session->set_flashdata('error', 'Không thành công, vui lòng kiểm tra lại');
            }

        } elseif (!empty($year) && $month == '' && $day == '') {
            $result = $data['custom_revenue'] = $this->revenueModel->getRevenueByCustomYear($year);
            // Hiển thị thông báo hành động 
            if ($result && $data['custom_revenue'] != []) {
                $this->session->set_flashdata('success', 'Đã thống kê thành công');
            } else {
                $this->session->set_flashdata('error', 'Không thành công, vui lòng kiểm tra lại');
            }
        } else {
            $data['custom_revenue'] = [];
            $this->session->set_flashdata('error', 'Vui lòng nhập đầy đủ thông tin ngày, tháng và năm.');
        }


        if (empty($data['custom_revenue'])) {
            $this->session->set_flashdata('error', 'Không có dữ liệu cho ngày/tháng/năm đã chọn.');
        }
        

        $data["template"] = "revenue/index";
        $data["title"] = "Thống kê doanh thu";
        $this->load->view("admin-layout/admin-layout", $data);
    }



    // Hàm kiểm tra tính hợp lệ của ngày tháng năm
    private function isValidDate($day, $month, $year)
    {

        if ($month < 1 || $month > 12) {
            return false;
        }

        if ($year < 1) {
            return false;
        }
        $daysInMonth = [31, ($this->isLeapYear($year) ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        if ($day < 1 || $day > $daysInMonth[$month - 1]) {
            return false;
        }

        return true;
    }

    private function isLeapYear($year)
    {
        return ($year % 4 === 0 && ($year % 100 !== 0 || $year % 400 === 0));
    }


}
