<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	class dashboardController extends CI_Controller {

		private function checkLogin()
		{
			if(!$this->session->userdata('logged_in_admin')){
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
		public function checkSessions(){
			if(!empty($this->session->userdata('logged_in_admin'))){
				echo "admin";
			}
		}
	


		public function index()
		{
			$this->checkLogin();
			$this->config->config['pageTitle'] = 'Dashboard';
			$data['template'] = "admin-layout/component-admin/pageHome";
			$this->load->view("admin-layout/admin-layout", $data);
           
		}
		public function revenue()
		{
			$this->config->config['pageTitle'] = 'Revenue';
			$this->load->model('revenueModel');
			$data['daily_revenue'] = $this->revenueModel->getRevenueByDay();
			$data['monthly_revenue'] = $this->revenueModel->getRevenueByMonth();
			$data['yearly_revenue'] = $this->revenueModel->getRevenueByYear();
			$this->load->view('revenue_view', $data);
		}

			
}
