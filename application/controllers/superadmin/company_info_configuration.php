<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Company_info_configuration extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->library('upload');
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in()) {
            redirect('superadmin/auth/login', 'refresh');
        }
    }

    public function index() {
        $this->data['app'] = COMPANY_INFO_CONFIGURATION_APP;
        $this->template->load(SUPER_ADMIN_TEMPLATE, "superadmin/company_info_configuration/index", $this->data);
    }

    public function add_company_info() {
        if (file_get_contents("php://input") != null) {
            $response = array();
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "companyInfo") != FALSE) {
                $companyInfo = $requestInfo->companyInfo;
                if (property_exists($companyInfo, "companyName")) {
                    $title = $companyInfo->companyName;
                }
                $additional_data = array(
                    'title' => $title
                );
                $this->load->model('superadmin/org/company_info_configuration_model');
                $result_event = $this->company_info_configuration_model->add_company_info($additional_data);
                if ($result_event > -1) {
                    $response["message"] = "Company Name add successfully";
                }
            }
            echo json_encode($response);
            return;
        }
    }

    public function upload_picture($image_data, $image_name, $image_path) {
        $response = array();
        list(, $data) = explode(',', $image_data);
        $final_image_data = base64_decode($data);
        $file = $image_path . $image_name;
        $result = file_put_contents($file, $final_image_data);
        if ($result != null) {
            $response['status'] = 1;
        } else {
            $response['status'] = 0;
        }
        return $response;
    }

    public function add_company_logo() {
        $response = array();
        $image_data = $this->input->post('imageData');
        $user_id = $this->session->userdata('user_id');
        //temp picture upload to server for profile picture
        $company_logo = COMPANY_LOGO_NAME . '.png';
        $company_logo_temp_path = COMPANY_LOGO_PATH;
        $result = $this->upload_picture($image_data, $company_logo, COMPANY_LOGO_PATH);

        // image upload to user album for database save 
        $temp_src_name = $company_logo . '_' . now() . '.png';
        if ($result['status'] != 0) {
            //resize profile picture 
            $file_temp = $company_logo_temp_path . $company_logo;
            $this->load->library('superadmin/org/super_utils');
            $resize_response = $this->utils->resize_image($file_temp, COMPANY_LOGO_PATH_W333_H44 . $company_logo, COMPANY_LOGO_H44, COMPANY_LOGO_W333);
            if ($resize_response != 0) {
                $response["message"] = "Company logo add successfully";
                $response["status"] = "1";
            }
        }
        echo json_encode($response);
        return;
    }

}
