<?php
defined('BASEPATH') or exit('No direct script access allowed');

class indexController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}

		$this->load->model('indexModel');
		$this->load->model('sliderModel');
		$this->load->library('cart');
		$this->data['brand'] = $this->indexModel->getBrandHome();
		$this->data['category'] = $this->indexModel->getCategoryHome();
	}
	public function page_404()
	{

		$this->load->view('pages/component/page404');

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
	public function index()
	{
		$this->load->library('pagination');
		// Lấy số lượng sản phẩm từ model
		$total_products = $this->indexModel->countAllProduct();
		$config["total_rows"] = !empty($total_products) ? (int) $total_products : 0;

		// Cấu hình phân trang
		$config = array();
		$config["base_url"] = base_url() . '/pagination/index';
		$config['total_rows'] = $total_products; // Sử dụng số lượng sản phẩm đã được đếm
		$config["per_page"] = 6; // Số lượng sản phẩm trên mỗi trang
		$config["uri_segment"] = 3; // Vị trí của số trang trong URI
		$config['use_page_numbers'] = TRUE;

		// Cấu hình HTML phân trang
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
		$page_segment = $this->uri->segment(3);
		$page = (!empty($page_segment) && is_numeric($page_segment)) ? (int) $page_segment : 1;
		$start = ($page - 1) * $config["per_page"];

		// Tạo các liên kết phân trang
		$this->data["links"] = $this->pagination->create_links();

		// Lấy danh sách sản phẩm có phân trang
		$this->data['allproduct_pagination'] = $this->indexModel->getIndexPagination($config["per_page"], $start);
		$this->data['items_category'] = $this->indexModel->getItemsCategoryHome();
		$this->data['sliders'] = $this->sliderModel->selectAllSlider();


		// echo '<pre>';
		// print_r($this->data['allproduct_pagination']);
		// echo '</pre>';


		// Load view templates
		$this->data['template'] = "pages/home/index";
		$this->load->view("pages/layout/index", $this->data);

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
		// Gửi 1 bản copy cho 1 hay nhiều người
		// $this->email->cc('another@another-example.com');
		// Gửi 1 bản copy cho 1 hay nhiều người mà không thấy được thông tin người gửi
		// $this->email->bcc('them@their-example.com');


		$this->email->subject($subject);
		$this->email->message($message);

		$this->email->send();
	}


	public function checkout()
	{
		$this->config->config['pageTitle'] = 'Checkout';
		if ($this->session->userdata('logged_in_customer') && $this->cart->contents()) {


			$this->data['template'] = "pages/checkout/index";
			$this->load->view("pages/layout/index", $this->data);
		} else {
			$this->session->set_flashdata('error', 'Vui lòng đăng nhập để thực hiện đặt hàng');
			redirect(base_url() . 'gio-hang');
		}
	}
	public function confirm_checkout()
	{
		$this->form_validation->set_rules('name', 'Username', 'trim|required', ['required' => 'Bạn cần cung cấp %s']);
		$this->form_validation->set_rules('email', 'Email', 'trim|required', ['required' => 'Bạn cần cung cấp %s']);
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required', ['required' => 'Bạn cần cung cấp %s']);
		$this->form_validation->set_rules('address', 'Address', 'trim|required', ['required' => 'Bạn cần cung cấp %s']);

		if ($this->form_validation->run() == TRUE) {

			$name = $this->input->post('name');
			$email = $this->input->post('email');
			$phone = $this->input->post('phone');
			$address = $this->input->post('address');
			$form_of_payment = $this->input->post('form_of_payment');

			$user_id = $this->getUserOnSession();
			// lấy id bên shipping
			// select ra ordercode có u
			// cần lấy tên người dùng, mã đặt hàng, tên hàng đặt, số lượng hàng đặt, trạng thái đơn hàng, tổng tiền
			// Thêm id-user đê biết ai đang đặt hàng mà insert vào bảng cart
			$data = [
				'user_id' => $user_id['id'],
				'name' => $name,
				'email' => $email,
				'phone' => $phone,
				'address' => $address,
				'form_of_payment' => $form_of_payment
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
				// Khi đã đặt hàng thành công thì giỏ hàng sẽ xóa đi các mặt hàng đã đặt
				$this->cart->destroy();

				// Gửi mail thông báo đã đặt hàng
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
	}
	public function listOrder()
	{
		$this->config->config['pageTitle'] = 'List Order';
		
		// Gán thông tin tiêu đề và template vào $this->data
		$this->data['template'] = 'pages/listOrder/index'; // Chỉ định view chính ở đây
		$this->data['pageTitle'] = 'Danh sách đơn hàng';
		$user_id = $this->getUserOnSession();
		$this->load->model('orderModel');
		$this->load->model('productModel');
		$data['order_items'] = $this->orderModel->getOrderByUserId($user_id['id']);
		if (!empty($data['order_items'])) {
			foreach ($data['order_items'] as $order_item) {
				$product_details = $this->productModel->selectProductById($order_item->product_id);
				$order_item->product_details = $product_details;
			}
		} else {
			$this->session->set_flashdata('error', 'Không có đơn hàng nào');
		}
		$this->data['order_items'] = $data['order_items']; // Gán danh sách đơn hàng vào $this->data
		$this->load->view("pages/layout/index", $this->data);
	}
	


	public function viewOrder($order_code)
	{
		$this->load->view('pages/component/header', $this->data);
		$this->load->model('orderModel');
		$this->load->model('productModel');

		// Lấy chi tiết đơn hàng dựa trên order_code
		$data['order_details'] = $this->orderModel->selectOrderDetails($order_code);

		// Lặp qua từng mục chi tiết đơn hàng và lấy thông tin chi tiết sản phẩm
		foreach ($data['order_details'] as $order_detail) {
			$product_details = $this->productModel->selectProductById($order_detail->product_id);
		}
		$this->load->view("pages/viewOrder", $data);
		$this->load->view("pages/component/footer");
	}

	public function deleteOrder($order_code)
	{
		$this->load->model('orderModel');
		$status = $this->orderModel->selectOrderDetails($orderCode)['order_status'];
		$del_order_details = $this->orderModel->deleteOrderDetails($order_code);
		$del = $this->orderModel->deleteOrder($order_code);
		if ($del && $del_order_details) {
			$this->session->set_flashdata('success', 'Xóa đơn hàng thành công');
			redirect(base_url('order_customer/listOrder'));
		} else {
			$this->session->set_flashdata('error', 'Xóa đơn hàng thất bại');
			redirect(base_url('order-customer/listOrder'));
		}
	}
	public function category($id)
	{
		$this->data['slug'] = $this->indexModel->getCategorySlug($id);
		//custom config link
		$config = array();
		$config["base_url"] = base_url() . '/pagination/danh-muc/' . '/' . $id . '/' . $this->data['slug'];
		$config['total_rows'] = ceil($this->indexModel->countAllProductByCate($id)); //đếm tất cả sản phẩm //8 //hàm ceil làm tròn phân trang 
		$config["per_page"] = 6; //từng trang 3 sản phẩn
		$config["uri_segment"] = 5; //lấy số trang hiện tại
		$config['use_page_numbers'] = TRUE; //trang có số
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
		//end custom config link
		$this->pagination->initialize($config); //tự động tạo trang
		$this->page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0; //current page active 
		$this->data["links"] = $this->pagination->create_links(); //tự động tạo links phân trang dựa vào trang hiện tại

		// Lấy giá thấp nhất và lớn nhất
		$min_price = $this->data['min_price'] = $this->indexModel->getMinPriceProduct($id);
		$max_price = $this->data['max_price'] = $this->indexModel->getMaxPriceProduct($id);



		// Filter
		if (isset($_GET['kytu'])) {
			$kytu = $_GET['kytu'];
			$this->data['allproductbycate_pagination'] = $this->indexModel->getCategoryKyTuPagination($id, $kytu, $config["per_page"], $this->page);
		} elseif (isset($_GET['gia'])) {
			$kytu = $_GET['gia'];
			$this->data['allproductbycate_pagination'] = $this->indexModel->getCategoryPricePagination($id, $kytu, $config["per_page"], $this->page);
		} elseif (isset($_GET['to']) && isset($_GET['from'])) {
			$from_price = $_GET['from'];
			$to_price = $_GET['to'];
			$this->data['allproductbycate_pagination'] = $this->indexModel->getCategoryPriceRangePagination($id, $from_price, $to_price, $config["per_page"], $this->page);
		} else {
			$this->data['allproductbycate_pagination'] = $this->indexModel->getCategoryPagination($id, $config["per_page"], $this->page);
		}




		// $this->data['category_Product'] = $this->indexModel->getCategoryProduct($id);
		$this->data['title'] = $this->indexModel->getCategoryTitle($id);
		$this->config->config['pageTitle'] = $this->data['title'];

		$this->data['template'] = "pages/category/index";
		$this->load->view("pages/layout/index", $this->data);
	}
	public function brand($id)
	{

		$this->data['slug'] = $this->indexModel->getBrandSlug($id);
		//custom config link
		$config = array();
		$config["base_url"] = base_url() . '/pagination/thuong-hieu/' . '/' . $id . '/' . $this->data['slug'];
		$config['total_rows'] = ceil($this->indexModel->countAllProductByBrand($id)); //đếm tất cả sản phẩm //8 //hàm ceil làm tròn phân trang 
		$config["per_page"] = 6; //từng trang 3 sản phẩn
		$config["uri_segment"] = 5; //lấy số trang hiện tại
		$config['use_page_numbers'] = TRUE; //trang có số
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
		//end custom config link
		$this->pagination->initialize($config); //tự động tạo trang
		$this->page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0; //current page active 
		$this->data["links"] = $this->pagination->create_links(); //tự động tạo links phân trang dựa vào trang hiện tại
		// Giới hạn sản phẩm trong trang (limit, start)
		$this->data['allproductbybrand_pagination'] = $this->indexModel->getbrandPagination($id, $config["per_page"], $this->page);


		$this->data['title'] = $this->indexModel->getBrandTitle($id);
		$this->config->config['pageTitle'] = $this->data['title'];
		$this->data['template'] = "pages/brand/index";
		$this->load->view("pages/layout/index", $this->data);
	}



	public function search_product()
	{
		if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
			$keyword = $_GET['keyword'];
		} else {
			$keyword = '';
		}

		// Custom config link
		$config = array();
		$config["base_url"] = base_url('search-product');
		$config['reuse_query_string'] = TRUE;
		$config['total_rows'] = ceil($this->indexModel->countAllProductByKeyword($keyword));
		$config["per_page"] = 3; // Number of products per page
		$config["uri_segment"] = 2;
		$config['use_page_numbers'] = TRUE;
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

		$this->pagination->initialize($config);

		$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

		$this->data["links"] = $this->pagination->create_links();

		// Limit the number of products per page
		$this->data['allproductbykeyword_pagination'] = $this->indexModel->getSearchPagination($keyword, $config["per_page"], $page);

		$this->data['title'] = $keyword;
		$this->config->config['pageTitle'] = "Search product: " . $keyword;

		$this->data['template'] = "pages/search/index";
		$this->load->view("pages/layout/index", $this->data);

	}

	public function product($id)
	{
		$this->load->model("indexModel");
		$this->data['product_details'] = $this->indexModel->getProductDetails($id);

		$this->data['product_comments'] = $this->indexModel->getListConmment($id);
		$this->data['title'] = $this->indexModel->getProductTitle($id);
		$this->config->config['pageTitle'] = $this->data['title'];
		// echo '<pre>';
		// print_r($this->data['product_details']);
		// echo '</pre>';
		$this->data['template'] = "pages/product-detail/index";
		$this->load->view("pages/layout/index", $this->data);
	}
	public function thank_you_for_order()
	{
		$this->config->config['pageTitle'] = 'Cảm ơn bạn đã đặt hàng';
		$this->data['template'] = "pages/thanks/index";
		$this->load->view("pages/layout/index", $this->data);


	}
	public function cart()
	{
		$this->config->config['pageTitle'] = 'Giỏ hàng';
		$this->data['template'] = "pages/cart/index";
		$this->load->view("pages/layout/index", $this->data);
	}

	public function add_to_cart()
	{
		$product_id = $this->input->post('product_id');
		$quantity = $this->input->post('quantity');
		$this->data['product_details'] = $this->indexModel->getProductDetails($product_id);

		// Đặt hàng
		$found = false;
		foreach ($this->cart->contents() as $items) {
			if ($items['id'] == $product_id) {
				$this->session->set_flashdata('error', 'Mặt hàng bạn đặt đã tồn tại trong giỏ hàng. Vui lòng vào giỏ hàng chỉnh sửa số lượng');
				redirect(base_url() . 'gio-hang', 'refresh');
				$found = true;
				break;
			}
		}
		if (!$found) {
			foreach ($this->data['product_details'] as $key => $product) {
				if ($quantity <= $product->quantity) {
					// Tính giá giảm nếu có discount
					if (isset($product->discount) && $product->discount != 0) {
						$selling_price = $product->selling_price * (1 - $product->discount / 100);
					} else {
						$selling_price = $product->selling_price;
					}
					$cart = array(
						'id' => $product->id,
						'qty' => $quantity,
						'price' => $selling_price,
						'name' => $product->title,
						'options' => array('image' => $product->image, 'in_stock' => $product->quantity)
					);
					$this->cart->insert($cart);
					$this->session->set_flashdata('success', 'Thêm vào giỏ hàng thành công.');
					redirect(base_url() . 'gio-hang', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Số lượng bạn chọn vượt quá số lượng tồn kho. Vui lòng chọn lại.');
					redirect($_SERVER['HTTP_REFERER']);
				}
			}
		}
	}

	public function update_cart_item()
	{
		$rowid = $this->input->post('rowid');
		$quantity = $this->input->post('quantity');
		foreach ($this->cart->contents() as $items) {
			if ($rowid == $items['rowid']) {
				if ($quantity <= $items['options']['in_stock']) {
					$cart = array(
						'rowid' => $rowid,
						'qty' => $quantity
					);
				} else {
					$cart = array(
						'rowid' => $rowid,
						'qty' => $items['options']['in_stock']
					);
				}
			}
		}
		$this->cart->update($cart);
		// redirect(base_url().'gio-hang', 'refresh');
		redirect($_SERVER['HTTP_REFERER']);
	}

	public function delete_all_cart()
	{
		$this->cart->destroy();
		redirect(base_url() . 'gio-hang', 'refresh');
	}
	public function delete_item($rowid)
	{
		$this->cart->remove($rowid);
		redirect(base_url() . 'gio-hang', 'refresh');
	}

	public function login()
	{
		$this->config->config['pageTitle'] = 'Đăng nhập';
		$this->data['template'] = "pages/login/index";
		$this->load->view("pages/layout/index", $this->data);
	}


	public function profile_user()
	{

		$this->config->config['pageTitle'] = 'Chỉnh sửa thông tin';
		$user_id = $this->getUserOnSession();

		// Kiểm tra nếu user_id hợp lệ
		if ($user_id) {
			$this->load->model('customerModel');
			$profile_user = $this->customerModel->selectCustomerById($user_id['id']);

			if ($profile_user) {
				// echo $profile_user->id;
				$this->load->view('pages/customer/profile_Customer', ['profile_user' => $profile_user]);
			} else {
				echo 'Không tìm thấy thông tin người dùng';
			}
		} else {
			echo 'Không tìm thấy ID người dùng trong session';
		}
	}


	public function editCustomer($user_id)
	{
		$this->config->config['pageTitle'] = 'Chỉnh sửa thông tin người dùng';

		$user_id = $this->getUserOnSession();

		// Kiểm tra nếu user_id hợp lệ
		if ($user_id) {
			$this->load->model('customerModel');
			$profile_user = $this->customerModel->selectCustomerById($user_id['id']);

			if ($profile_user) {
				// echo $profile_user->id;
				$this->load->view('pages/customer/editCustomer', ['profile_user' => $profile_user]);
			} else {
				echo 'Không tìm thấy thông tin người dùng';
			}
		} else {
			echo 'Không tìm thấy ID người dùng trong session';
		}
	}

	public function updateAvatarCustomer($user_id)
	{

		if (!empty($_FILES['image']['name'])) {
			// Upload Image
			$ori_filename = $_FILES['image']['name'];
			$new_name = time() . "-" . str_replace(' ', '-', $ori_filename);
			$config = [
				'upload_path' => './uploads/user',
				'allowed_types' => 'gif|jpg|png|jpeg',
				'file_name' => $new_name
			];
			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('image')) {
				$error = ['error' => $this->upload->display_errors()];
				$this->load->view('pages/customer/profile_Customer', $error);
				return; // Thêm return để dừng việc thực thi tiếp tục
			} else {
				$avatar_filename = $this->upload->data('file_name');
				$data = [
					'avatar' => $avatar_filename
				];
			}
		} else {
			$data = [

			];
		}

		// Kiểm tra giá trị của $data trước khi cập nhật
		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';

		$this->load->model('customerModel');
		$this->customerModel->updateCustomer($user_id, $data);
		$this->session->set_flashdata('success', 'Đã chỉnh sửa thông tin thành công');
		redirect(base_url('profile-user'));
	}


	public function updateCustomer($user_id)
	{
		$this->form_validation->set_rules('username', 'Username', 'trim|required', ['required' => 'Bạn cần điền %s']);
		$this->form_validation->set_rules('email', 'Email', 'trim|required', ['required' => 'Bạn cần điền %s']);
		$this->form_validation->set_rules('address', 'Address', 'trim|required', ['required' => 'Bạn cần chọn %s']);
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required', ['required' => 'Bạn cần chọn %s']);

		if ($this->form_validation->run()) {
			if (!empty($_FILES['image']['name'])) {
				// Upload Image
				$ori_filename = $_FILES['image']['name'];
				$new_name = time() . "-" . str_replace(' ', '-', $ori_filename);
				$config = [
					'upload_path' => './uploads/user',
					'allowed_types' => 'gif|jpg|png|jpeg',
					'file_name' => $new_name
				];
				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('image')) {
					$error = ['error' => $this->upload->display_errors()];
					$this->load->view('customer/update_profile_user', $error);
					return; // Thêm return để dừng việc thực thi tiếp tục
				} else {
					$avatar_filename = $this->upload->data('file_name');
					$data = [
						'username' => $this->input->post('username'),
						'email' => $this->input->post('email'),
						'address' => $this->input->post('address'),
						'phone' => $this->input->post('phone'),
						'avatar' => $avatar_filename
					];
				}
			} else {
				$data = [
					'username' => $this->input->post('username'),
					'email' => $this->input->post('email'),
					'address' => $this->input->post('address'),
					'phone' => $this->input->post('phone'),
				];
			}

			// Kiểm tra giá trị của $data trước khi cập nhật
			// echo '<pre>';
			// print_r($data);
			// echo '</pre>';

			$this->load->model('customerModel');
			$this->customerModel->updateCustomer($user_id, $data);
			$this->session->set_flashdata('success', 'Đã chỉnh sửa thông tin thành công');
			redirect(base_url('profile-user'));
		} else {
			$this->editCustomer($user_id);
		}
	}



	public function logout()
	{
		if (!empty($this->session->userdata('logged_in_customer'))) {
			$this->session->unset_userdata('logged_in_customer');
		}
		$this->session->set_flashdata('success', 'Đăng xuất thành công');
		redirect(base_url('/'));
	}




	public function loginCustomer()
	{
		$max_attempts = 5;
		$lockout_time = 900; // 15 phút

		// Lấy thông tin số lần thử đăng nhập và thời gian lần thử cuối cùng
		$login_attempts = $this->session->userdata('login_attempts') ?? 0;
		$last_attempt_time = $this->session->userdata('last_attempt_time') ?? 0;

		// Kiểm tra xem người dùng có bị khóa không
		if ($login_attempts >= $max_attempts) {
			$time_since_last_attempt = time() - $last_attempt_time;
			if ($time_since_last_attempt < $lockout_time) {
				$remaining_time = $lockout_time - $time_since_last_attempt;
				$this->session->set_flashdata(
					'error',
					'Bạn đã thử đăng nhập quá nhiều lần. Vui lòng thử lại sau ' . ceil($remaining_time / 60) . ' phút.'
				);
				redirect(base_url('/dang-nhap'));
				return;
			} else {
				// Xóa dữ liệu sau khi hết thời gian khóa
				$this->session->unset_userdata(['login_attempts', 'last_attempt_time']);
			}
		}

		// Ràng buộc form validation
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email', [
			'required' => 'Bạn cần cung cấp email.',
			'valid_email' => 'Email không hợp lệ.',
		]);
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]', [
			'required' => 'Bạn cần cung cấp mật khẩu.',
			'min_length' => 'Mật khẩu phải có ít nhất 6 ký tự.',
		]);

		if ($this->form_validation->run()) {
			$email = $this->input->post('email');
			$password = $this->input->post('password');

			$this->load->model('loginModel');
			$result = $this->loginModel->checkLoginCustomer($email);

			// echo '<pre>';
			// print_r($result);
			// echo '</pre>';

			// Kiểm tra kết quả từ cơ sở dữ liệu và xác minh mật khẩu
			if (!empty($result) && password_verify($password, $result[0]->password)) {
				// Xóa dữ liệu đăng nhập không thành công trước đó
				$this->session->unset_userdata(['login_attempts', 'last_attempt_time']);

				$session_array = [
					'id' => $result[0]->id,
					'role_id' => $result[0]->role_id,
					'username' => $result[0]->username,
					'email' => $result[0]->email,
					'phone' => $result[0]->phone,
				];

				// Chuyển hướng dựa trên quyền
				if ($result[0]->role_id == 1) {
					// echo '<pre>';
					// print_r($session_array);
					// echo '</pre>';
					$this->session->set_userdata('logged_in_admin', $session_array);
					$this->session->set_flashdata('success', 'Đăng nhập thành công');
					redirect(base_url('dashboard'));
				} else {
					$this->session->set_userdata('logged_in_customer', $session_array);
					$this->session->set_flashdata('success', 'Đăng nhập thành công');
					redirect(base_url('/'));
				}
			} else {
				// Tăng số lần thử đăng nhập và cập nhật thời gian
				$login_attempts++;
				$this->session->set_userdata('login_attempts', $login_attempts);
				$this->session->set_userdata('last_attempt_time', time());

				$this->session->set_flashdata('error', 'Email hoặc mật khẩu không chính xác.');
				redirect(base_url('/dang-nhap'));
			}
		} else {
			// Hiển thị lỗi form validation nếu có
			$validation_errors = validation_errors('<div>', '</div>');
			$this->session->set_flashdata('error', $validation_errors);
			redirect(base_url('/dang-nhap'));
		}
	}



	public function dang_ky()
	{
		// Tạo quy tắc ràng buộc cho các trường
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[3]', [
			'required' => 'Bạn cần cung cấp %s',
			'min_length' => 'Tên người dùng phải có ít nhất 3 ký tự',
		]);
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email', [
			'required' => 'Bạn cần cung cấp %s',
			'valid_email' => 'Địa chỉ email không hợp lệ',
		]);
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required|numeric|min_length[10]|max_length[11]', [
			'required' => 'Bạn cần cung cấp %s',
			'numeric' => 'Số điện thoại chỉ được chứa chữ số',
			'min_length' => 'Số điện thoại phải có ít nhất 10 chữ số',
			'max_length' => 'Số điện thoại không được quá 11 chữ số',
		]);
		$this->form_validation->set_rules('address', 'Address', 'trim|required', [
			'required' => 'Bạn cần cung cấp %s',
		]);
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]', [
			'required' => 'Bạn cần cung cấp %s',
			'min_length' => 'Mật khẩu phải có ít nhất 6 ký tự',
		]);

		if ($this->form_validation->run()) {
			// Lấy giá trị email từ form
			$email = $this->input->post('email');

			$this->load->model('loginModel');
			$email_exists = $this->loginModel->checkEmailExists($email);
			// echo $email_exists;
			if ($email_exists) {
				$this->session->set_flashdata('error', 'Email đã được sử dụng. Vui lòng sử dụng email khác.');
				redirect(base_url('dang-nhap'));
			}


			$username = $this->input->post('username');
			$password = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
			$phone = $this->input->post('phone');
			$address = $this->input->post('address');

			// Tạo mã token và thời gian hết hạn
			$letters = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);
			$numbers = sprintf("%06d", rand(0, 999999));
			$token = $letters . $numbers;
			$date_created = Carbon\Carbon::now('Asia/Ho_Chi_Minh')->addMinutes(10);

			// Dữ liệu để lưu vào cơ sở dữ liệu
			$data = [
				'username' => $username,
				'email' => $email,
				'password' => $password,
				'phone' => $phone,
				'address' => $address,
				'avatar' => 'User-avatar.png',
				'token' => $token,
				'role_id' => 2,
				'date_created' => $date_created
			];

			// Lưu dữ liệu vào cơ sở dữ liệu
			$result = $this->loginModel->newCustomer($data);

			if ($result) {
				// Gửi email kích hoạt tài khoản
				$fullURL = base_url() . 'kich-hoat-tai-khoan/?email=' . $email;
				$to_mail = $email;
				$subject = 'Thông báo đăng ký tài khoản thành công';
				$message = 'Click vào đường link để kích hoạt tài khoản: ' . $fullURL . '
				Nhập mã xác thực sau: ' . $token;
				$this->send_mail($to_mail, $subject, $message);
				$this->session->set_flashdata('success', 'Đăng ký tài khoản thành công. Vui lòng kiểm tra email để kích hoạt tài khoản.');
			} else {
				$this->session->set_flashdata('error', 'Đăng ký tài khoản thất bại. Vui lòng thử lại.');
			}

			redirect(base_url('dang-nhap'));
		} else {
			$this->session->set_flashdata('error', validation_errors('<div>', '</div>'));
			redirect(base_url('dang-nhap'));
		}
	}




	public function kich_hoat_tai_khoan()
	{
		if (isset($_GET['email'])) {
			$email = $_GET['email'];
			$data['email'] = $email;

			$this->data['template'] = "pages/auth/verify_token";
			$this->load->view("pages/layout/index", $this->data);
		} else {
			$this->session->set_flashdata('error', 'Thông tin kích hoạt không hợp lệ.');
			redirect(base_url('dang-nhap'));
		}
	}

	public function verify_token()
	{
		$email = $this->input->post('email');
		$token = $this->input->post('token');
		$this->load->model('indexModel');
		$customer = $this->indexModel->getCustomerToken($email);

		$is_valid = false;
		$time_now = Carbon\Carbon::now('Asia/Ho_Chi_Minh');

		if ($customer && $token == $customer->token && $customer->date_created > $time_now) {
			$letters = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);
			$numbers = sprintf("%06d", rand(0, 999999));
			$new_token = $letters . $numbers;

			$data_customer = [
				'status' => 1,
				'token' => $new_token
			];

			$this->indexModel->activeCustomerAndUpdateNewToken($email, $data_customer);
			$this->session->set_flashdata('success', 'Kích hoạt tài khoản thành công, mời bạn đăng nhập lại');
			$is_valid = true;
		} else {
			$this->session->set_flashdata('error', 'Đường dẫn hoặc mã đã hết hạn. Vui lòng thực hiện lại');
		}

		if (!$is_valid) {
			$this->session->set_flashdata('error', 'Mã xác thực không đúng. Vui lòng kiểm tra lại.');
		}

		redirect(base_url('dang-nhap'));
	}



	// Quên mật khẩu
	public function forgot_password_layout()
	{
		$this->data['template'] = "pages/forgot-password/index";
		$this->load->view("pages/layout/index", $this->data);
	}


	public function confirm_forgot_password()
	{
		$email = $this->input->post('email');
		$phone = $this->input->post('phone');
		$this->load->model('customerModel');
		$data['get_customer'] = $this->customerModel->getCustomerByEmailAndPhone($email, $phone);

		if (!empty($data['get_customer'])) {
			if ($data['get_customer']->email == $email && $data['get_customer']->phone == $phone) {
				$letters = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);
				$numbers = sprintf("%06d", rand(0, 999999));
				$new_token = $letters . $numbers;
				$date_created = Carbon\Carbon::now('Asia/Ho_Chi_Minh')->addMinutes(10);

				$update_data = [
					'token' => $new_token,
					'date_created' => $date_created
				];

				$result = $this->customerModel->updateTokenCustomer($update_data, $email, $phone);
				if ($result) {
					$fullURL = base_url() . 'lay-lai-mat-khau/?email=' . $email . '&phone=' . $phone;
					$to_mail = $email;
					$subject = 'Lấy lại mật khẩu';
					$message = 'Click vào đường link để xác thực lấy lại mật khẩu: ' . $fullURL . '. Nhập mã xác thực sau: <b>' . $new_token . '</b>';

					$this->send_mail($to_mail, $subject, $message);
					$this->session->set_flashdata('success', 'Vui lòng kiểm tra email: <b>' . $email . '</b> và làm theo hướng dẫn');

					redirect(base_url('dang-nhap'));
				} else {
					$this->session->set_flashdata('error', 'Không thể gửi mã xác thực. Vui lòng thử lại');
					redirect(base_url('forgot-password-layout'));
				}
			} else {
				$this->session->set_flashdata('error', 'Thông tin email hoặc số điện thoại không khớp.');
				redirect(base_url('forgot-password-layout'));
			}
		} else {
			$this->session->set_flashdata('error', 'Không tìm thấy khách hàng với thông tin cung cấp.');
			redirect(base_url('forgot-password-layout'));
		}
	}



	public function lay_lai_mat_khau()
	{
		if (isset($_GET['email']) && $_GET['phone']) {
			$email = $_GET['email'];
			$phone = $_GET['phone'];
			$data['email'] = $email;
			$data['phone'] = $phone;

			
			$this->data['template'] = "pages/auth/verify-token-forget-password";
			$this->load->view("pages/layout/index", $this->data);
		} else {
			$this->session->set_flashdata('error', 'Thông tin kích hoạt không hợp lệ.');
			redirect(base_url('dang-nhap'));
		}
	}

	public function verify_token_forget_password()
	{
		$email = $this->input->post('email');
		$phone = $this->input->post('phone');
		$token = $this->input->post('token');
		$this->load->model('indexModel');
		$customer = $this->indexModel->getCustomerToken($email);

		$is_valid = false;
		$time_now = Carbon\Carbon::now('Asia/Ho_Chi_Minh');

		if ($customer && $token == $customer->token && $customer->date_created > $time_now) {
			$letters = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);
			$numbers = sprintf("%06d", rand(0, 999999));
			$new_token = $letters . $numbers;

			$this->session->set_userdata('reset_email', $email);
			$this->session->set_userdata('reset_phone', $phone);
			$this->session->set_userdata('reset_token', $new_token);

			$update_data = [
				'token' => $new_token
			];

			$this->load->model('customerModel');
			$this->customerModel->updateTokenCustomer($update_data, $email, $phone);
			$this->session->set_flashdata('success', 'Xác thực thành công');

			$is_valid = true;
			redirect(base_url('nhap-mat-khau-moi'));
		}

		if (!$is_valid) {
			$this->session->set_flashdata('error', 'Mã xác thực hoặc đường dẫn không đúng. Vui lòng thực hiện lại.');
			redirect(base_url('forgot-password-layout'));
		}
		redirect(base_url('dang-nhap'));
	}







	public function nhap_mat_khau_moi()
	{

		$email = $this->session->userdata('reset_email');
		$phone = $this->session->userdata('reset_phone');

		$data['email'] = $email;
		$data['phone'] = $phone;
		if ($email && $phone) {
			$this->data = $data;
			$this->data['template'] = "pages/auth/enterNewPassword";
			$this->load->view("pages/layout/index", $this->data);
		} else {
			$this->session->set_flashdata('error', 'Mật khẩu bạn đã được đổi không thể quay lại');
			redirect(base_url('dang-nhap'));
		}
	}

	public function enterNewPassword()
	{

		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email', ['required' => 'Bạn cần cung cấp %s', 'valid_email' => 'Địa chỉ email không hợp lệ']);
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required', ['required' => 'Bạn cần cung cấp %s']);
		$this->form_validation->set_rules('password', 'Password', 'trim|required', ['required' => 'Bạn cần cung cấp %s']);

		if ($this->form_validation->run() == TRUE) {
			$email = $this->input->post('email');
			$phone = $this->input->post('phone');
			$password = $this->input->post('password');
			$token_on_session = $this->session->userdata('reset_token');

			$this->load->model('indexModel');
			$customer = $this->indexModel->getCustomerToken($email);

			if ($token_on_session == $customer->token) {
				$letters = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);
				$numbers = sprintf("%06d", rand(0, 999999));
				$new_token = $letters . $numbers;

				// Sử dụng hàm password_hash với hai tham số: mật khẩu và thuật toán
				$update_data = [
					'token' => $new_token,
					'password' => password_hash($password, PASSWORD_DEFAULT),
				];

				$this->load->model('customerModel');
				$result = $this->customerModel->updateCustomerForgotPassword($email, $phone, $update_data);

				if ($result == 1) {
					$this->session->unset_userdata('reset_email');
					$this->session->unset_userdata('reset_phone');
					$this->session->unset_userdata('reset_token');
					$this->session->set_flashdata('success', 'Cập nhật mật khẩu thành công, xin mời bạn đăng nhập lại');
					redirect(base_url('dang-nhap'));
				} else {
					$this->session->set_flashdata('error', 'Có lỗi xảy ra, vui lòng thực hiện lại');
				}


			} else {
				$this->session->set_flashdata('error', 'Không thể thay đổi mật khẩu, bạn đã thay đổi trước đó, vui lòng thực hiện lại');
				redirect(base_url('dang-nhap'));
			}
		} else {
			// $email = $this->input->post('email');
			// $phone = $this->input->post('phone');
			$this->session->set_flashdata('error', 'Vui lòng nhập đúng và đầy đủ thông tin');
			redirect(base_url('nhap-mat-khau-moi'));
		}
	}

	public function confirmPassword()
	{
		$this->checkLogin();
		$user = $this->getUserOnSession();
		// echo '<pre>';
		// print_r($user);
		// echo '</pre>';
		$this->data = $user;
		$this->data['template'] = "pages/customer/confirmPassword";
		$this->load->view("pages/layout/index", $this->data);
	}

	public function enterPasswordNow()
	{
		$max_attempts = 5;
		$lockout_time = 900; // 15 phút

		// Lấy thông tin số lần thử đăng nhập và thời gian lần thử cuối cùng
		$login_attempts = $this->session->userdata('login_attempts') ?? 0;
		$last_attempt_time = $this->session->userdata('last_attempt_time') ?? 0;

		// Kiểm tra xem người dùng có bị khóa không
		if ($login_attempts >= $max_attempts) {
			$time_since_last_attempt = time() - $last_attempt_time;
			if ($time_since_last_attempt < $lockout_time) {
				$remaining_time = $lockout_time - $time_since_last_attempt;
				$this->session->set_flashdata(
					'error',
					'Bạn đã thử đăng nhập quá nhiều lần. Vui lòng thử lại sau ' . ceil($remaining_time / 60) . ' phút.'
				);
				redirect(base_url('/dang-nhap'));
				return;
			} else {
				// Xóa dữ liệu sau khi hết thời gian khóa
				$this->session->unset_userdata(['login_attempts', 'last_attempt_time']);
			}
		}

		// Ràng buộc form validation
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email', [
			'required' => 'Bạn cần cung cấp email.',
			'valid_email' => 'Email không hợp lệ.',
		]);
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]', [
			'required' => 'Bạn cần cung cấp mật khẩu.',
			'min_length' => 'Mật khẩu phải có ít nhất 6 ký tự.',
		]);

		if ($this->form_validation->run()) {
			$email = $this->input->post('email');
			$password = $this->input->post('password');

			$this->load->model('loginModel');
			$result = $this->loginModel->checkLoginCustomer($email);

			// echo '<pre>';
			// print_r($result);
			// echo '</pre>';

			// Kiểm tra kết quả từ cơ sở dữ liệu và xác minh mật khẩu
			if (!empty($result) && password_verify($password, $result[0]->password)) {

				$this->session->unset_userdata(['login_attempts', 'last_attempt_time']);
				$this->session->set_flashdata('success', 'Kiểm tra email và nhập mã xác thực');
				redirect(base_url('change-password'));


			} else {
				// Tăng số lần thử đăng nhập và cập nhật thời gian
				$login_attempts++;
				$this->session->set_userdata('login_attempts', $login_attempts);
				$this->session->set_userdata('last_attempt_time', time());

				$this->session->set_flashdata('error', 'Mật khẩu không chính xác, vui lòng thử lại.');
				redirect(base_url('/confirmPassword'));
			}
		} else {
			// Hiển thị lỗi form validation nếu có
			$validation_errors = validation_errors('<div>', '</div>');
			$this->session->set_flashdata('error', $validation_errors);
			redirect(base_url('/confirmPassword'));
		}
	}





	public function change_password()
	{
		$this->checkLogin();
		$results = $this->getUserOnSession();
		$email = $results['email'];
		$phone = $results['phone'];

		$letters = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);
		$numbers = sprintf("%06d", rand(0, 999999));
		$new_token = $letters . $numbers;
		$date_created = Carbon\Carbon::now('Asia/Ho_Chi_Minh')->addMinute(10);
		$update_data = [
			'token' => $new_token,
			'date_created' => $date_created
		];
		$this->load->model('customerModel');
		$result = $this->customerModel->updateTokenCustomer($update_data, $email, $phone);
		if ($result) {
			$to_mail = $email;
			$subject = 'Đổi mật khẩu mới';
			$message = 'Mã xác thực của bạn là: ' . $new_token;

			$this->send_mail($to_mail, $subject, $message);
			$this->session->set_flashdata('success', 'Mã xác thực đã được gửi về email của bạn, vui lòng kiểm tra email: <b>' . $email . '</b>');

			// Chuyển đến trang nhập mã xác thực
			redirect(base_url('nhap-ma-xac-thuc'));
		} else {
			$this->session->set_flashdata('error', 'Không thể gửi mã xác thực. Vui lòng thử lại');
			redirect(base_url('profile-user'));
		}
	}


	public function nhap_ma_xac_thuc()
	{
		$this->checkLogin();

		$results = $this->getUserOnSession();
		$data['email'] = $results['email'];
		$data['phone'] = $results['phone'];

		$this->data = $data;
		$this->data['template'] = "pages/auth/changePass_verify";
		$this->load->view("pages/layout/index", $this->data);
	}

	public function change_password_verify_token()
	{
		$this->checkLogin();

		$email = $this->input->post('email');
		$phone = $this->input->post('phone');
		$token = $this->input->post('token');
		$this->load->model('customerModel');
		$data['get_customer'] = $this->customerModel->getCustomerByEmailAndPhone($email, $phone);

		$time_now = Carbon\Carbon::now('Asia/Ho_Chi_Minh');
		if ($data['get_customer'] && $token == $data['get_customer']->token && $data['get_customer']->date_created > $time_now) {
			$letters = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);
			$numbers = sprintf("%06d", rand(0, 999999));
			$new_token = $letters . $numbers;

			$update_data = [
				'token' => $new_token
			];

			$this->load->model('customerModel');
			$this->customerModel->updateTokenCustomer($update_data, $email, $phone);

			if (!empty($this->session->userdata('password_updated'))) {
				$this->session->unset_userdata('password_updated');
			}

			$this->session->set_flashdata('success', 'Xác thực thành công');
			redirect(base_url('cap-nhat-mat-khau-moi'));
		} else {
			$this->session->set_flashdata('error', 'Mã xác thực hoặc đường dẫn không đúng. Vui lòng thực hiện lại.');
			redirect($_SERVER['HTTP_REFERER']);
		}
	}



	public function cap_nhat_mat_khau_moi()
	{
		if (!empty($this->session->userdata('password_updated'))) {
			$this->session->set_flashdata('error', 'Bạn đã thay đổi mật khẩu trước đó, hãy thực hiện lại');
			redirect(base_url('profile-user'));
		} else {
			$results = $this->getUserOnSession();
			$email = $results['email'];
			$phone = $results['phone'];

			if ($email && $phone) {
				$data['email'] = $email;
				$data['phone'] = $phone;

				$this->data = $data;
				$this->data['template'] = "pages/customer/changePassword";
				$this->load->view("pages/layout/index", $this->data);
			} else {
				$this->session->set_flashdata('error', 'Mật khẩu bạn đã được đổi không thể quay lại');
				redirect(base_url('dang-nhap'));
			}
		}

	}

	public function changePassword()
	{

		if (!empty($this->session->userdata('password_updated'))) {
			// echo "Đã thay đổi";
			$this->session->set_flashdata('error', 'Bạn đã thay đổi mật khẩu trước đó, hãy thực hiện lại');
			redirect(base_url('profile-user'));
		} else {
			// echo "Chưa thay đổi";
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email', [
				'required' => 'Bạn cần cung cấp %s',
				'valid_email' => 'Địa chỉ email không hợp lệ'
			]);
			$this->form_validation->set_rules('phone', 'Phone', 'trim|required', ['required' => 'Bạn cần cung cấp %s']);
			$this->form_validation->set_rules('password', 'Password', 'trim|required', ['required' => 'Bạn cần cung cấp %s']);

			if ($this->form_validation->run() == TRUE) {
				$email = $this->input->post('email');
				$phone = $this->input->post('phone');
				$password = $this->input->post('password');

				$letters = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);
				$numbers = sprintf("%06d", rand(0, 999999));
				$new_token = $letters . $numbers;



				$update_data = [
					'token' => $new_token,
					'password' => password_hash($password, PASSWORD_DEFAULT),
				];

				$this->load->model('customerModel');
				$result = $this->customerModel->updateCustomerChangePassword($email, $phone, $update_data);
				if ($result) {
					$this->session->set_flashdata('success', 'Cập nhật mật khẩu thành công, xin mời bạn đăng nhập lại');

					$this->session->set_userdata('password_updated', TRUE);

					$this->session->unset_userdata('logged_in_customer');
					redirect(base_url('dang-nhap'));

				} else {
					$this->session->set_flashdata('error', 'Có lỗi xảy ra, vui lòng thực hiện lại');
					redirect(base_url('profile-user'));
				}

			} else {
				$this->session->set_flashdata('error', 'Vui lòng nhập đúng và đầy đủ thông tin');
				redirect(base_url('nhap-mat-khau-moi'));
			}
		}
	}

	// AI
	public function AI()
	{
		// Cấu hình tiêu đề trang
		$this->config->config['pageTitle'] = 'AI Chẩn đoán bệnh';

		// Cấu hình CORS (nên thực hiện ở middleware)
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
		header('Access-Control-Allow-Headers: Content-Type, Authorization');

		// Kiểm tra phương thức request
		$disease_name = $this->input->post('disease_name') ?: null;

		// Tải model sản phẩm và tìm sản phẩm theo tên bệnh
		$this->load->model('productModel');
		$products = $disease_name ? $this->productModel->getProductsByDisease($disease_name) : [];

		if ($this->input->is_ajax_request()) {
			// AJAX request: trả về danh sách sản phẩm dưới dạng HTML
			if (!empty($products)) {
				echo '<h2 class="title text-center">Bạn có thể sử dụng các loại thuốc này để điều trị bệnh: ' . htmlspecialchars($disease_name) . '</h2>';
				foreach ($products as $product) {
					?>
					<form action="<?= base_url('add-to-cart') ?>" method="POST">
						<input type="hidden" value="<?php echo $product->id ?>" name="product_id">
						<input type="hidden" value="1" name="quantity">
						<div class="col-sm-4">
							<div class="product-image-wrapper">
								<div class="single-products">
									<div class="productinfo text-center">
										<img src="<?= base_url('uploads/product/' . htmlspecialchars($product->image)) ?>"
											alt="<?= htmlspecialchars($product->title) ?>" />
										<h2><?= number_format($product->selling_price, 0, ',', '.') ?> VND</h2>
										<p><?= htmlspecialchars($product->title) ?></p>
										<a href="<?= base_url('san-pham/' . $product->id . '/' . $product->slug) ?>"
											class="btn btn-default add-to-cart">
											<i class="fa fa-eye"></i> Details
										</a>
										<button type="submit" class="btn btn-fefault cart">
											<i class="fa fa-shopping-cart"></i>
											Add to cart
										</button>
									</div>
								</div>
							</div>
						</div>
					</form>
					<?php
				}
			} else {
				echo '<p class="text-center">Không tìm thấy sản phẩm nào liên quan đến bệnh này.</p>';
			}
			return;
		}


		$this->data['disease_name'] = $disease_name;
		$this->data['products_by_disease'] = $products;

		// Load các view
		$this->load->view('pages/component/header', $this->data);
		$this->load->view('AI/AI_page', $this->data);
		$this->load->view('pages/component/footer');
	}



	public function comment_send()
	{
		$data = [
			'name' => $this->input->post('name_comment'),
			'email' => $this->input->post('email_comment'),
			'comment' => $this->input->post('comment'),
			'product_id_comment' => $this->input->post('pro_id_cmt'),
			'status' => 0,
			'date_cmt' => Carbon\Carbon::now('Asia/Ho_Chi_Minh')
		];
		$result = $this->indexModel->commentSend($data);

	}






}