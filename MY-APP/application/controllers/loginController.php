<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	class loginController extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			
		}

		public function index()
		{
			$this->config->config['pageTitle'] = 'Login';
			$this->load->view("loginAdmin/header");
			$this->load->view("login/indexLogin");
			$this->load->view("loginAdmin/footer");
		}
		public function register_admin()
		{
			$this->config->config['pageTitle'] = 'Register';
			$this->load->view("loginAdmin/header");
			$this->load->view("register_admin/indexRegister");
			$this->load->view("loginAdmin/footer");
		}
		

		public function insert_admin(){
			$this->form_validation->set_rules('username', 'Username', 'trim|required', ['required' => 'Bạn cần cung cấp username']);
			$this->form_validation->set_rules('email', 'Email', 'trim|required', ['required' => 'Bạn cần cung cấp email']);
			$this->form_validation->set_rules('password', 'Password', 'trim|required', ['required' => 'Bạn cần cung cấp mật khẩu']);

			if ($this->form_validation->run()) {
				$username = $this->input->post('username');
				$email = $this->input->post('email');
				$password = password_hash($this->input->post('password'), PASSWORD_BCRYPT);

				$data = [
					'username' => $username,
					'email' => $email,
					'password' => $password,
					'role_id' => 1,
					'status' => 0
				];

				$this->load->model('loginModel');
				$result = $this->loginModel->newUserAdmin($data);
				if($result){
					$this->session->set_flashdata('success', 'Đăng ký thành công');
					redirect(base_url('login'));
				} else{
					$this->session->set_flashdata('error', 'Đăng ký thất bại');
					redirect(base_url('login'));
				}
			}
			else{
				$this->loginUser();
			}
		}


		public function loginAdmin(){
			$this->form_validation->set_rules('email', 'Email', 'trim|required', ['required' => 'Bạn cần cung cấp email']);
			$this->form_validation->set_rules('password', 'Password', 'trim|required', ['required' => 'Bạn cần cung cấp mật khẩu']);
		
			if ($this->form_validation->run()) {
				$email = $this->input->post('email');
				$password = $this->input->post('password');
				$this->load->model('loginModel');
				$result = $this->loginModel->checkLoginAdmin($email);
		
				if($result && password_verify($password, $result[0]->password)){
					$session_array = [
						'id'=> $result[0]->id,
						'username'=> $result[0]->username,
						'email'=> $result[0]->email,
					];
		
					$this->session->set_userdata('logged_in', $session_array);
					$this->session->set_flashdata('success', 'Đăng nhập thành công');
					redirect(base_url('dashboard'));
				} else{
					$this->session->set_flashdata('error', 'Đăng nhập thất bại');
					redirect(base_url('login'));
				}
			}
			else{
				$this->index();
			}
		}
		

		

}
