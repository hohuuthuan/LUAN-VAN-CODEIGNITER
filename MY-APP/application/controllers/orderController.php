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
 * @property db $db
 */

class orderController extends CI_Controller
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
			redirect(base_url('dang_nhap'));
			die();
		}
	}

	// public function index()
	// {
	// 	$this->config->config['pageTitle'] = 'List Order';
	// 	$this->load->model('orderModel');
	// 	$data['order'] = $this->orderModel->selectOrder();
	// 	// In dữ liệu để kiểm tra
	// 	// echo '<pre>';
	// 	// print_r($data['order']);
	// 	// echo '</pre>';

	// 	$data['title'] = "Danh sách đơn hàng";
	// 	$data['breadcrumb'] = [
	// 		['label' => 'Dashboard', 'url' => 'dashboard'],
	// 		['label' => 'Danh sách đơn hàng']
	// 	];
	// 	$data['template'] = "order_admin/index";
	// 	$this->load->view("admin-layout/admin-layout", $data);

	// }



	// public function index($page = 1)
	// {
	// 	$this->config->config['pageTitle'] = 'List Order';
	// 	$this->load->model('orderModel');

	// 	// Lấy filter từ GET
	// 	$keyword = $this->input->get('keyword', TRUE);
	// 	$status  = $this->input->get('status', TRUE);
	// 	$perpage = (int) $this->input->get('perpage');
	// 	$perpage = ($perpage > 0) ? $perpage : 1;

	// 	$filter = [
	// 		'keyword' => $keyword,
	// 		'status'  => $status
	// 	];

	// 	// Tổng số đơn hàng
	// 	$total = $this->orderModel->countOrder($filter);

	// 	// Cấu hình phân trang
	// 	$this->load->library('pagination');
	// 	// Cấu hình phân trang
	// 	$config['base_url'] = base_url('order_admin/listOrder');
	// 	$config['total_rows'] = $total;
	// 	$config['per_page'] = $perpage;
	// 	$config['uri_segment'] = 3;
	// 	$config['use_page_numbers'] = TRUE;
	// 	$config['reuse_query_string'] = TRUE;

	// 	// Bootstrap UI
	// 	$config['full_tag_open'] = '<ul class="pagination">';
	// 	$config['full_tag_close'] = '</ul>';
	// 	$config['first_tag_open'] = '<li>';
	// 	$config['first_tag_close'] = '</li>';
	// 	$config['last_tag_open'] = '<li>';
	// 	$config['last_tag_close'] = '</li>';
	// 	$config['next_tag_open'] = '<li>';
	// 	$config['next_tag_close'] = '</li>';
	// 	$config['prev_tag_open'] = '<li>';
	// 	$config['prev_tag_close'] = '</li>';
	// 	$config['cur_tag_open'] = '<li class="active"><a>';
	// 	$config['cur_tag_close'] = '</a></li>';
	// 	$config['num_tag_open'] = '<li>';
	// 	$config['num_tag_close'] = '</li>';

	// 	$this->pagination->initialize($config);

	// 	$page  = max(1, (int)$page);
	// 	$start = ($page - 1) * $perpage;

	// 	$data['order'] = $this->orderModel->selectOrder($perpage, $start, $filter);
	// 	$data['links'] = $this->pagination->create_links();

	// 	// Truyền filter về lại view
	// 	$data['keyword'] = $keyword;
	// 	$data['status']  = $status;
	// 	$data['perpage'] = $perpage;

	// 	$data['title'] = "Danh sách đơn hàng";
	// 	$data['breadcrumb'] = [
	// 		['label' => 'Dashboard', 'url' => 'dashboard'],
	// 		['label' => 'Danh sách đơn hàng']
	// 	];
	// 	$data['template'] = "order_admin/index";
	// 	$this->load->view("admin-layout/admin-layout", $data);
	// }


	// public function index($page = 1)
	// {
	// 	$this->config->config['pageTitle'] = 'List Order';
	// 	$this->load->model('orderModel');

	// 	// --- Lấy filter từ GET ---
	// 	$keyword = $this->input->get('keyword', TRUE);
	// 	$status  = $this->input->get('status', TRUE);
	// 	$perpage = (int)$this->input->get('perpage');
	// 	$perpage = ($perpage > 0) ? $perpage : 2;

	// 	$filter = [
	// 		'keyword' => $keyword,
	// 		'status'  => $status
	// 	];

	// 	// --- Tổng số đơn hàng ---
	// 	$total = $this->orderModel->countOrder($filter);

	// 	// --- Tính offset ---
	// 	$page = (int)$page;
	// 	$page = ($page > 0) ? $page : 1;
	// 	$max_page = ceil($total / $perpage);

	// 	if ($page > $max_page && $total > 0) {
	// 		$query = http_build_query($this->input->get());
	// 		redirect(base_url('order_admin/listOrder') . ($query ? '?' . $query : ''));
	// 	}

	// 	$start = ($page - 1) * $perpage;

	// 	// --- Cấu hình phân trang ---
	// 	$this->load->library('pagination');
	// 	$config['base_url']           = base_url('order_admin/listOrder');
	// 	$config['total_rows']         = $total;
	// 	$config['per_page']           = $perpage;
	// 	$config['uri_segment']        = 3;
	// 	$config['use_page_numbers']   = true;
	// 	$config['reuse_query_string'] = true;

	// 	// --- Bootstrap UI ---
	// 	$config['full_tag_open']   = '<ul class="pagination">';
	// 	$config['full_tag_close']  = '</ul>';
	// 	$config['first_tag_open']  = '<li>';
	// 	$config['first_tag_close'] = '</li>';
	// 	$config['last_tag_open']   = '<li>';
	// 	$config['last_tag_close']  = '</li>';
	// 	$config['next_tag_open']   = '<li>';
	// 	$config['next_tag_close']  = '</li>';
	// 	$config['prev_tag_open']   = '<li>';
	// 	$config['prev_tag_close']  = '</li>';
	// 	$config['cur_tag_open']    = '<li class="active"><a>';
	// 	$config['cur_tag_close']   = '</a></li>';
	// 	$config['num_tag_open']    = '<li>';
	// 	$config['num_tag_close']   = '</li>';

	// 	$this->pagination->initialize($config);

	// 	// --- Lấy danh sách đơn hàng ---
	// 	$data['order']   = $this->orderModel->selectOrder($perpage, $start, $filter);
	// 	$data['links']   = $this->pagination->create_links();

	// 	// --- Truyền filter lại cho view ---
	// 	$data['keyword'] = $keyword;
	// 	$data['status']  = $status;
	// 	$data['perpage'] = $perpage;
	// 	$data['start']   = $start;

	// 	// --- Thông tin hiển thị ---
	// 	$data['title'] = "Danh sách đơn hàng";
	// 	$data['breadcrumb'] = [
	// 		['label' => 'Dashboard', 'url' => 'dashboard'],
	// 		['label' => 'Danh sách đơn hàng']
	// 	];
	// 	$data['template'] = "order_admin/index";
	// 	$this->load->view("admin-layout/admin-layout", $data);
	// }


	public function index($page = 1)
	{
		$this->config->config['pageTitle'] = 'List Order';
		$this->load->model('orderModel');

		// --- Lấy filter từ GET ---
		$date_from = $this->input->get('date_from', TRUE);
		$date_to   = $this->input->get('date_to', TRUE);
		$keyword         = $this->input->get('keyword', TRUE);
		$status          = $this->input->get('status', TRUE);
		$checkout_method = $this->input->get('checkout_method', TRUE);
		$perpage         = (int)$this->input->get('perpage');
		$perpage         = ($perpage > 0) ? $perpage : 10;

		$filter = [
			'keyword'         => $keyword,
			'status'          => $status,
			'checkout_method' => $checkout_method,
			'date_from'       => $date_from,
			'date_to'         => $date_to,
		];

		$total = $this->orderModel->countOrder($filter);

		// --- Tính offset ---
		$page = (int)$page;
		$page = ($page > 0) ? $page : 1;
		$max_page = ceil($total / $perpage);

		if ($page > $max_page && $total > 0) {
			$query = http_build_query($this->input->get());
			redirect(base_url('order_admin/listOrder') . ($query ? '?' . $query : ''));
		}

		$start = ($page - 1) * $perpage;

		$data['order'] = $this->orderModel->selectOrder($perpage, $start, $filter);
		$data['links'] = init_pagination(base_url('order_admin/listOrder'), $total, $perpage, 3);

		$data['keyword']         = $keyword;
		$data['status']          = $status;
		$data['checkout_method'] = $checkout_method;
		$data['perpage']         = $perpage;
		$data['start']           = $start;

		$data['title'] = "Danh sách đơn hàng";
		$data['breadcrumb'] = [
			['label' => 'Dashboard', 'url' => 'dashboard'],
			['label' => 'Danh sách đơn hàng']
		];
		$data['start'] = $start;
		$data['date_from'] = $date_from;
		$data['date_to']   = $date_to;

		$data['template'] = "order_admin/index";
		$this->load->view("admin-layout/admin-layout", $data);
	}



	public function viewOrder($order_code)
	{
		$this->config->config['pageTitle'] = 'View Order';
		$this->load->model('orderModel');
		$data['order_details'] = $this->orderModel->selectOrderDetails($order_code);

		if (!empty($data['order_details'])) {
			// Lặp qua từng sản phẩm trong order_details
			foreach ($data['order_details'] as &$order_detail) {
				// Lấy số lượng sản phẩm trong batch cho từng sản phẩm và gắn vào thuộc tính mới
				$order_detail->product_qty_in_batches = $this->orderModel->get_qty_product_in_batches($order_detail->ProductID, $order_detail->qty);
			}

			$data['title'] = "Chi tiết đơn hàng";
			$data['breadcrumb'] = [
				['label' => 'Dashboard', 'url' => 'dashboard'],
				['label' => 'Danh sách đơn hàng', 'url' => 'order_admin/listOrder'],
				['label' => 'Chi tiết đơn hàng']
			];
			$data['template'] = "order_admin/viewOrder";
			$this->load->view("admin-layout/admin-layout", $data);
		} else {
			$this->session->set_flashdata('error', 'Không có đơn hàng nào');
			redirect(base_url('dashboard'));
		}

		// echo '<pre>';
		// print_r($data['order_details']);
		// echo '</pre>';
	}



	public function deleteOrder($order_code)
	{
		$this->load->model('orderModel');
		// Lấy danh sách order details
		$order_detail = $this->orderModel->selectOrderDetails($order_code);
		if (empty($order_detail)) {
			$this->session->set_flashdata('error', 'Đơn hàng không tồn tại');
			redirect(base_url('order_admin/listOrder'));
			return;
		}

		if ($order_detail->checkout_method == 'COD') {
			$this->db->trans_start();
			foreach ($order_detail as $order_detail) {
				$this->orderModel->deleteOrderBatches($order_detail->order_detail_id);
			}
			$this->orderModel->deleteOrderDetails($order_code);
			$ShippingID = $this->orderModel->deleteOrder($order_code);
			if ($ShippingID !== false) {
				$this->orderModel->deleteShipping($ShippingID);
			}
			$this->db->trans_complete();
		} elseif ($order_detail->checkout_method == 'VNPAY') {
			$this->session->set_flashdata('error', 'Không thể xoá đơn hàng với phuong thức thanh toán VNPAY');
			redirect(base_url('order_admin/listOrder'));
		}

		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Xóa đơn hàng thất bại');
		} else {
			$this->session->set_flashdata('success', 'Xóa đơn hàng thành công');
		}
		redirect(base_url('order_admin/listOrder'));
	}

	public function update_order_status()
	{
		$value = $this->input->post('value');
		$order_code = $this->input->post('Order_Code');
		$product_qty_in_batch = $this->input->post('product_qty_in_batch');

		$this->load->model('orderModel');

		if ($value == 4) { // Đơn hàng đã thanh toán
			$timenow = Carbon\Carbon::now('Asia/Ho_Chi_Minh')->toDateTimeString();
			$data_order = array(
				'Order_Status' => $value,
				'Payment_Status' => 1,
				'Date_delivered' => $timenow,
				'Payment_date_successful' => $timenow
			);
			$this->orderModel->updateOrder($data_order, $order_code);
			if (!empty($product_qty_in_batch)) {
				foreach ($product_qty_in_batch as $batch) {
					$batch_id = $batch['Batch_ID'];
					$quantity_to_deduct = $batch['QuantityToTake'];

					$this->orderModel->deductBatchQuantity($batch_id, $quantity_to_deduct);
				}
			} else {
				$this->session->set_flashdata('error', 'Lỗi không thể cập nhật số lượng');
				redirect(base_url('order_admin/listOrder'));
			}
		} elseif ($value == 5) {
			$data_order = array(
				'Order_Status' => $value,
			);
			$this->orderModel->updateOrder($data_order, $order_code);
		} else {
			$data_order = array(
				'Order_Status' => $value
			);
			$this->orderModel->updateOrder($data_order, $order_code);
		}
	}


	public function printOrder($order_code)
	{

		// echo $order_code; die();
		$this->load->library('Pdf');
		$this->load->model('orderModel');

		$order_details = $this->orderModel->printOrderDetails($order_code);
		// echo '<pre>';
		// print_r($order_details);
		// echo '</pre>';

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetTitle('Hóa đơn: ' . $order_code);
		$pdf->SetHeaderMargin(10);
		$pdf->SetTopMargin(15);
		$pdf->SetFooterMargin(15);
		$pdf->SetAutoPageBreak(true);
		$pdf->SetAuthor('Pesticide Shop');
		$pdf->SetDisplayMode('real', 'default');
		$pdf->AddPage();

		// Tạo tiêu đề hóa đơn
		$html = '
	    <h2 style="text-align: center;">HÓA ĐƠN MUA HÀNG</h2>
	    <p style="text-align: center;">Cảm ơn bạn đã mua sắm tại <strong>Pesticide Shop</strong></p>
	    <p><strong>Mã đơn hàng:</strong> ' . $order_code . '</p>
	    <p><strong>Ngày in:</strong> ' . date('d/m/Y') . '</p>
	';

		// Bảng chi tiết sản phẩm
		$html .= '
	    <table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse;">
	        <thead>
	            <tr style="background-color: #f2f2f2; text-align: center;">
	                <th>STT</th>
	                <th>Mã đơn hàng</th>
	                <th>Tên sản phẩm</th>
	                <th>Giá</th>
	                <th>Số lượng</th>
	                <th>Chiết khấu</th>
	                <th>Thành tiền</th>
	            </tr>
	        </thead>
	        <tbody>
	';

		$total = 0;
		foreach ($order_details as $key => $product) {
			$discounted_price = $product->Selling_price * (1 - $product->Promotion / 100);
			$subtotal = $product->qty * $discounted_price;
			$total += $subtotal;

			$html .= '
			<tr style="text-align: center;">
				<td>' . ($key + 1) . '</td>
				<td>' . $product->Order_Code . '</td>
				<td style="text-align: left;">' . $product->Name . '</td>
				<td>' . number_format($product->Selling_price, 0, ',', '.') . 'đ</td>
				<td>' . $product->qty . '</td>
				<td>' . $product->Promotion . '%</td>
				<td>' . number_format($subtotal, 0, ',', '.') . 'đ</td>
			</tr>
			';
		}


		// Tổng cộng
		$html .= '
	        <tr style="font-weight: bold; text-align: right;">
	            <td colspan="6">Tổng cộng:</td>
	            <td style="text-align: center;">' . number_format($total, 0, ',', '.') . 'đ</td>
	        </tr>
	    </tbody>
	    </table>
	';

		// Lời cảm ơn
		$html .= '
	    <p style="text-align: center; margin-top: 20px;">Cảm ơn bạn đã ủng hộ. Mọi thắc mắc vui lòng liên hệ hotline: <strong>1900 1900</strong>.</p>
	';

		// Xuất PDF
		$pdf->SetFont('dejavusans', '', 10);
		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->Output('Order_' . $order_code . '.pdf', 'I');
	}
}
