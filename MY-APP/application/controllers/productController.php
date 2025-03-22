<?php
defined('BASEPATH') or exit('No direct script access allowed');
class productController extends CI_Controller
{

	public function checkLogin()
	{
		if (!$this->session->userdata('logged_in_admin')) {
			redirect(base_url('dang-nhap'));
		}
	}



	public function index()
	{
		// $this->checkLogin();
		$this->config->config['pageTitle'] = 'List Products';
		$this->load->model('indexModel');

		$total_products = $this->indexModel->countAllProduct();

		$this->load->library('pagination');

		$config = array();
		$config["base_url"] = base_url() . 'product/list';
		$config['total_rows'] = $total_products; // Sử dụng số lượng sản phẩm đã được đếm
		$config["per_page"] = 10; // Số lượng sản phẩm trên mỗi trang
		$config["uri_segment"] = 3; // Vị trí của số trang trong URI
		$config['use_page_numbers'] = TRUE; // Sử dụng số trang thay vì offset
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = 'First';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = 'Last';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';

		// Khởi tạo phân trang
		$this->pagination->initialize($config);

		// Xác định trang hiện tại
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
		$start = ($page - 1) * $config['per_page'];

		// Lấy dữ liệu sản phẩm theo phân trang
		$data['products'] = $this->indexModel->getProductPagination($config['per_page'], $start);
		$data['links'] = $this->pagination->create_links();
		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';
		
		$data['template'] = "product/index";
		$data['title'] = "Danh sách sản phẩm";
		$this->load->view("admin-layout/admin-layout", $data);

	}
	


	


	public function createProduct()
	{
		$this->config->config['pageTitle'] = 'Create Product';
		// Load categories
		$this->load->model('categoryModel');
		$data['category'] = $this->categoryModel->selectCategory();
		// Load brand
		$this->load->model('brandModel');
		$data['brand'] = $this->brandModel->selectBrand();
		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';
		$data['template'] = "product/storeProduct";
		$data['title'] = "Thêm mới sản phẩm";
		$this->load->view("admin-layout/admin-layout", $data);

		
	}

