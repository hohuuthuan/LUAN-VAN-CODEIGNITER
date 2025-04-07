<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property session $session
 * @property config $config
 * @property form_validation $form_validation
 * @property input $input
 * @property load $load
 * @property model $model
 * @property warehouseModel $warehouseModel
 * @property indexModel $indexModel
 * @property productModel $productModel
 * @property pagination $pagination
 * @property uri $uri
 * @property pagination $pagination
 * @property brandModel $brandModel
 * @property categoryModel $categoryModel
 * @property upload $upload
 * @property revenueModel $revenueModel
 */


class dashboardController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->checkLogin();
    }


    private function checkLogin()
    {
        if (!$this->session->userdata('logged_in_admin')) {
            $this->session->set_flashdata('error', 'Bạn cần đăng nhập với tài khoản quản trị để sử dụng chức năng này.');
            redirect(base_url('dang-nhap'));
        }
    }
    public function logout()
    {
        if (!empty($this->session->userdata('logged_in_admin'))) {
            $this->session->unset_userdata('logged_in_admin');
        }

        $this->session->set_flashdata('success', 'Đăng xuất Admin thành công');
        redirect(base_url('/'));
    }
    public function checkSessions()
    {
        if (!empty($this->session->userdata('logged_in_admin'))) {
            echo "admin";
        }
    }



    public function index()
    {
        $this->config->config['pageTitle'] = 'Dashboard';

        // Load model
        $this->load->model('revenueModel');
        $currentYear = Carbon\Carbon::now()->year;
        $startMonth = Carbon\Carbon::create($currentYear, 1, 1)->format('Y-m'); 
        $endMonth = Carbon\Carbon::create($currentYear, 12, 1)->format('Y-m');
        $data['profits'] = $this->revenueModel->getProfitByMonthRange($startMonth, $endMonth);
        $data['timeType'] = 'month';
        $data['todayRevenue'] = $this->revenueModel->getTodayRevenue();
        $data['todayProfit'] = $this->revenueModel->getTodayProfit();
        $data['todayNewOrders'] = $this->revenueModel->getTodayNewOrders();
        $data['todayNewUsers'] = $this->revenueModel->getTodayNewUsers();

        


        // Load view
        $data['template'] = "dashboard/adminHomePage";
        $this->load->view("admin-layout/admin-layout", $data);
    }



    public function list_comment()
    {
        $this->config->config['pageTitle'] = 'List Comments';
        $this->load->model('indexModel');
        $data['comments'] = $this->indexModel->getAllComment();
        // echo '</pre>';
        // print_r($data['comments']);
        // echo '</pre>';
        $data["title"] = "Danh sách bình luận";
        $data['breadcrumb'] = [
			['label' => 'Dashboard', 'url' => 'dashboard'],
			['label' => 'Danh sách bình luận']
		];
        $data["template"] = "comment/index";
        $this->load->view("admin-layout/admin-layout", $data);
    }

    public function deleteComment($cmt_id)
    {
        $this->load->model('indexModel');

        $del_comment_details = $this->indexModel->deleteComment($cmt_id);
        if ($del_comment_details) {
            $this->session->set_flashdata('success', 'Xóa bình luận thành công');
            redirect(base_url('comment'));
        } else {
            $this->session->set_flashdata('error', 'Xóa bình luận thất bại');
            redirect(base_url('comment'));
        }
    }

    public function editComment($cmt_id)
    {
        $this->config->config['pageTitle'] = 'Edit Customer';
        $this->load->model('indexModel');
        $data['comments'] = $this->indexModel->selectCommentById($cmt_id);
        $data["title"] = "Chỉnh sửa bình luận";
        $data['breadcrumb'] = [
			['label' => 'Dashboard', 'url' => 'dashboard'],
			['label' => 'Danh sách bình luận', 'url' => 'comment'],
            ['label' => 'Cập nhật trạng thái bình luận']
		];
        $data["template"] = "comment/editComment";
        $this->load->view("admin-layout/admin-layout", $data);
    }


    public function updateComment($cmt_id)
    {
        $data = [
            'comment' => $this->input->post('comment'),
            'status' => $this->input->post('status'),
        ];
        $this->load->model('indexModel');
        $this->indexModel->updateComment($cmt_id, $data);
        $this->session->set_flashdata('success', 'Đã chỉnh sửa bình luận thành công');
        redirect(base_url('comment'));
    }
}
