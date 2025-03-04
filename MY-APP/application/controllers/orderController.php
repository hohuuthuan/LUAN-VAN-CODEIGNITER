<?php
defined('BASEPATH') or exit('No direct script access allowed');
class orderController extends CI_Controller
{

	public function checkLogin()
	{
		if (!$this->session->userdata('logged_in_admin')) {
			redirect(base_url('dang_nhap'));
		}
	}

	public function index()
	{
		$this->config->config['pageTitle'] = 'List Order';
		$this->load->model('orderModel');
		$data['order'] = $this->orderModel->selectOrder();
		// In dữ liệu để kiểm tra
		// echo '<pre>';
		// print_r($data['order']);
		// echo '</pre>';
		$data['template'] = "order_admin/index";
		$data['title'] = "Danh sách đơn hàng";
		$this->load->view("admin-layout/admin-layout", $data);
	}


	public function viewOrder($order_code)
	{
		$this->config->config['pageTitle'] = 'View Order';
		$this->load->model('orderModel');
		$data['order_details'] = $this->orderModel->selectOrderDetails($order_code);
		// In dữ liệu để kiểm tra
		// echo '<pre>';
		// print_r($data['order_details']);
		// echo '</pre>';
		$data['template'] = "order_admin/viewOrder";
		$data['title'] = "Chi tiết đơn hàng";
		$this->load->view("admin-layout/admin-layout", $data);
	}

	public function deleteOrder($order_code)
	{
		$this->load->model('orderModel');
		$del = $this->orderModel->deleteOrder($order_code);
		$del_order_details = $this->orderModel->deleteOrderDetails($order_code);
		if ($del && $del_order_details) {
			$this->session->set_flashdata('success', 'Xóa đơn hàng thành công');
			redirect(base_url('order_admin/listOrder'));
		} else {
			$this->session->set_flashdata('error', 'Xóa đơn hàng thất bại');
			redirect(base_url('order-admin/listOrder'));
		}
	}

	public function update_order_status()
	{
		$value = $this->input->post('value');
		$order_code = $this->input->post('order_code');
		$this->load->model('orderModel');

		if ($value == 4) {
			$date_delivered = Carbon\Carbon::now('Asia/Ho_Chi_Minh');
			$data_order = array(
				'status' => $value,
			);
			$data_order_details = array(
				'date_delivered' => $date_delivered
			);

			// Tính tổng tiền của đơn hàng
			$order_details = $this->orderModel->selectOrderDetails($order_code);
			$tong = 0;
			foreach ($order_details as $ord) {
				$tong += $ord->sub;
			}

			// Chuẩn bị dữ liệu để chèn vào bảng revenue
			$data_revenue = array(
				'order_code' => $order_code,
				'subtotal' => $tong,
				'date_delivered' => $date_delivered
			);

			// Gọi hàm chèn dữ liệu vào bảng revenue
			$this->orderModel->insertRevenue($data_revenue);
			// Cập nhật dữ liệu vào bảng orders và order_details
			$this->orderModel->updateOrder($data_order, $order_code);
			$this->orderModel->updateOrderDetails($data_order_details, $order_code);

		} elseif ($value == 5) {
			$data_order = array(
				'status' => $value,
			);
			$this->orderModel->updateOrder($data_order, $order_code);
		} else {
			$data_order = array(
				'status' => $value
			);
			$data_order_details = array(
				'date_delivered' => '0000-00-00'
			);
			$this->orderModel->updateOrder($data_order, $order_code);
			$this->orderModel->updateOrderDetails($data_order_details, $order_code);
		}
	}


	public function printOrder($order_code)
	{
		$this->load->library('Pdf');
		$this->load->model('orderModel');

		$order_details = $this->orderModel->printOrderDetails($order_code);

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
			$discounted_price = $product->selling_price * (1 - $product->discount / 100);
			$subtotal = $product->qty * $discounted_price;
			$total += $subtotal;

			$html .= '
            <tr style="text-align: center;">
                <td>' . ($key + 1) . '</td>
                <td>' . $order_code . '</td>
                <td style="text-align: left;">' . $product->title . '</td>
                <td>' . number_format($product->selling_price, 0, ',', '.') . 'đ</td>
                <td>' . $product->qty . '</td>
                <td>' . $product->discount . '%</td>
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
?>