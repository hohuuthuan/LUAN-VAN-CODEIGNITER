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
 * @property categoryModel $categoryModel
 * @property upload $upload
 * @property data $data
 * @property email $email
 * @property cart $cart
 * @property orderModel $orderModel
 */

class CheckoutController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $this->load->model('indexModel');
        $this->load->library('cart');
        $this->data['brand'] = $this->indexModel->getBrandHome();
        $this->data['category'] = $this->indexModel->getCategoryHome();
    }
    public function checkLogin()
    {
        if (!$this->session->userdata('logged_in_customer')) {
            $this->session->set_flashdata('error', 'Bạn cần đăng nhập để sử dụng chức năng này.');
            redirect(base_url('/dang-nhap'));
        }
    }

    public function getUserOnSession()
    {
        $this->checkLogin();
        // Lấy thông tin người dùng từ session
        $user_data = $this->session->userdata('logged_in_customer');
        return $user_data;
    }


    public function send_mail($to_mail, $subject, $message)
    {

        $config = array();

        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.gmail.com';
        $config['smtp_user'] = 'hohuuthuan789@gmail.com';
        $config['smtp_pass'] = 'xvinihubnvdnmloz';
        $config['smtp_port'] = '465';
        $config['charset'] = 'utf-8';
        $config['wordwrap'] = TRUE;
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $from_mail = 'hohuuthuan789@gmail.com';

        $this->email->from($from_mail, 'Trang web abc.com');
        $this->email->to($to_mail);

        $this->email->subject($subject);
        $this->email->message($message);

        $this->email->send();
    }


    public function confirm_checkout_method()
    {

        $this->form_validation->set_rules('name', 'Username', 'trim|required', ['required' => 'Bạn cần cung cấp %s']);
        $this->form_validation->set_rules('email', 'Email', 'trim|required', ['required' => 'Bạn cần cung cấp %s']);
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required', ['required' => 'Bạn cần cung cấp %s']);
        $this->form_validation->set_rules('address', 'Address', 'trim|required', ['required' => 'Bạn cần cung cấp %s']);

        

        $user_id = $this->getUserOnSession();
        $email = $this->input->post('email');
        // Tạo mã đơn hàng duy nhất
        $letters = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);
        $numbers = sprintf("%06d", rand(0, 999999));
        $order_code = $letters . $numbers;

        // Lấy dữ liệu vận chuyển từ form
        $shipping_data = [
            'user_id' => $user_id['id'],
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'phone' => $this->input->post('phone'),
            'address' => $this->input->post('address'),
            'checkout_method' => $this->input->post('checkout_method')
        ];

        // Lưu thông tin shipping vào session với key là mã đơn hàng
        $this->session->set_userdata("shipping_data_{$order_code}", $shipping_data);

        // Tính tổng tiền
        $subtotal = 0;
        $total = 0;
        foreach ($this->cart->contents() as $items) {
            $subtotal = $items['qty'] * $items['price'];
            $total += $subtotal;
        }

        if ($this->form_validation->run()) {
            if (isset($_POST['checkout_method']) && $_POST['checkout_method'] == 'COD') {
                // Truy xuất đúng session shipping theo order_code
                $shipping_data_session = $this->session->userdata("shipping_data_{$order_code}");

                // echo '<pre>';
                // print_r($shipping_data_session);
                // echo '</pre>';
                // die();

                $this->load->model('orderModel');
                $ShippingID = $this->orderModel->newShipping($shipping_data_session);

                if ($ShippingID) {
                    if (!empty($this->session->userdata("shipping_data_{$order_code}"))) {
                        $this->session->unset_userdata("shipping_data_{$order_code}");
                    }

                    $data_order = [
                        'Order_code' => $order_code,
                        'Order_Status' => -1,
                        'Payment_Status' => 0,
                        'UserID' => $user_id['id'],
                        'TotalAmount' => $total,
                        // Chưa có giảm giá
                        'Date_Order' => Carbon\Carbon::now('Asia/Ho_Chi_Minh')->toDateTimeString(),
                        'ShippingID' => $ShippingID
                    ];
                    $this->data['order_id'] = $this->orderModel->insert_order($data_order);

                    foreach ($this->cart->contents() as $items) {
                        $data_order_detail = array(
                            'Order_code' => $order_code,
                            'ProductID' => $items['id'],
                            'Quantity' => $items['qty'],
                            'Selling_price' => $items['price'],
                            // Chưa có áp mã giảm giá
                            'Subtotal' => $items['subtotal'],
                        );
                        $order_detail_id = $this->orderModel->insert_order_detail($data_order_detail);
                        $get_product_in_batches = $this->orderModel->get_qty_product_in_batches($items['id'], $items['qty']);
                        if (!empty($get_product_in_batches)) {
                            foreach ($get_product_in_batches['batches'] as $batch) {
                                $data_order_batches = [
                                    'order_detail_id' => $order_detail_id,
                                    'batch_id' => $batch['Batch_ID'],
                                    'quantity' => $get_product_in_batches['totalQuantity']
                                ];
                                $result = $this->orderModel->insert_order_batches($data_order_batches);
                            }
                        }
                    }
                }

                $this->session->set_flashdata('success', 'Đặt hàng thành công');
                $this->cart->destroy();

                $to_mail = $email;
                $subject = 'Thông báo đặt hàng';
                $message = 'Cảm ơn bạn đã đặt hàng, chúng tôi sẽ gửi đơn hàng đến bạn sớm nhất.';
                $this->send_mail($to_mail, $subject, $message);
                redirect(base_url('thank-you-for-order'));
            } elseif (isset($_POST['checkout_method']) && $_POST['checkout_method'] == 'VNPAY') {

                // Chuyển hướng thanh toán vnpay
                $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
                $vnp_Returnurl = "http://localhost:8000/thank-you-for-order";
                $vnp_TmnCode = "F72UMWTL";
                $vnp_HashSecret = "696U98UTDBDDD09ZN1T0GAVR3KC4EVMU";

                $vnp_TxnRef = $order_code;
                $vnp_OrderInfo = 'Thanh toan don hang: ' . $order_code;
                $vnp_OrderType = 'billpayment';
                $vnp_Amount = $total * 100;
                $vnp_Locale = 'vn';
                $vnp_BankCode = 'NCB';
                $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];


                $inputData = array(
                    "vnp_Version" => "2.1.0",
                    "vnp_TmnCode" => $vnp_TmnCode,
                    "vnp_Amount" => $vnp_Amount,
                    "vnp_Command" => "pay",
                    "vnp_CreateDate" => date('YmdHis'),
                    "vnp_CurrCode" => "VND",
                    "vnp_IpAddr" => $vnp_IpAddr,
                    "vnp_Locale" => $vnp_Locale,
                    "vnp_OrderInfo" => $vnp_OrderInfo,
                    "vnp_OrderType" => $vnp_OrderType,
                    "vnp_ReturnUrl" => $vnp_Returnurl,
                    "vnp_TxnRef" => $vnp_TxnRef

                );

                if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                    $inputData['vnp_BankCode'] = $vnp_BankCode;
                }
                if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
                    $inputData['vnp_Bill_State'] = $vnp_Bill_State;
                }

                ksort($inputData);
                $query = "";
                $i = 0;
                $hashdata = "";
                foreach ($inputData as $key => $value) {
                    if ($i == 1) {
                        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                    } else {
                        $hashdata .= urlencode($key) . "=" . urlencode($value);
                        $i = 1;
                    }
                    $query .= urlencode($key) . "=" . urlencode($value) . '&';
                }

                $vnp_Url = $vnp_Url . "?" . $query;
                if (isset($vnp_HashSecret)) {
                    $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
                    $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
                }
                $returnData = array(
                    'code' => '00',
                    'message' => 'success',
                    'data' => $vnp_Url
                );
                if (isset($_POST['checkout_method']) && $_POST['checkout_method'] == 'VNPAY') {
                    header('Location: ' . $vnp_Url);
                    die();
                } else {
                    echo json_encode($returnData);
                }
            } else {
                $this->session->set_flashdata('error', 'Vui lòng chọn phương thức thanh toán');
                redirect(base_url('checkout'));
            }
        } else {
            $this->session->set_flashdata('error', 'Vui lòng điền đầy đủ thông tin');
            redirect(base_url('checkout'));
        }
    }



    public function thank_you_for_order()
    {
        if (isset($_GET['vnp_Amount']) && $_GET['vnp_ResponseCode'] == 00) {
            $user_id = $this->getUserOnSession();
            $shipping_data_session = $this->session->userdata("shipping_data_{$_GET['vnp_TxnRef']}");

            $this->load->model('orderModel');
            $ShippingID = $this->orderModel->newShipping($shipping_data_session);


            if ($ShippingID) {
                if (!empty($this->session->userdata("shipping_data_{$_GET['vnp_TxnRef']}"))) {
                    $this->session->unset_userdata("shipping_data_{$_GET['vnp_TxnRef']}");
                }
                // Tính tổng tiền
                $subtotal = 0;
                $total = 0;
                foreach ($this->cart->contents() as $items) {
                    $subtotal = $items['qty'] * $items['price'];
                    $total += $subtotal;
                }
                $data_order = [
                    'Order_code' => $_GET['vnp_TxnRef'],
                    'Order_Status' => -1,
                    'Payment_Status' => 1,
                    'UserID' => $user_id['id'],
                    'TotalAmount' => $total,
                    // Chưa có giảm giá
                    'Date_Order' => Carbon\Carbon::now('Asia/Ho_Chi_Minh')->toDateTimeString(),
                    'ShippingID' => $ShippingID,
                    'Payment_date_successful' => Carbon\Carbon::now('Asia/Ho_Chi_Minh')->toDateTimeString()
                ];
                $this->data['order_id'] = $this->orderModel->insert_order($data_order);


                foreach ($this->cart->contents() as $items) {
                    $data_order_detail = array(
                        'Order_code' => $_GET['vnp_TxnRef'],
                        'ProductID' => $items['id'],
                        'Quantity' => $items['qty'],
                        'Selling_price' => $items['price'],
                        // Chưa có áp mã giảm giá
                        'Subtotal' => $items['subtotal'],
                    );
                    $order_detail_id = $this->orderModel->insert_order_detail($data_order_detail);

                    $get_product_in_batches = $this->orderModel->get_qty_product_in_batches($items['id'], $items['qty']);


                    if (!empty($get_product_in_batches)) {
                        foreach ($get_product_in_batches['batches'] as $batch) {
                            $data_order_batches = [
                                'order_detail_id' => $order_detail_id,
                                'batch_id' => $batch['Batch_ID'],
                                'quantity' => $get_product_in_batches['totalQuantity']
                            ];
                            $this->orderModel->insert_order_batches($data_order_batches);
                        }
                    }
                }
            }
            // Lưu thông tin thanh toán VNPAY
            $data_vnpay = [
                'ShippingID' => $ShippingID,
                'vnp_Amount' => $_GET['vnp_Amount'],
                'vnp_BankCode' => $_GET['vnp_BankCode'],
                'vnp_BankTranNo' => $_GET['vnp_BankTranNo'],
                'vnp_CardType' => $_GET['vnp_CardType'],
                'vnp_OrderInfo' => $_GET['vnp_OrderInfo'],
                'vnp_PayDate' =>  $_GET['vnp_PayDate'],
                'vnp_ResponseCode' => $_GET['vnp_ResponseCode'],
                'vnp_TmnCode' => $_GET['vnp_TmnCode'],
                'vnp_TransactionStatus' => $_GET['vnp_TransactionStatus'],
                'vnp_TxnRef' => $_GET['vnp_TxnRef'], // Lưu giá trị order_code
                'vnp_SecureHash' => $_GET['vnp_SecureHash']
            ];
            $this->load->model('indexModel');
            $this->indexModel->insert_VNPAY($data_vnpay);


            $this->session->set_flashdata('success', 'Đặt hàng thành công');
            $this->cart->destroy();

            $to_mail = $user_id['email'];
            $subject = 'Thông báo đặt hàng';
            $message = 'Cảm ơn bạn đã đặt hàng, chúng tôi sẽ gửi đơn hàng đến bạn sớm nhất.';
            $this->send_mail($to_mail, $subject, $message);
            redirect(base_url('thank-you-for-order'));
            die();
        }


        $this->config->config['pageTitle'] = 'Cảm ơn bạn đã đặt hàng';
        $this->data['template'] = "thanks/index";
        $this->load->view("pages/layout/index", $this->data);
    }

    public function thank_you_for_payment()
    {
        $this->config->config['pageTitle'] = 'Cảm ơn bạn đã đặt hàng';
        $this->data['template'] = "thanks/index";
        $this->load->view("pages/layout/index", $this->data);
    }
}
