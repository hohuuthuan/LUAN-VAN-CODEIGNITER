<?php
defined('BASEPATH') or exit('No direct script access allowed');
class warehouseController extends CI_Controller
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
		$data['products'] = $this->indexModel->getIndexPagination($config['per_page'], $start);
		$data['links'] = $this->pagination->create_links();
		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';

		$data['title'] = "Danh sách sản phẩm trong kho";
		$data['template'] = "warehouse/index";
		$this->load->view("admin-layout/admin-layout", $data);

	}


	public function receive_goods()
	{
		$this->load->model('indexModel');
		$data['allproducts'] = $this->indexModel->getAllProduct();

		// echo '<pre>';
		// print_r($data['allproducts']);
		// echo '</pre>';

		$this->config->config['pageTitle'] = 'Phiếu nhập hàng';
		$data['template'] = "warehouse/receive-goods";
		// $data['template'] = "warehouse/test-data";
		// $data['template'] = "warehouse/test2";
		$data['title'] = "Phiếu nhập kho";
		$this->load->view("admin-layout/admin-layout", $data);
	}

	public function enter_into_warehouse()
	{
		$this->form_validation->set_rules('date', 'date', 'trim|required', ['required' => 'Bạn cần chọn ngày']);
		$this->form_validation->set_rules('ho_ten_nguoi_giao', 'HoTen', 'trim|required', ['required' => 'Bạn cần nhập họ tên người giao']);
		$this->form_validation->set_rules('donvi', 'DonVi', 'trim|required', ['required' => 'Bạn cần nhập đơn vị']);
		$this->form_validation->set_rules('address', 'Diachi', 'trim|required', ['required' => 'Bạn cần nhập địa chỉ']);
		$this->form_validation->set_rules('phieu_giao_nhan_so', 'PhieuGiaoNhanSo', 'trim|required', ['required' => 'Bạn cần nhập phiếu giao nhận số']);
		$this->form_validation->set_rules('nhan_noi_bo_tu_kho', 'NhanNoiBo', 'trim|required', ['required' => 'Bạn cần nhập nhận nội bộ từ kho']);


		$products = $this->input->post('products') ?? [];
		foreach ($products as $key => $product) {
			$this->form_validation->set_rules("products[$key][name]", 'ProductName', 'trim|required', ['required' => 'Bạn cần chọn sản phẩm']);
			$this->form_validation->set_rules("products[$key][code]", 'ProductCode', 'trim|required', ['required' => 'Thiếu mã sản phẩm']);
			$this->form_validation->set_rules("products[$key][unit]", 'ProductUnit', 'trim|required', ['required' => 'Thiếu đơn vị tính']);
			$this->form_validation->set_rules("products[$key][Import_price]", 'ImportPrice', 'trim|required|numeric', [
				'required' => 'Thiếu giá nhập',
				'numeric' => 'Giá nhập phải là số'
			]);
			$this->form_validation->set_rules("products[$key][Exp_date]", 'Exp_date', 'trim|required', ['required' => 'Thiếu hạn sử dụng']);
			$this->form_validation->set_rules("products[$key][quantity_doc]", 'QuantityDoc', 'trim|required|numeric', [
				'required' => 'Thiếu số lượng',
				'numeric' => 'Số lượng phải là số'
			]);
			$this->form_validation->set_rules("products[$key][quantity_real]", 'QuantityReal', 'trim|required|numeric', [
				'required' => 'Thiếu số lượng',
				'numeric' => 'Số lượng phải là số'
			]);
		}

		if ($this->form_validation->run() == FALSE) {
			$errors = array_merge([
				"date" => form_error("date"),
				"ho_ten_nguoi_giao" => form_error("ho_ten_nguoi_giao"),
				"donvi" => form_error("donvi"),
				"address" => form_error("address"),
				"phieu_giao_nhan_so" => form_error("phieu_giao_nhan_so"),
				"nhan_noi_bo_tu_kho" => form_error("nhan_noi_bo_tu_kho")
			], array_reduce(array_keys($products), function ($carry, $key) {
				return array_merge($carry, [
					
					"products[$key][name]" => form_error("products[$key][name]"),
					"products[$key][code]" => form_error("products[$key][code]"),
					"products[$key][unit]" => form_error("products[$key][unit]"),
					"products[$key][Import_price]" => form_error("products[$key][Import_price]"),
					"products[$key][Exp_date]" => form_error("products[$key][Exp_date]"),
					"products[$key][quantity_doc]" => form_error("products[$key][quantity_doc]"),
					"products[$key][quantity_real]" => form_error("products[$key][quantity_real]")
				]);
			}, []));

			$data['errors'] = $errors;
			$data['input'] = $this->input->post();
		
			// echo "<pre>";
			// print_r($data['input']);
			// echo "</pre>";		

			$this->load->model('indexModel');
			$data['allproducts'] = $this->indexModel->getAllProduct();
			$this->config->config['pageTitle'] = 'Phiếu nhập hàng';
			// $data['template'] = "warehouse/test2";
			// $data['template'] = "warehouse/test-data";
			$data['template'] = "warehouse/receive-goods";
			$data['title'] = "Phiếu nhập kho";
			$this->load->view("admin-layout/admin-layout", $data);
		} else {

			$result = false;


			$data = [
				'date' => $this->input->post('date'),
				'ho_ten_nguoi_giao' => $this->input->post('ho_ten_nguoi_giao'),
				'donvi' => $this->input->post('donvi'),
				'phieu_giao_nhan_so' => $this->input->post('phieu_giao_nhan_so'),
				'nhan_noi_bo_tu_kho' => $this->input->post('nhan_noi_bo_tu_kho'),
				'products' => $this->input->post('products')
			];

			// echo "<pre>";
			// print_r($data);
			// echo "</pre>";

			$this->session->set_flashdata('success', 'Nhập kho thành công');
			// redirect(base_url('dashboard'));
		}
	}





	public function updateQuantityProduct($id)
	{
		$this->config->config['pageTitle'] = 'Update Quantity Product';
		$this->load->model('productModel');
		$data['product'] = $this->productModel->selectProductById($id);

		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';

		$data['template'] = "warehouse/plusQuantityInWarehouse";
		$data['title'] = "Cập nhật số lượng sản phẩm trong kho";
		$this->load->view("admin-layout/admin-layout", $data);
	}

	public function plusQuantityWarehouses($id)
	{
		$data = [
			'quantity' => $this->input->post('quantity_warehouses'),
			'import_price_one_product' => $this->input->post('import_price_warehouses'),
		];
		$total_import_price = ($this->input->post('import_price_warehouses')) * ($this->input->post('quantity_warehouses'));
		$this->load->model('productModel');

		if ($this->productModel->plusQuantityProduct($id, $data) && $this->productModel->plusTotalPriceProduct($id, $total_import_price)) {
			$this->session->set_flashdata('success', 'Đã thêm vào kho thành công');
		} else {
			$this->session->set_flashdata('error', 'Thêm vào kho thất bại');
		}
		redirect(base_url('quantity/update/' . $id));
	}

	public function deleteProduct($id)
	{
		$this->load->model('productModel');

		// Kiểm tra nếu sản phẩm liên quan đến đơn hàng
		$orders = $this->productModel->getOrdersByProductId($id);
		if (!empty($orders)) {
			$order_codes = array_column($orders, 'order_code');
			$this->session->set_flashdata(
				'error',
				'Không thể xóa sản phẩm vì đang được sử dụng trong các đơn hàng: ' . implode(', ', $order_codes)
			);
			redirect(base_url('warehouse/list')); // Quay lại danh sách kho
		}

		// Nếu không liên quan đến đơn hàng, tiến hành xóa
		if ($this->productModel->deleteProduct($id)) {
			$this->session->set_flashdata('success', 'Sản phẩm đã được xóa thành công khỏi kho');
		} else {
			$this->session->set_flashdata('error', 'Xóa sản phẩm thất bại');
		}

		redirect(base_url('warehouse/list'));
	}


}
