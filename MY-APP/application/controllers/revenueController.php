 <?php
    defined('BASEPATH') or exit('No direct script access allowed');

    /**
     * @property session $session
     * @property config $config
     * @property form_validation $form_validation
     * @property input $input
     * @property load $load
     * @property data $data
     * @property indexModel $indexModel
     * @property pagination $pagination
     * @property uri $uri
     * @property sliderModel $sliderModel
     * @property email $email
     * @property cart $cart
     * @property orderModel $orderModel
     * @property productModel $productModel
     * @property page $page
     * @property customerModel $customerModel
     * @property loginModel $loginModel
     * @property upload $upload
     * @property revenueModel $revenueModel
     * @property getRevenueToday $getRevenueToday
     * @property getRevenueThisMonth $getRevenueThisMonth
     * @property getRevenueThisYear $getRevenueThisYear
     */


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
        private function _isValidDate($date)
        {
            $format = 'Y-m-d';
            $d = DateTime::createFromFormat($format, $date);
            return $d && $d->format($format) === $date;
        }

        private function _loadRevenueReportPage($extraData = [])
        {
            // Dữ liệu mặc định
            $data = [
                "template" => "revenue/revenueReport",
                "title" => "Thống kê doanh thu"
            ];

            // Hợp nhất dữ liệu bổ sung
            $data = array_merge($data, $extraData);

            // Tải view
            $this->load->view("admin-layout/admin-layout", $data);
        }

        // public function index()
        // {
        //     $this->config->config['pageTitle'] = 'Revenue';
        //     $data = [];

        //     // Lấy loại thời gian từ form
        //     $timeType = $this->input->get('time_type');

        //     // Lấy giá trị input dựa trên loại thời gian
        //     if ($timeType === 'day') {
        //         $startDate = $this->input->get('start_date');
        //         $endDate = $this->input->get('end_date');

        //         if ($startDate && $endDate) {
        //             if ($this->_isValidDate($startDate) && $this->_isValidDate($endDate)) {
        //                 $data['profits'] = $this->revenueModel->getProfitByDateRange($startDate, $endDate);
        //             } else {
        //                 $data['profits'] = ['error' => 'Invalid date format. Please use YYYY-MM-DD.'];
        //             }
        //         }
        //     } elseif ($timeType === 'month') {
        //         $startMonth = $this->input->get('start_month');
        //         $endMonth = $this->input->get('end_month');

        //         if ($startMonth && $endMonth) {
        //             if ($this->_isValidMonth($startMonth) && $this->_isValidMonth($endMonth)) {
        //                 $data['profits'] = $this->revenueModel->getProfitByMonthRange($startMonth, $endMonth);
        //             } else {
        //                 $data['profits'] = ['error' => 'Invalid month format. Please use YYYY-MM.'];
        //             }
        //         }
        //     } elseif ($timeType === 'year') {
        //         $startYear = $this->input->get('start_year');
        //         $endYear = $this->input->get('end_year');

        //         if ($startYear && $endYear) {
        //             if ($this->_isValidYear($startYear) && $this->_isValidYear($endYear)) {
        //                 $data['profits'] = $this->revenueModel->getProfitByYearRange($startYear, $endYear);
        //             } else {
        //                 $data['profits'] = ['error' => 'Invalid year format. Please use YYYY.'];
        //             }
        //         }
        //     }

        //     $this->_loadRevenueReportPage($data);
        // }



        public function index()
        {
            $this->config->config['pageTitle'] = 'Revenue';
            $data = [];

            // Lấy loại thời gian từ form
            $timeType = $this->input->get('time_type');

            // Lấy giá trị input dựa trên loại thời gian
            if ($timeType === 'day') {
                $startDate = $this->input->get('start_date');
                $endDate = $this->input->get('end_date');

                if ($startDate && $endDate) {
                    if ($this->_isValidDate($startDate) && $this->_isValidDate($endDate)) {
                        $data['profits'] = $this->revenueModel->getProfitByDateRange($startDate, $endDate);
                        $data['timeType'] = 'day'; // Gửi loại thời gian sang view
                    } else {
                        $data['profits'] = ['error' => 'Invalid date format. Please use YYYY-MM-DD.'];
                    }
                }
            } elseif ($timeType === 'month') {
                $startMonth = $this->input->get('start_month');
                $endMonth = $this->input->get('end_month');

                if ($startMonth && $endMonth) {
                    if ($this->_isValidMonth($startMonth) && $this->_isValidMonth($endMonth)) {
                        $data['profits'] = $this->revenueModel->getProfitByMonthRange($startMonth, $endMonth);
                        $data['timeType'] = 'month'; // Gửi loại thời gian sang view
                    } else {
                        $data['profits'] = ['error' => 'Invalid month format. Please use YYYY-MM.'];
                    }
                }
            } elseif ($timeType === 'year') {
                $startYear = $this->input->get('start_year');
                $endYear = $this->input->get('end_year');

                if ($startYear && $endYear) {
                    if ($this->_isValidYear($startYear) && $this->_isValidYear($endYear)) {
                        $data['profits'] = $this->revenueModel->getProfitByYearRange($startYear, $endYear);
                        $data['timeType'] = 'year'; // Gửi loại thời gian sang view
                    } else {
                        $data['profits'] = ['error' => 'Invalid year format. Please use YYYY.'];
                    }
                }
            }

            $this->_loadRevenueReportPage($data);
        }
        // Hàm kiểm tra định dạng tháng
        private function _isValidMonth($month)
        {
            return preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $month);
        }

        // Hàm kiểm tra định dạng năm
        private function _isValidYear($year)
        {
            return preg_match('/^\d{4}$/', $year);
        }


        public function testMonth()
        {
            $startMonth = '2025-01';
            $endMonth = '2025-03';

            $result = $this->revenueModel->getProfitByMonthRange($startMonth, $endMonth);
            echo "<pre>";
            print_r($result);
            echo "</pre>";
        }
        public function testYear()
        {
            $startYear = 2023; // Năm bắt đầu
            $endYear = 2025;   // Năm kết thúc

            $result = $this->revenueModel->getProfitByYearRange($startYear, $endYear);

            echo "<pre>";
            print_r($result);
            echo "</pre>";
        }



        public function batches()
        {
            $this->config->config['pageTitle'] = 'Thống kê lô hàng';
            $data = [];
            $data['batches'] = $this->revenueModel->getBatchProfitStatus();
            $data['template'] = "revenue/batches";
            $this->load->view("admin-layout/admin-layout", $data);
        }















































        // Lợi nhuận toàn hệ thống
        public function totalProfit()
        {
            $profit = $this->revenueModel->getProfit();
            echo "Tổng lợi nhuận toàn hệ thống: " . number_format($profit) . " VND";
        }

        // // Lợi nhuận trong khoảng thời gian
        public function profitByDateRange()
        {
            $startDate = '2025-03-01 00:00:00';
            $endDate = '2025-03-25 23:59:59';
            $profit = $this->revenueModel->getProfit($startDate, $endDate);
            echo "Lợi nhuận từ $startDate đến $endDate: " . number_format($profit) . " VND";
        }

        // DOANH THU CỦA NGÀY HÔM NAY
        public function profitToday()
        {
            $startDate = date('Y-m-d 00:00:00');
            $endDate = date('Y-m-d 23:59:59');
            $profit = $this->revenueModel->getProfit($startDate, $endDate);
            echo "Doanh thu ngày hôm nay: " . number_format($profit) . " VND";
        }

        // lỢI NHUẬN CỦA NĂM NAY
        public function profitThisYear()
        {
            $startDate = date('Y-01-01');
            $endDate = date('Y-12-31');
            $profit = $this->revenueModel->getProfit($startDate, $endDate);
            echo "Lợi nhuận của năm nay: " . number_format($profit) . " VND";
        }


        // LỢI NHUẬN CỦA TOÀN BỘ HỆ THỐNG
        public function showOrderProfit()
        {
            $profits = $this->revenueModel->getOrderProfit();

            echo "<pre>";
            print_r($profits);
            echo "</pre>";
            die();

            foreach ($profits as $profit) {
                echo "Order Code: " . $profit->Order_Code . "<br>";
                echo "Total Revenue: " . number_format($profit->Total_Revenue) . " VND<br>";
                echo "Total Cost: " . number_format($profit->Total_Cost) . " VND<br>";
                echo "Total Profit: " . number_format($profit->Total_Profit) . " VND<br><br>";
            }
        }



        // LỢI NHUẬN TRÊN TỪNG LÔ HÀNG
        public function batchProfitStatus()
        {
            $this->load->model('revenueModel'); // Tải model
            $data['batches'] = $this->revenueModel->getBatchProfitStatus();
            echo "<pre>";
            print_r($data['batches']);
            echo "</pre>";
            die();

            // $data["template"] = "revenue/batch_profit_status";
            // $data["title"] = "Thống kê lợi nhuận từng lô hàng";
            // $this->load->view("admin-layout/admin-layout", $data);
        }
    }





    // public function index()
    // {
    //     $this->config->config['pageTitle'] = 'Revenue';

    //     // Lấy thống kê doanh thu
    //     $time_now = Carbon\Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
    //     $data['revenue_today'] = $this->revenueModel->getRevenueByDate($time_now);
    //     $data['revenue_month'] = $this->revenueModel->getRevenueThisMonth();
    //     $data['revenue_year'] = $this->revenueModel->getRevenueThisYear();

    //     // echo "<pre>";
    //     // print_r($data['revenue_today']);
    //     // echo "</pre>";
    //     // die();

    //     if ($this->input->get('date')) {
    //         $data['revenue_by_date'] = $this->revenueModel->getRevenueByDate(
    //             $this->input->get('date')
    //         );
    //         // echo "<pre>";
    //         // print_r($this->input->get('date'));
    //         // echo "</pre>";
    //         // die();
    //     }

    //     // Lấy thống kê theo khoảng thời gian (nếu có dữ liệu từ form gửi lên)
    //     if ($this->input->get('start_date') && $this->input->get('end_date')) {
    //         $data['revenue_by_date_range'] = $this->revenueModel->getRevenueByDateRange(
    //             $this->input->get('start_date'),
    //             $this->input->get('end_date')
    //         );
    //     }

    //     if ($this->input->get('start_month') && $this->input->get('end_month')) {
    //         $data['revenue_by_month_range'] = $this->revenueModel->getRevenueByMonthRange(
    //             $this->input->get('start_month'),
    //             $this->input->get('end_month')
    //         );
    //     }

    //     if ($this->input->get('start_year') && $this->input->get('end_year')) {
    //         $data['revenue_by_year_range'] = $this->revenueModel->getRevenueByYearRange(
    //             $this->input->get('start_year'),
    //             $this->input->get('end_year')
    //         );
    //     }

    //     $data["template"] = "revenue/index";
    //     $data["title"] = "Thống kê doanh thu";
    //     $this->load->view("admin-layout/admin-layout", $data);
    // }
    ?>