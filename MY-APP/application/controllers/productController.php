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
 * @property categoryModel $categoryModel
 * @property brandModel $brandModel
 */

class productController extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}

		$this->checkLogin();
	}

	public function checkLogin()
	{
		if (!$this->session->userdata('logged_in_admin')) {
			redirect(base_url('dang-nhap'));
		}
	}


	public function index($page = 1)
	{
		$this->config->config['pageTitle'] = 'List Products';
		$this->load->model('indexModel');
		$this->load->library('pagination');

		$keyword  = $this->input->get('keyword', true);
		$status   = $this->input->get('status', true);
		$perpage  = (int) $this->input->get('perpage');
		$perpage  = $perpage > 0 ? $perpage : 1;

		$total_products = $this->indexModel->countAllProduct($keyword, $status);

		$page  = (int)$page;
		$page  = ($page > 0) ? $page : 1;
		$max_page = ceil($total_products / $perpage);
		if ($page > $max_page && $total_products > 0) {
			$query = http_build_query($this->input->get());
			redirect(base_url('product/list') . ($query ? '?' . $query : ''));
		}

		$start = ($page - 1) * $perpage;


		$data['products'] = $this->indexModel->getProductPagination($perpage, $start, $keyword, $status);
		$data['links'] = init_pagination(base_url('product/list'), $total_products, $perpage, 3);


		$data['keyword'] = $keyword;
		$data['status'] = $status;
		$data['perpage'] = $perpage;
		$data['title'] = "Danh sách sản phẩm";
		$data['breadcrumb'] = [
			['label' => 'Dashboard', 'url' => 'dashboard'],
			['label' => 'Danh sách sản phẩm']
		];
		$data['start'] = $start;
		$data['template'] = "product/index";
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
		$data['title'] = "Thêm mới sản phẩm";
		$data['breadcrumb'] = [
			['label' => 'Dashboard', 'url' => 'dashboard'],
			['label' => 'Thêm mới sản phẩm']
		];
		$data['template'] = "product/storeProduct";
		$this->load->view("admin-layout/admin-layout", $data);
	}

	public function storeProduct()
	{
		$this->form_validation->set_rules('Name', 'Name', 'trim|required', ['required' => 'Bạn cần diền %s']);
		$this->form_validation->set_rules('Slug', 'Slug', 'trim|required', ['required' => 'Bạn cần diền %s']);
		$this->form_validation->set_rules('Product_Code', 'Product_Code', 'trim|required', ['required' => 'Bạn cần diền %s']);
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
					'Product_Code' => $this->input->post('Product_Code'),
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

		$data['title'] = "Chỉnh sửa sản phẩm";
		$data['breadcrumb'] = [
			['label' => 'Dashboard', 'url' => 'dashboard'],
			['label' => 'Danh sách sản phẩm', 'url' => 'product/list'],
			['label' => 'Chỉnh sửa sản phẩm']
		];
		$data['template'] = "product/editProduct";
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
			$this->editProduct($ProductID);
		}
	}
	// public function deleteProduct($id)
	// {
	// 	$this->load->model('productModel');
	// 	$result = $this->productModel->deleteProduct($id);

	// 	if ($result['status']) {
	// 		$this->session->set_flashdata('success', $result['message']);
	// 	} else {
	// 		$this->session->set_flashdata('error', $result['message']);
	// 	}

	// 	redirect(base_url('product/list'));
	// }


}
