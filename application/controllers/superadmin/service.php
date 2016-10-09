<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Service extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->model("superadmin/org/service_model");
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in()) {
            redirect('superadmin/auth/login', 'refresh');
        }
    }

    public function index() {
        
    }

    public function show_services() {
        $service_list = array();
        $resulted_service_list = $this->service_model->get_all_service_list()->result_array();
        if (!empty($resulted_service_list)) {
            $service_list = $resulted_service_list;
        }
        $this->data['service_list'] = $service_list;
        $this->data['app'] = SERVICE_APP;
        $this->template->load(null, "superadmin/service/index", $this->data);
    }

    public function create_service() {
        $response = array();
        if (file_get_contents("php://input") != null) {
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "serviceInfo") != FALSE) {
                $serviceInfo = $requestInfo->serviceInfo;
            }
            if (property_exists($serviceInfo, "title") != FALSE) {
                $title = $serviceInfo->title;
            }

            $result_event = $this->service_model->create_service($title);
            if (property_exists($result_event, "responseCode") != FALSE) {
                $response_code = $result_event->responseCode;
                if ($response_code == RESPONSE_CODE_SUCCESS) {
                    $response['message'] = "Service is created successfully.";
                } else {
                    $response['message'] = 'Error while creating a service.';
                }
            }
            echo json_encode($response);
            return;
        }
        $this->data['app'] = SERVICE_APP;
        $this->template->load(null, "superadmin/service/create_service", $this->data);
    }

    public function update_service($service_id = 0) {
        $service_info = new stdClass();
        if (file_get_contents("php://input") != null) {
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "serviceInfo") != FALSE) {
                $serviceInfo = $requestInfo->serviceInfo;
            }
            $service_info = array();
            if (property_exists($serviceInfo, "title") != FALSE) {
                $service_info['title'] = $serviceInfo->title;
            }
            if (property_exists($serviceInfo, "id") != FALSE) {
                $service_id = $serviceInfo->id;
            }
            if (property_exists($serviceInfo, "type_id") != FALSE) {
                $service_info['type_id'] = $serviceInfo->type_id;
            }
            if (property_exists($serviceInfo, "transaction_interval") != FALSE) {
                $service_info['transaction_interval'] = $serviceInfo->transaction_interval;
            }

            $result = $this->service_model->update_service($service_id, $service_info);
            if ($result != FALSE) {
                $response['message'] = "Service is updated successfully.";
            } else {
                $response['message'] = 'Error while updating a service.';
            }
            echo json_encode($response);
            return;
        }
        $service_info_array = $this->service_model->get_service_info($service_id)->result_array();
        if (!empty($service_info_array)) {
            $service_info = $service_info_array[0];
        }
        $service_type_list_array = $this->service_model->get_service_type_list()->result_array();
        foreach ($service_type_list_array as $service_type_info) {
            if ($service_info['type_id'] == $service_type_info['id']) {
                $service_type_info['selected'] = true;
            } else {
                $service_type_info['selected'] = false;
            }
            $service_type_list[] = $service_type_info;
        }
        $this->load->library('superadmin/org/super_utils');
        $time_list = $this->super_utils->get_transaction_interval_list();
        $this->data['app'] = SERVICE_APP;
        $this->data['service_info'] = json_encode($service_info);
        $this->data['service_type_list'] = $service_type_list;
        $this->data['time_list'] = $time_list;
        $this->template->load(null, "superadmin/service/update_service", $this->data);
    }

}
