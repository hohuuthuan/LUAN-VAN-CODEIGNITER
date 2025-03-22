<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	class categoryController extends CI_Controller {

		public function checkLogin()
		{
			if(!$this->session->userdata('logged_in_admin')){
				redirect(base_url('dang-mhap'));
			}
		}
		public function index()
		{
			$this->config->config['pageTitle'] = 'List Categories';
            $this->load->model('categoryModel');
			$data['category'] = $this->categoryModel->selectCategory();
			$data['template'] = "category/index";
			$data['title'] = "Danh sách danh mục";
			$this->load->view("admin-layout/admin-layout", $data);
		}

        public function createCategory()
		{
			$this->config->config['pageTitle'] = 'Create Category';
			$this->load->model('categoryModel');
			$data['category'] = $this->categoryModel->selectCategory();
			$data['template'] = "category/storeCategory";
			$data['title'] = "Thêm mới danh mục";
			$this->load->view("admin-layout/admin-layout", $data);
		}
		
		public function storeCategory(){
			$this->form_validation->set_rules('Name', 'Name', 'trim|required', ['required' => 'Bạn cần diền %s']);
			$this->form_validation->set_rules('Description', 'Description', 'trim|required', ['required' => 'Bạn cần điền %s']);
			$this->form_validation->set_rules('Slug', 'Slug', 'trim|required', ['required' => 'Bạn cần chọn %s']);
			

			if ($this->form_validation->run()) {

				$ori_filename = $_FILES['Image']['name'];
				$new_name = time()."".str_replace(' ', '-', $ori_filename);
				$config = [
					'upload_path' => './uploads/category',
					'allowed_types' => 'gif|jpg|png|jpeg',
					'file_name' => $new_name
				];
				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('Image')) {
					$data['error'] = $this->upload->display_errors();
					$data['template'] = "category/storeCategory";
					$data['title'] = "Thêm mới danh mục";
					$this->load->view("admin-layout/admin-layout", $data);
				}
				else{
					$category_filename = $this->upload->data('file_name');
					$data = [
						'Name' => $this->input->post('Name'),
						'Slug' => $this->input->post('Slug'),
						'Description' => $this->input->post('Description'),
						'Image' => $category_filename,
						'Status' => $this->input->post('Status'),
					];
					$this->load->model('categoryModel');
					$this->categoryModel->insertcategory($data);
					$this->session->set_flashdata('success', 'Đã thêm danh mục thành công');
					redirect(base_url('category/list'));

				}

				
			}else{
				$this->createcategory();
			}
		}

		public function editcategory($id)
		{
			$this->config->config['pageTitle'] = 'Edit Category';
			$this->load->model('categoryModel');
			$data['category'] = $this->categoryModel->selectcategoryById($id);
			$data['template'] = "category/editcategory";
			$data['title'] = "Chỉnh sửa danh mục";
			$this->load->view("admin-layout/admin-layout", $data);

			
		}
		
		public function updateCategory($CategoryID)
		{
			$this->form_validation->set_rules('Name', 'Name', 'trim|required', ['required' => 'Bạn cần diền %s']);
			$this->form_validation->set_rules('Description', 'Description', 'trim|required', ['required' => 'Bạn cần điền %s']);
			$this->form_validation->set_rules('Slug', 'Slug', 'trim|required', ['required' => 'Bạn cần chọn %s']);
			

			if ($this->form_validation->run()) {

				if(!empty($_FILES['Image']['name'])){
					// Upload Image
					$ori_filename = $_FILES['Image']['name'];
					$new_name = time()."".str_replace(' ', '-', $ori_filename);
					
					$config = [
						'upload_path' => './uploads/category',
						'allowed_types' => 'gif|jpg|png|jpeg',
						'file_name' => $new_name
					];
					$this->load->library('upload', $config);

					if (!$this->upload->do_upload('Image')) {
						$data['error'] = $this->upload->display_errors();
						$data['category'] = $this->categoryModel->selectCategory();
						$data['template'] = "category/storeCategory";
						$data['title'] = "Chỉnh sửa danh mục";
						$this->load->view("admin-layout/admin-layout", $data);
					}else{
						$category_filename = $this->upload->data('file_name');
						$data = [
							'Name' => $this->input->post('Name'),
							'Slug' => $this->input->post('Slug'),
							'Description' => $this->input->post('Description'),
							'Image' => $category_filename,
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
				$this->load->model('categoryModel');
				$this->categoryModel->updateCategory($CategoryID, $data);
				$this->session->set_flashdata('success', 'Đã chỉnh sửa danh mục thành công');
				redirect(base_url('category/list'));	
			}else{
				
				$this->editcategory($CategoryID);
			}
		}

		public function deleteCategory($CategoryID)
		{
			$this->load->model('categoryModel');
			$this->load->model('productModel');
	
			// Kiểm tra xem danh mục có sản phẩm liên kết hay không
			$categoryUsedInProducts = $this->categoryModel->checkCategoryInProducts($CategoryID);
	
			if ($categoryUsedInProducts) {
				// Nếu có sản phẩm sử dụng danh mục này, không cho phép xóa
				$this->session->set_flashdata('error', 'Không thể xóa danh mục vì có sản phẩm đang sử dụng.');
			} else {
				// Nếu không có sản phẩm nào liên kết, thực hiện xóa
				if ($this->categoryModel->deleteCategory($CategoryID)) {
					$this->session->set_flashdata('success', 'Đã xóa danh mục thành công');
				} else {
					$this->session->set_flashdata('error', 'Xóa danh mục thất bại');
				}
			}
	
			redirect(base_url('category/list'));
		}

}
