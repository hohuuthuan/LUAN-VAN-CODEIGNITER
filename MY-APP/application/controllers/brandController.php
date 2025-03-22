<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	class brandController extends CI_Controller {



		private function checkLogin()
		{
			if(!$this->session->userdata('logged_in_admin')){
				$this->session->set_flashdata('error', 'Bạn cần đăng nhập với tài khoản quản trị để sử dụng chức năng này.');
				redirect(base_url('dang-nhap'));
			}
		}

		public function index()
		{


			// $this->output->delete_cache('order_admin/listOrder');
			$this->checkLogin();
			$this->config->config['pageTitle'] = 'List Brand';
			$this->load->model('brandModel');
			$data['brand'] = $this->brandModel->selectBrand();
			$data['template'] = "brand/index";
			$data['title'] = "Danh sách thương hiệu";
			$this->load->view("admin-layout/admin-layout", $data);
    
			
		}

        public function createBrand()
		{
			$this->config->config['pageTitle'] = 'Create Brand';
			$this->load->model('brandModel');
			$data['brand'] = $this->brandModel->selectBrand();
			$data['template'] = "brand/storeBrand";
			$data['title'] = "Thêm mới thương hiệu";
			$this->load->view("admin-layout/admin-layout", $data);
		}
		


		public function storeBrand(){
			$this->form_validation->set_rules('Name', 'Name', 'trim|required', ['required' => 'Bạn cần diền %s']);
			$this->form_validation->set_rules('Description', 'Description', 'trim|required', ['required' => 'Bạn cần điền %s']);
			$this->form_validation->set_rules('Slug', 'Slug', 'trim|required', ['required' => 'Bạn cần chọn %s']);
			

			if ($this->form_validation->run()) {

				$ori_filename = $_FILES['Image']['name'];
				$new_name = time()."".str_replace(' ', '-', $ori_filename);
				$config = [
					'upload_path' => './uploads/brand',
					'allowed_types' => 'gif|jpg|png|jpeg',
					'file_name' => $new_name
				];
				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('Image')) {
					$data['error'] = $this->upload->display_errors();
					$data['template'] = "brand/storeBrand";
					$data['title'] = "Thêm mới thương hiệu";
					$this->load->view("admin-layout/admin-layout", $data);
				}else{
					$brand_filename = $this->upload->data('file_name');
					$data = [
						'Name' => $this->input->post('Name'),
						'Slug' => $this->input->post('Slug'),
						'Description' => $this->input->post('Description'),
						'Image' => $brand_filename,
						'Status' => $this->input->post('Status'),
					];
					$this->load->model('brandModel');
					$this->brandModel->insertBrand($data);
					$this->session->set_flashdata('success', 'Đã thêm thương hiệu thành công');
					redirect(base_url('brand/list'));

				}

			}else{
				$this->session->set_flashdata('error', 'Vui lòng nhập đầy đủ thông tin');
				$this->createBrand();
			}
		}

		public function editBrand($BrandID)
		{
			$this->config->config['pageTitle'] = 'Edit Brand';
			$this->load->model('brandModel');
			$data['brand'] = $this->brandModel->selectBrandById($BrandID);
			$data['template'] = "brand/editBrand";
			$data['title'] = "Chỉnh sửa thương hiệu";
			$this->load->view("admin-layout/admin-layout", $data);
		
	
		}
		
		public function updateBrand($BrandID)
		{
			$this->form_validation->set_rules('Name', 'Name', 'trim|required', ['required' => 'Bạn cần diền %s']);
			$this->form_validation->set_rules('Description', 'Description', 'trim|required', ['required' => 'Bạn cần điền %s']);
			$this->form_validation->set_rules('Slug', 'Slug', 'trim|required', ['required' => 'Bạn cần chọn %s']);
			

			if ($this->form_validation->run()) {

				if(!empty($_FILES['Image']['name'])){
					// Upload Image
					$ori_filename = $_FILES['image']['name'];
					$new_name = time()."".str_replace(' ', '-', $ori_filename);
					$config = [
						'upload_path' => './uploads/brand',
						'allowed_types' => 'gif|jpg|png|jpeg',
						'file_name' => $new_name
					];
					$this->load->library('upload', $config);

					if (!$this->upload->do_upload('Image')) {
						$error = array('error' => $this->upload->display_errors());
						$data['template'] = "brand/createBrand";
						$data['title'] = "Thêm mới thương hiệu";
						$this->load->view("admin-layout/admin-layout", $data);
					}else{
						$brand_filename = $this->upload->data('file_name');
						$data = [
							'Name' => $this->input->post('Name'),
							'Slug' => $this->input->post('Slug'),
							'Description' => $this->input->post('Description'),
							'Image' => $brand_filename,
							'Status' => $this->input->post('Status'),
						];
					}
				}else{
					$data = [
						'Name' => $this->input->post('Name'),
						'Slug' => $this->input->post('Slug'),
						'Description' => $this->input->post('Description'),
						'Status' => $this->input->post('Status'),
					];
				}
				$this->load->model('brandModel');
				$result = $this->brandModel->updateBrand($BrandID, $data);
				if($result){
					$this->session->set_flashdata('success', 'Đã chỉnh sửa thương hiệu thành công');
				}else{
					$this->session->set_flashdata('error', 'Có lỗi xảy ra');
				}

				
				redirect(base_url('brand/list'));	
			}else{
				$this->editBrand($BrandID);
			}
		}

		public function deleteBrand($BrandID)
		{
			$this->load->model('brandModel');
			$this->load->model('productModel');

			$brandUsedInProducts = $this->brandModel->checkBrandInProducts($BrandID);
	
			if ($brandUsedInProducts) {
				$this->session->set_flashdata('error', 'Không thể xóa thương hiệu vì có sản phẩm đang sử dụng.');
			} else {
				
				if ($this->brandModel->deleteBrand($BrandID)) {
					$this->session->set_flashdata('success', 'Đã xóa thương hiệu thành công');
				} else {
					$this->session->set_flashdata('error', 'Xóa thương hiệu thất bại');
				}
			}
			redirect(base_url('brand/list'));
		}
}
