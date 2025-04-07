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
 * @property upload $upload
 */


class predictController extends CI_Controller
{

    
    public function yolo_predict_page(){
        $this->config->config['pageTitle'] = 'Chẩn đoán bệnh';
        $data['template'] = "AI/yolo_predict";
        $data['title'] = "Yolo Predict";
        $this->load->view("pages/layout/index", $data);
    }

	
}
