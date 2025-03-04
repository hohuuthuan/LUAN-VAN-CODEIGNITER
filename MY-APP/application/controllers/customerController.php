<?php
defined('BASEPATH') or exit('No direct script access allowed');
class customerController extends CI_Controller
{

	public function checkLogin()
	{
		if (!$this->session->userdata('logged_in-admin')) {
			redirect(base_url('dang-nhap'));
		}
	}

	public function index()
	{
		$this->config->config['pageTitle'] = 'List Customers';
		$this->load->model('customerModel');
		$data['customers'] = $this->customerModel->selectCustomer();
		$data['template'] = "manage-customer/index";
		$data['title'] = "Danh sách người dùng";
		$this->load->view("admin-layout/admin-layout", $data);
	}


	public function editCustomer($id)
	{
		$this->config->config['pageTitle'] = 'Edit Customer';
		$this->load->model('customerModel');
		$data['customers'] = $this->customerModel->selectCustomerById($id);
		$data['template'] = "manage-customer/editCustomer";
		$data['title'] = "Chỉnh sửa người dùng";
		$this->load->view("admin-layout/admin-layout", $data);
	}


	public function updateCustomer($id)
	{
		$this->form_validation->set_rules('username', 'Username', 'trim|required', ['required' => 'Bạn cần diền %s']);
		$this->form_validation->set_rules('email', 'Email', 'trim|required', ['required' => 'Bạn cần điền %s']);
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required', ['required' => 'Bạn cần diền %s']);
		$this->form_validation->set_rules('address', 'Address', 'trim|required', ['required' => 'Bạn cần chọn %s']);


		if ($this->form_validation->run()) {

			if (!empty($_FILES['image']['name'])) {
				// Upload Image
				$ori_filename = $_FILES['image']['name'];
				$new_name = time() . "" . str_replace(' ', '-', $ori_filename);
				$config = [
					'upload_path' => './uploads/user',
					'allowed_types' => 'gif|jpg|png|jpeg',
					'file_name' => $new_name
				];
				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('image')) {
					$data['error'] = $this->upload->display_errors();
					$data['template'] = "manage-customer/editCustomer";
					$data['title'] = "Chỉnh sửa người dùng";
					$this->load->view("admin-layout/admin-layout", $data);
				} else {
					$avatar_filename = $this->upload->data('file_name');
					$data = [
						'username' => $this->input->post('username'),
						'email' => $this->input->post('email'),
						'phone' => $this->input->post('phone'),
						'address' => $this->input->post('address'),
						'avatar' => $avatar_filename,
						'status' => $this->input->post('status'),
					];
				}
			} else {
				$data = [
					'username' => $this->input->post('username'),
					'email' => $this->input->post('email'),
					'phone' => $this->input->post('phone'),
					'address' => $this->input->post('address'),
					'status' => $this->input->post('status'),
				];
			}
			$this->load->model('customerModel');
			$this->customerModel->updateCustomer($id, $data);
			$this->session->set_flashdata('success', 'Đã chỉnh sửa trạng thái khách hàng thành công');
			redirect(base_url('customer/list'));
		} else {
			$this->editCustomer($id);
		}
	}

	public function deleteCustomer($id)
	{
		$this->load->model('customerModel');
		$this->customerModel->deleteCustomer($id);
		$this->session->set_flashdata('success', 'Đã xoá người dùng thành công');
		redirect(base_url('category/list'));
	}

}
