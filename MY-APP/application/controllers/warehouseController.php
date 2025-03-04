<?php
defined('BASEPATH') or exit('No direct script access allowed');
class warehouseController extends CI_Controller
{

	public function checkLogin()
	{
		if (!$this->session->userdata('logged_in_admin')) {
			redirect(base_url('dang-nhap'));
		}
	}





	// [tendanhmuc] => Thuốc trừ Sâu - Rầy - Nhện
	// [id] => 38
	// [title] => Selecron 500EC
	// [description] => Diệt triệt để và nhanh chóng sâu non, thành trùng và trứng. Lý tưởng làm nền phối hợp với các loại thuốc trừ sâu khác, đặc biệt là nhóm Cúc tống hợp. Mở rộng phổ phòng trị, hạ gục nhanh sâu hại, ngăn chặn dịch bộc phát, bảo vệ cây trồng tối đa. Diệt sâu kháng thuốc, tiết giảm công lao động.
	// [selling_price] => 25000
	// [unit] => Chai
	// [production_date] => 2024-12-07 00:00:00
	// [expiration_date] => 2025-12-07 00:00:00
	// [image] => 1732888897Picture5.png
	// [status] => 1
	// [category_id] => 7
	// [brand_id] => 8
	// [slug] => selecron-500ec
	// [discount] => 10
	// [product_id] => 73
	// [quantity] => 1128
	// [import_price_one_product] => 1
	// [total_import_price] => 97916100
	// [tenthuonghieu] => Công ty Cổ phần Nông dược HAI
	
	public function index()
	{
		$this->checkLogin();
		$this->config->config['pageTitle'] = 'List Products';
		$this->load->model('indexModel');

		// Tổng số sản phẩm
		$total_products = $this->indexModel->countAllProduct();

		// Cấu hình phân trang
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
	
		$data['template'] = "warehouse/index";
		$data['title'] = "Danh sách sản phẩm trong kho";
		$this->load->view("admin-layout/admin-layout", $data);

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
		$total_import_price = ($this->input->post('import_price_warehouses'))*($this->input->post('quantity_warehouses'));
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
