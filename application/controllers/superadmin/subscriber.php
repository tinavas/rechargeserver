<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Subscriber extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->model("superadmin/org/subscriber_model");
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in()) {
            redirect('superadmin/auth/login', 'refresh');
        }
    }

    public function index() {
        
    }

    public function show_subscribers() {
        $subscriber_list = array();
        $resulted_subscriber_list = $this->subscriber_model->get_all_subscribers();
        if (!empty($resulted_subscriber_list)) {
            if (property_exists($resulted_subscriber_list, "result")) {
                $subscriber_list = $resulted_subscriber_list->result;
            }
        }
        $this->data['subscriber_list'] = $subscriber_list;
        $this->data['app'] = SUBSCRIBER_APP;
        $this->template->load(null, "superadmin/subscriber/index", $this->data);
    }

    public function create_subscriber() {
        $response = array();
        if (file_get_contents("php://input") != null) {
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "subscriberInfo") != FALSE) {
                $subscriberInfo = $requestInfo->subscriberInfo;
            }
            $subscriber_info = new stdClass();
            $subscriber_info->registrationDate = $subscriberInfo->registrationDate;
            $subscriber_info->expiredDate = $subscriberInfo->expiredDate;
            $subscriber_info->maxMembers = $subscriberInfo->maxMembers;
            $subscriber_info->ipAddress = $subscriberInfo->ipAddress;
            $subscriber_info->referenceUserName = $subscriberInfo->refUserName;
            $result_event = $this->subscriber_model->create_subscriber($subscriber_info);
            if (property_exists($result_event, "responseCode") != FALSE) {
                $response_code = $result_event->responseCode;
                if ($response_code == RESPONSE_CODE_SUCCESS) {
                    $response['message'] = "SUbscriber is created successfully.";
                } else {
                    $response['message'] = 'Error while creating a subscriber.';
                }
            }
            echo json_encode($response);
            return;
        }
        $this->data['app'] = SUBSCRIBER_APP;
        $this->template->load(null, "superadmin/subscriber/create_subscriber", $this->data);
    }

    public function update_subscriber($subscriber_id = 0) {
        $response = array();
        if (file_get_contents("php://input") != null) {
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "subscriberInfo") != FALSE) {
                $subscriberInfo = $requestInfo->subscriberInfo;
            }
            $subscriber_info = new stdClass();
            $subscriber_info->userId = $subscriber_id;
            $subscriber_info->registrationDate = $subscriberInfo->registrationDate;
            $subscriber_info->expiredDate = $subscriberInfo->expiredDate;
            $subscriber_info->maxMembers = $subscriberInfo->maxMembers;
            $subscriber_info->ipAddress = $subscriberInfo->ipAddress;
            $result_event = $this->subscriber_model->update_subscriber($subscriber_info);
            if (property_exists($result_event, "responseCode") != FALSE) {
                $response_code = $result_event->responseCode;
                if ($response_code == RESPONSE_CODE_SUCCESS) {
                    $response['message'] = "Subscriber is updated successfully.";
                } else {
                    $response['message'] = 'Error while updating a subscriber.';
                }
            }
            echo json_encode($response);
            return;
        }
        $subscriber_info = $this->subscriber_model->get_subscriber_info($subscriber_id);
        if (property_exists($subscriber_info, "result")) {
            $subscriber_info = $subscriber_info->result;
        }

        $this->data['subscriber_info'] = $subscriber_info;
        $this->data['subscriber_id'] = $subscriber_id;
        $this->data['app'] = SUBSCRIBER_APP;
        $this->template->load(null, "superadmin/subscriber/update_subscriber", $this->data);
    }

}
