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
			$this->form_validation->set_rules('title', 'Title', 'trim|required', ['required' => 'Bạn cần diền %s']);
			$this->form_validation->set_rules('description', 'Description', 'trim|required', ['required' => 'Bạn cần điền %s']);
			$this->form_validation->set_rules('slug', 'Slug', 'trim|required', ['required' => 'Bạn cần chọn %s']);
			

			if ($this->form_validation->run()) {

				$ori_filename = $_FILES['image']['name'];
				$new_name = time()."".str_replace(' ', '-', $ori_filename);
				$config = [
					'upload_path' => './uploads/brand',
					'allowed_types' => 'gif|jpg|png|jpeg',
					'file_name' => $new_name
				];
				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('image')) {
					$data['error'] = $this->upload->display_errors();
					$data['brand'] = $this->brandModel->selectBrand();
					$data['template'] = "brand/storeBrand";
					$data['title'] = "Thêm mới thương hiệu";
					$this->load->view("admin-layout/admin-layout", $data);
				}else{
					$brand_filename = $this->upload->data('file_name');
					$data = [
						'title' => $this->input->post('title'),
						'slug' => $this->input->post('slug'),
						'description' => $this->input->post('description'),
						'image' => $brand_filename,
						'status' => $this->input->post('status'),
					];
					$this->load->model('brandModel');
					$this->brandModel->insertBrand($data);
					$this->session->set_flashdata('success', 'Đã thêm thương hiệu thành công');
					redirect(base_url('brand/list'));

				}

			}else{
				$this->createBrand();
			}
		}

		public function editBrand($id)
		{
			$this->config->config['pageTitle'] = 'Edit Brand';
			$this->load->model('brandModel');
			$data['brand'] = $this->brandModel->selectBrandById($id);
			$data['template'] = "brand/editBrand";
			$data['title'] = "Thêm mới thương hiệu";
			$this->load->view("admin-layout/admin-layout", $data);
		
	
		}
		
		public function updateBrand($id)
		{
			$this->form_validation->set_rules('title', 'Title', 'trim|required', ['required' => 'Bạn cần diền %s']);
			$this->form_validation->set_rules('description', 'Description', 'trim|required', ['required' => 'Bạn cần điền %s']);
			$this->form_validation->set_rules('slug', 'Slug', 'trim|required', ['required' => 'Bạn cần chọn %s']);
			

			if ($this->form_validation->run()) {

				if(!empty($_FILES['image']['name'])){
					// Upload Image
					$ori_filename = $_FILES['image']['name'];
					$new_name = time()."".str_replace(' ', '-', $ori_filename);
					$config = [
						'upload_path' => './uploads/brand',
						'allowed_types' => 'gif|jpg|png|jpeg',
						'file_name' => $new_name
					];
					$this->load->library('upload', $config);

					if (!$this->upload->do_upload('image')) {
						$error = array('error' => $this->upload->display_errors());
						$data['brand'] = $this->brandModel->selectBrand();
						$data['template'] = "brand/createBrand";
						$data['title'] = "Thêm mới thương hiệu";
						$this->load->view("admin-layout/admin-layout", $data, $error);
					}else{
						$brand_filename = $this->upload->data('file_name');
						$data = [
							'title' => $this->input->post('title'),
							'slug' => $this->input->post('slug'),
							'description' => $this->input->post('description'),
							'image' => $brand_filename,
							'status' => $this->input->post('status'),
						];
					}
				}else{
					$data = [
						'title' => $this->input->post('title'),
						'slug' => $this->input->post('slug'),
						'description' => $this->input->post('description'),
						'status' => $this->input->post('status'),
					];
				}
				$this->load->model('brandModel');
				$this->brandModel->updateBrand($id, $data);
				$this->session->set_flashdata('success', 'Đã chỉnh sửa thương hiệu thành công');
				redirect(base_url('brand/list'));	
			}else{
				$this->editBrand($id);
			}
		}

		public function deleteBrand($id)
		{
			$this->load->model('brandModel');
			$this->load->model('productModel');
	
		
			$brandUsedInProducts = $this->brandModel->checkBrandInProducts($id);
	
			if ($brandUsedInProducts) {
		
				$this->session->set_flashdata('error', 'Không thể xóa thương hiệu vì có sản phẩm đang sử dụng.');
			} else {
				
				if ($this->brandModel->deleteBrand($id)) {
					$this->session->set_flashdata('success', 'Đã xóa thương hiệu thành công');
				} else {
					$this->session->set_flashdata('error', 'Xóa thương hiệu thất bại');
				}
			}
	
			redirect(base_url('brand/list'));
		}
}
