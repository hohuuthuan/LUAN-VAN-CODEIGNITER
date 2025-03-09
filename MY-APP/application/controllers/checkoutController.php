<?php
defined('BASEPATH') or exit('No direct script access allowed');
class CheckoutController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->load->library('cart');

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

    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }



    public function confirm_checkout_method()
    {

        $subtotal = 0;
        $total = 0;
        foreach ($this->cart->contents() as $items) {
            $subtotal = $items['qty'] * $items['price'];
            $total += $subtotal;
        }
        if (isset($_POST['checkout_method']) && $_POST['checkout_method'] == 'COD') {
            // echo $total;
            $this->form_validation->set_rules('name', 'Username', 'trim|required', ['required' => 'Bạn cần cung cấp %s']);
            $this->form_validation->set_rules('email', 'Email', 'trim|required', ['required' => 'Bạn cần cung cấp %s']);
            $this->form_validation->set_rules('phone', 'Phone', 'trim|required', ['required' => 'Bạn cần cung cấp %s']);
            $this->form_validation->set_rules('address', 'Address', 'trim|required', ['required' => 'Bạn cần cung cấp %s']);
            if ($this->form_validation->run() == TRUE) {
                $name = $this->input->post('name');
                $email = $this->input->post('email');
                $phone = $this->input->post('phone');
                $address = $this->input->post('address');
                $checkout_method = $this->input->post('checkout_method');
                $user_id = $this->getUserOnSession();
                $data = [
                    'user_id' => $user_id['id'],
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'address' => $address,
                    'form_of_payment' => $checkout_method
                ];

                $this->load->model('loginModel');
                $result = $this->loginModel->newShipping($data);
                if ($result) {
                    $letters = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3); // Lấy 3 chữ cái ngẫu nhiên
                    $numbers = sprintf("%06d", rand(0, 999999));
                    $order_code = $letters . $numbers;
                    // echo $order_code;
                    // Lưu vàp orders
                    $data_orders = [
                        'order_code' => $order_code,
                        'status' => 1,
                        'form_of_payment_id' => $result

                    ];
                    $insert_orders = $this->loginModel->insert_orders($data_orders);

                    // Order details
                    foreach ($this->cart->contents() as $items) {
                        $date_created = Carbon\Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
                        $data_orders_details = array(
                            'order_code' => $order_code,
                            'product_id' => $items['id'],
                            'quantity' => $items['qty'],
                            'subtotal' => $items['subtotal'],
                            'date_order' => $date_created
                        );
                        $insert_orders_details = $this->loginModel->insert_orders_details($data_orders_details);
                    }


                    $this->session->set_flashdata('success', 'Đặt hàng thành công');
                    $this->cart->destroy();

                    $to_mail = $email;
                    $subject = 'Thông báo đặt hàng';
                    $message = 'Cảm ơn bạn đã đặt hàng, chúng tôi sẽ gửi đơn hàng đến bạn sớm nhất.';
                    $this->send_mail($to_mail, $subject, $message);
                    redirect(base_url('thank-you-for-order'));
                } else {
                    $this->session->set_flashdata('error', 'Đặt hàng thất bại');
                    redirect(base_url('checkout'));
                }
            } else {
                redirect(base_url('checkout'));
            }
        } elseif (isset($_POST['checkout_method']) && $_POST['checkout_method'] == 'VNPAY') {


            $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
            $vnp_Returnurl = "http://localhost:8000/thank-you-for-order";
            $vnp_TmnCode = "F72UMWTL";
            $vnp_HashSecret = "696U98UTDBDDD09ZN1T0GAVR3KC4EVMU";

            $vnp_TxnRef = rand(00, 9999); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
            $vnp_OrderInfo = 'Noi dung thanh toan';
            $vnp_OrderType = 'billpayment';
            $vnp_Amount = $total * 100;
            $vnp_Locale = 'vn';
            $vnp_BankCode = 'NCB';
            $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
            //Add Params of 2.0.1 Version
            //$vnp_ExpireDate = $_POST['txtexpire'];
            //Billing
            // $vnp_Bill_Mobile = $_POST['txt_billing_mobile'];
            // $vnp_Bill_Email = $_POST['txt_billing_email'];
            // $fullName = trim($_POST['txt_billing_fullname']);
            // if (isset($fullName) && trim($fullName) != '') {
            //     $name = explode(' ', $fullName);
            //     $vnp_Bill_FirstName = array_shift($name);
            //     $vnp_Bill_LastName = array_pop($name);
            // }
            // $vnp_Bill_Address=$_POST['txt_inv_addr1'];
            // $vnp_Bill_City=$_POST['txt_bill_city'];
            // $vnp_Bill_Country=$_POST['txt_bill_country'];
            // $vnp_Bill_State=$_POST['txt_bill_state'];
            // // Invoice
            // $vnp_Inv_Phone=$_POST['txt_inv_mobile'];
            // $vnp_Inv_Email=$_POST['txt_inv_email'];
            // $vnp_Inv_Customer=$_POST['txt_inv_customer'];
            // $vnp_Inv_Address=$_POST['txt_inv_addr1'];
            // $vnp_Inv_Company=$_POST['txt_inv_company'];
            // $vnp_Inv_Taxcode=$_POST['txt_inv_taxcode'];
            // $vnp_Inv_Type=$_POST['cbo_inv_type'];
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

                // "vnp_ExpireDate"=>$vnp_ExpireDate,
                // "vnp_Bill_Mobile"=>$vnp_Bill_Mobile,
                // "vnp_Bill_Email"=>$vnp_Bill_Email,
                // "vnp_Bill_FirstName"=>$vnp_Bill_FirstName,
                // "vnp_Bill_LastName"=>$vnp_Bill_LastName,
                // "vnp_Bill_Address"=>$vnp_Bill_Address,
                // "vnp_Bill_City"=>$vnp_Bill_City,
                // "vnp_Bill_Country"=>$vnp_Bill_Country,
                // "vnp_Inv_Phone"=>$vnp_Inv_Phone,
                // "vnp_Inv_Email"=>$vnp_Inv_Email,
                // "vnp_Inv_Customer"=>$vnp_Inv_Customer,
                // "vnp_Inv_Address"=>$vnp_Inv_Address,
                // "vnp_Inv_Company"=>$vnp_Inv_Company,
                // "vnp_Inv_Taxcode"=>$vnp_Inv_Taxcode,
                // "vnp_Inv_Type"=>$vnp_Inv_Type
            );

            if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }
            if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
                $inputData['vnp_Bill_State'] = $vnp_Bill_State;
            }

            //var_dump($inputData);
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
                $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }
            $returnData = array(
                'code' => '00'
                ,
                'message' => 'success'
                ,
                'data' => $vnp_Url
            );
            if (isset($_POST['checkout_method']) && $_POST['checkout_method'] == 'VNPAY') {
                header('Location: ' . $vnp_Url);
                die();
            } else {
                echo json_encode($returnData);
            }
        }
    }

}



