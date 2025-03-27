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
 */


class sliderController extends CI_Controller
{

	public function checkLogin()
	{
		if (!$this->session->userdata('logged_in_admin')) {
			redirect(base_url('dang_nhap'));
		}
	}

	public function index()
	{
		$this->config->config['pageTitle'] = 'List Banner';
		$this->load->model('sliderModel');
		$data['slider'] = $this->sliderModel->selectSlider();

		// echo '<pre>';
		// print_r($data['slider']);
		// echo '</pre>';
		$data['template'] = "slider/index";
		$data['title'] = "Danh sách banner";
		$this->load->view("admin-layout/admin-layout", $data);
	}

	public function createSlider()
	{
		$data['template'] = "slider/storeSlider";
		$data['title'] = "Thêm mới Banner";
		$this->load->view("admin-layout/admin-layout", $data);
	}

	public function storeSlider()
	{
		$this->form_validation->set_rules('title', 'Title', 'trim|required', ['required' => 'Bạn cần diền %s']);


		if ($this->form_validation->run()) {

			$ori_filename = $_FILES['image']['name'];
			$new_name = time() . "" . str_replace(' ', '-', $ori_filename);
			$config = [
				'upload_path' => './uploads/sliders',
				'allowed_types' => 'gif|jpg|png|jpeg',
				'file_name' => $new_name
			];
			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('image')) {
				$data['error'] = $this->upload->display_errors();
				$data['template'] = "slider/storeSlider";
				$data['title'] = "Thêm mới Banner";
				$this->load->view("admin-layout/admin-layout", $data);
			} else {
				$slider_filename = $this->upload->data('file_name');
				$data = [
					'title' => $this->input->post('title'),
					'image' => $slider_filename,
					'status' => $this->input->post('status'),
				];
				$this->load->model('sliderModel');
				$this->sliderModel->insertSlider($data);
				$this->session->set_flashdata('success', 'Đã thêm Slider thành công');
				redirect(base_url('slider/list'));
			}
		} else {
			$this->createSlider();
		}
	}

	public function editSlider($id)
	{
		$this->config->config['pageTitle'] = 'Update Banner';
		$this->load->model('sliderModel');
		$data['slider'] = $this->sliderModel->selectSliderById($id);
		$data['template'] = "slider/editSlider";
		$data['title'] = "Chỉnh sửa banner";
		$this->load->view("admin-layout/admin-layout", $data);
	}

	public function updateSlider($id)
	{
		$this->form_validation->set_rules('title', 'Title', 'trim|required', ['required' => 'Bạn cần điền %s']);

		if ($this->form_validation->run()) {
			if (!empty($_FILES['image']['name'])) {
				// Upload Image
				$ori_filename = $_FILES['image']['name'];
				$new_name = time() . "" . str_replace(' ', '-', $ori_filename);
				$config = [
					'upload_path' => './uploads/sliders',
					'allowed_types' => 'gif|jpg|png|jpeg',
					'file_name' => $new_name
				];
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('image')) {
					$data['error'] = $this->upload->display_errors();
					$data['template'] = "slider/editSlider";
					$data['title'] = "Chỉnh sửa Banner";
					$this->load->view("admin-layout/admin-layout", $data);
				} else {
					$slider_filename = $this->upload->data('file_name');
					$data = [
						'title' => $this->input->post('title'),
						'image' => $slider_filename,
						'status' => $this->input->post('status'),
					];
				}
			} else {
				$data = [
					'title' => $this->input->post('title'),
					'status' => $this->input->post('status'),
				];
			}

			$this->load->model('sliderModel');
			$this->sliderModel->updateSlider($id, $data);
			$this->session->set_flashdata('success', 'Đã chỉnh sửa Slider thành công');
			redirect(base_url('slider/list'));
		} else {
			$this->editSlider($id);
		}
	}


	public function deleteSlider($id)
	{
		$this->load->model('sliderModel');
		$this->sliderModel->deleteSlider($id);
		$this->session->set_flashdata('success', 'Đã xoá Slider thành công');
		redirect(base_url('slider/list'));
	}
}
