<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class History extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->library('superadmin/org/history_library');
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in()) {
            redirect('superadmin/auth/login', 'refresh');
        }
    }

    public function index() {
        
    }

    public function get_service_volume_rank_list() {
        if (file_get_contents("php://input") != null) {
            $response = array();
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "searchInfo") != FALSE) {
                $searchInfo = $requestInfo->searchInfo;
                if (property_exists($searchInfo, "startDate")) {
                    $start_date = $searchInfo->startDate;
                }
                if (property_exists($searchInfo, "endDate")) {
                    $end_date = $searchInfo->endDate;
                }
            }
            $result_event = $this->history_library->get_service_volume_rank_list($start_date, $end_date);
//            var_dump($result_event);exit;
            if (!empty($result_event)) {
//                if (property_exists($result_event_event, "responseCode") != FALSE) {
//                    if ($result_event_event->responseCode == RESPONSE_CODE_SUCCESS) {
//                        $service_volumn_rank_list = $result_event->result;
                $response['service_volumn_rank_list'] = $result_event;
            } else {
                $response['message'] = "Error While Processing  ";
            }
//                } else {
//                    $response['message'] = "Server Error While Processing  ";
//                }
//            } else {
//                $response['message'] = "Server Error While Processing  ";
//            }
            echo json_encode($response);
            return;
        }
    }

    public function get_top_customer_list() {
        if (file_get_contents("php://input") != null) {
            $response = array();
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "searchInfo") != FALSE) {
                $searchInfo = $requestInfo->searchInfo;
                if (property_exists($searchInfo, "startDate")) {
                    $start_date = $searchInfo->startDate;
                }
                if (property_exists($searchInfo, "endDate")) {
                    $end_date = $searchInfo->endDate;
                }
                if (property_exists($searchInfo, "serviceId")) {
                    $service_id = $searchInfo->serviceId;
                }
            }
            $result_event = $this->history_library->get_top_customer_list($start_date, $end_date, $service_id);
//            var_dump($result_event);exit;
            if (!empty($result_event)) {
//                if (property_exists($result_event_event, "responseCode") != FALSE) {
//                    if ($result_event_event->responseCode == RESPONSE_CODE_SUCCESS) {
//                        $service_volumn_rank_list = $result_event->result;
                $response['top_customer_list'] = $result_event;
            } else {
                $response['message'] = "Error While Processing  ";
            }
//                } else {
//                    $response['message'] = "Server Error While Processing  ";
//                }
//            } else {
//                $response['message'] = "Server Error While Processing  ";
//            }
            echo json_encode($response);
            return;
        }
    }

    public function get_service_profit_rank_list() {
        if (file_get_contents("php://input") != null) {
            $response = array();
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "searchInfo") != FALSE) {
                $searchInfo = $requestInfo->searchInfo;
                if (property_exists($searchInfo, "startDate")) {
                    $start_date = $searchInfo->startDate;
                }
                if (property_exists($searchInfo, "endDate")) {
                    $end_date = $searchInfo->endDate;
                }
            }
            $result_event = $this->history_library->get_service_profit_rank_list($start_date, $end_date);
            if (!empty($result_event)) {
                $profit_rank_list = $result_event;
                foreach ($profit_rank_list as $profit_rank_info) {
                    $service_profit_info = array();
                    if ($profit_rank_info->service_id == SERVICE_TYPE_ID_BKASH_CASHIN) {
                        $service_profit_info['service'] = 'BKash';
                    } else if ($profit_rank_info->service_id == SERVICE_TYPE_ID_DBBL_CASHIN) {
                        $service_profit_info['service'] = 'BDDL';
                    } else if ($profit_rank_info->service_id == SERVICE_TYPE_ID_MCASH_CASHIN) {
                        $service_profit_info['service'] = 'M-Cash';
                    } else if ($profit_rank_info->service_id == SERVICE_TYPE_ID_UCASH_CASHIN) {
                        $service_profit_info['service'] = 'U-Cash';
                    } else if ($profit_rank_info->service_id == SERVICE_TYPE_ID_TOPUP_GP) {
                        $service_profit_info['service'] = 'Topup-Gp';
                    } else if ($profit_rank_info->service_id == SERVICE_TYPE_ID_TOPUP_ROBI) {
                        $service_profit_info['service'] = 'Topup-Robi';
                    } else if ($profit_rank_info->service_id == SERVICE_TYPE_ID_TOPUP_BANGLALINK) {
                        $service_profit_info['service'] = 'Topup-BLink';
                    } else if ($profit_rank_info->service_id == SERVICE_TYPE_ID_TOPUP_AIRTEL) {
                        $service_profit_info['service'] = 'Topup-Airtel';
                    } else if ($profit_rank_info->service_id == SERVICE_TYPE_ID_TOPUP_TELETALK) {
                        $service_profit_info['service'] = 'Topup-Teletalk';
                    }
                    $service_profit_info['service_percentage'] = (int) $profit_rank_info->service_percentage;
                    $service_profit_list[] = $service_profit_info;
                }
                $response['profit_rank_list'] = $service_profit_list;
            } else {
                $response['message'] = "Error While Processing  ";
            }
//                } else {
//                    $response['message'] = "Server Error While Processing  ";
//                }
//            } else {
//                $response['message'] = "Server Error While Processing  ";
//            }
            echo json_encode($response);
            return;
        }
    }

    public function test() {
        $this->load->view('superadmin/test');
    }

}
