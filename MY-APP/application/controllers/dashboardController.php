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




		
	public function list_comment()
	{
		$this->config->config['pageTitle'] = 'List Comments';
		$this->load->model('indexModel');
		$data['comments'] = $this->indexModel->getAllComment();
		// echo '</pre>';
		// print_r($data['comments']);
		// echo '</pre>';
		$data["template"] = "comment/index";
		$data["title"] = "Danh sách bình luận";
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
		$data["template"] = "comment/editComment";
		$data["title"] = "Chỉnh sửa bình luận";
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
