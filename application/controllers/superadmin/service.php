<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Service extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->model("superadmin/org/service_model");
    }

    public function index() {
        
    }

    public function show_services() {
        $service_list = array();
        $resulted_service_list = $this->service_model->get_all_services();
        if (!empty($resulted_service_list)) {
            if (property_exists($resulted_service_list, "result")) {
                $service_list = $resulted_service_list->result;
            }
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
            if (property_exists($serviceInfo, "title") != FALSE) {
                $title = $serviceInfo->title;
            }
            if (property_exists($serviceInfo, "id") != FALSE) {
                $service_id = $serviceInfo->id;
            }

            $result_event = $this->service_model->update_service($service_id, $title);
            if (property_exists($result_event, "responseCode") != FALSE) {
                $response_code = $result_event->responseCode;
                if ($response_code == RESPONSE_CODE_SUCCESS) {
                    $response['message'] = "Service is updated successfully.";
                } else {
                    $response['message'] = 'Error while updating a service.';
                }
            }
            echo json_encode($response);
            return;
        }
        $result_event = $this->service_model->get_service_info($service_id);
        if (property_exists($result_event, "result")) {
            $service_info = $result_event->result;
        }
        $this->data['app'] = SERVICE_APP;
        $this->data['service_info'] = json_encode($service_info);
        $this->template->load(null, "superadmin/service/update_service", $this->data);
    }

}