	public function storeProduct()
	{
		$this->form_validation->set_rules('Name', 'Name', 'trim|required', ['required' => 'Bạn cần diền %s']);
		$this->form_validation->set_rules('Slug', 'Slug', 'trim|required', ['required' => 'Bạn cần diền %s']);
		$this->form_validation->set_rules('Description', 'Description', 'trim|required', ['required' => 'Bạn cần điền %s']);
		$this->form_validation->set_rules('Selling_price', 'Price', 'trim|required', ['required' => 'Bạn cần diền %s']);
		$this->form_validation->set_rules('Product_uses', 'Product_uses', 'trim|required', ['required' => 'Bạn cần diền %s']);
		$this->form_validation->set_rules('Unit', 'Unit', 'trim|required', ['required' => 'Bạn cần điền %s']);

		if ($this->form_validation->run()) {

			$ori_filename = $_FILES['Image']['name'];
			$new_name = time() . "" . str_replace(' ', '-', $ori_filename);
			$config = [
				'upload_path' => './uploads/product',
				'allowed_types' => 'gif|jpg|png|jpeg|webp',
				'file_name' => $new_name
			];
			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('Image')) {
				$data['error'] = $this->upload->display_errors();
				$data['template'] = "product/storeProduct";
				$data['title'] = "Thêm mới sản phẩm";
				$this->load->view("admin-layout/admin-layout", $data);
			} else {
				$product_filename = $this->upload->data('file_name');
				$data = [
					'Name' => $this->input->post('Name'),
					'Description' => $this->input->post('Description'),
					'Product_uses' => $this->input->post('Product_uses'),
					'Slug' => $this->input->post('Slug'),
					'Selling_price' => $this->input->post('Selling_price'),
					'Unit' => $this->input->post('Unit'),
					'Promotion' => $this->input->post('Promotion'),
					'Image' => $product_filename,
					'Status' => $this->input->post('Status'),
					'BrandID' => $this->input->post('BrandID'),
					'CategoryID' => $this->input->post('CategoryID'),
				];
				// echo '<pre>';
				// print_r($data);
				// echo '</pre>';

				$this->load->model('productModel');
				$this->productModel->insertProduct($data);
				$this->session->set_flashdata('success', 'Đã thêm sản phẩm thành công');
				redirect(base_url('product/list'));

			}
		} else {
			$this->session->set_flashdata('error', 'Tạo sản phẩm thất bại, Vui lòng thử lại');
			$this->createProduct();
		}
	}

	public function editProduct($ProductID)
	{
		$this->config->config['pageTitle'] = 'Edit Product';
		$this->load->model('categoryModel');
		$data['category'] = $this->categoryModel->selectCategory();
		$this->load->model('brandModel');
		$data['brand'] = $this->brandModel->selectBrand();
		$this->load->model('productModel');
		$data['product'] = $this->productModel->selectProductById($ProductID);

		// echo '<pre>';
		// print_r($data['product'] );
		// echo '</pre>';

		$data['template'] = "product/editProduct";
		$data['title'] = "Chỉnh sửa sản phẩm";
		$this->load->view("admin-layout/admin-layout", $data);


	}

	public function updateProduct($ProductID)
	{
		$this->form_validation->set_rules('Name', 'Name', 'trim|required', ['required' => 'Bạn cần diền %s']);
		$this->form_validation->set_rules('Slug', 'Slug', 'trim|required', ['required' => 'Bạn cần diền %s']);
		$this->form_validation->set_rules('Description', 'Description', 'trim|required', ['required' => 'Bạn cần điền %s']);
		$this->form_validation->set_rules('Selling_price', 'Price', 'trim|required', ['required' => 'Bạn cần diền %s']);
		$this->form_validation->set_rules('Product_uses', 'Product_uses', 'trim|required', ['required' => 'Bạn cần diền %s']);
		$this->form_validation->set_rules('Unit', 'Unit', 'trim|required', ['required' => 'Bạn cần điền %s']);


		if ($this->form_validation->run()) {

			if (!empty($_FILES['Image']['name'])) {
				// Upload Image
				$ori_filename = $_FILES['Image']['name'];
				$new_name = time() . "" . str_replace(' ', '-', $ori_filename);
				$config = [
					'upload_path' => './uploads/product',
					'allowed_types' => 'gif|jpg|png|jpeg',
					'file_name' => $new_name
				];
				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('Image')) {
					$data['error'] = $this->upload->display_errors();
					$data['template'] = "product/storeProduct";
					$data['title'] = "Chỉnh sửa sản phẩm";
					$this->load->view("admin-layout/admin-layout", $data);
				} else {
					$product_filename = $this->upload->data('file_name');
					$data = [
						'Name' => $this->input->post('Name'),
						'Description' => $this->input->post('Description'),
						'Product_uses' => $this->input->post('Product_uses'),
						'Slug' => $this->input->post('Slug'),
						'Selling_price' => $this->input->post('Selling_price'),
						'Unit' => $this->input->post('Unit'),
						'Promotion' => $this->input->post('Promotion'),
						'Image' => $product_filename,
						'Status' => $this->input->post('Status'),
						'BrandID' => $this->input->post('BrandID'),
						'CategoryID' => $this->input->post('CategoryID'),
					];
				}
			} else {
				$data = [
					'Name' => $this->input->post('Name'),
					'Description' => $this->input->post('Description'),
					'Product_uses' => $this->input->post('Product_uses'),
					'Slug' => $this->input->post('Slug'),
					'Selling_price' => $this->input->post('Selling_price'),
					'Unit' => $this->input->post('Unit'),
					'Promotion' => $this->input->post('Promotion'),
					'Status' => $this->input->post('Status'),
					'BrandID' => $this->input->post('BrandID'),
					'CategoryID' => $this->input->post('CategoryID'),
				];
			}
			$this->load->model('productModel');
			$this->productModel->updateProduct($ProductID, $data);
			$this->session->set_flashdata('success', 'Đã chỉnh sửa sản phẩm thành công');
			redirect(base_url('product/list'));
		} else {
			$this->editProduct($id);
		}
	}
	public function deleteProduct($id)
	{
		$this->load->model('productModel');
		$result = $this->productModel->deleteProduct($id);

		if ($result['status']) {
			$this->session->set_flashdata('success', $result['message']);
		} else {
			$this->session->set_flashdata('error', $result['message']);
		}

		redirect(base_url('product/list'));
	}


}
