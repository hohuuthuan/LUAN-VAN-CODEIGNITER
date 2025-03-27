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
 * @property customerModel $customerModel
 * @property upload $upload
 * @property data $data
 */

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


	public function editCustomer($UserID)
	{
		$this->config->config['pageTitle'] = 'Edit Customer';
		$this->load->model('customerModel');
		$data['customers'] = $this->customerModel->selectCustomerById($UserID);
		$data['template'] = "manage-customer/editCustomer";
		$data['title'] = "Chỉnh sửa người dùng";
		$this->load->view("admin-layout/admin-layout", $data);
	}


	public function updateCustomer($UserID)
	{
		$this->form_validation->set_rules('Name', 'Username', 'trim|required', ['required' => 'Bạn cần diền %s']);
		$this->form_validation->set_rules('Email', 'Email', 'trim|required', ['required' => 'Bạn cần điền %s']);
		$this->form_validation->set_rules('Phone', 'Phone', 'trim|required', ['required' => 'Bạn cần diền %s']);
		$this->form_validation->set_rules('Address', 'Address', 'trim|required', ['required' => 'Bạn cần điền %s']);


		if ($this->form_validation->run()) {

			if (!empty($_FILES['Avatar']['name'])) {

				$ori_filename = $_FILES['Avatar']['name'];
				$new_name = time() . "" . str_replace(' ', '-', $ori_filename);
			
				$config = [
					'upload_path' => './uploads/user',
					'allowed_types' => 'gif|jpg|png|jpeg',
					'file_name' => $new_name
				];
				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('Avatar')) {
					$this->data['error'] = $this->upload->display_errors();
					$this->data['template'] = "manage-customer/editCustomer";
					$this->data['title'] = "Chỉnh sửa người dùng";
					$this->load->view("admin-layout/admin-layout", $this->data);
				} else {
					$avatar_filename = $this->upload->data('file_name');
					$data = [
						'Name' => $this->input->post('Name'),
						'Email' => $this->input->post('Email'),
						'Phone' => $this->input->post('Phone'),
						'Address' => $this->input->post('Address'),
						'Avatar' => $avatar_filename,
						'Status' => $this->input->post('Status'),
					];
				}
			} else {
				$data = [
					'Name' => $this->input->post('Name'),
					'Email' => $this->input->post('Email'),
					'Phone' => $this->input->post('Phone'),
					'Address' => $this->input->post('Address'),
					'Status' => $this->input->post('Status'),
				];
			}
			$this->load->model('customerModel');
			$this->customerModel->updateCustomer($UserID, $data);
			$this->session->set_flashdata('success', 'Đã chỉnh sửa trạng thái khách hàng thành công');
			redirect(base_url('customer/list'));
		} else {
			$this->editCustomer($UserID);
		}
	}

	public function deleteCustomer($UserID)
	{
		$this->load->model('customerModel');
		$this->customerModel->deleteCustomer($UserID);
		$this->session->set_flashdata('success', 'Đã xoá người dùng thành công');
		redirect(base_url('category/list'));
	}

}
