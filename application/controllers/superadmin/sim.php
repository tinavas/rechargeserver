<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sim extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->model('superadmin/org/sim_model');
    }

    /*
     * Home page of sim
     * @author nazmul hasan on 11th June 2016
     */

    public function index() {
        $sim_list = $this->sim_model->get_sim_list();
        $this->data['sim_list'] = json_encode($sim_list);
        $this->data['app'] = SIM_APP;
        $this->template->load(null, "superadmin/sims/index", $this->data);
    }

    /*
     * This method will add a new sim
     * @author nazmul hasan on 11th June 2016
     */

    public function add_sim() {
        if (file_get_contents("php://input") != null) {
            $response = array();
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "simInfo") != FALSE) {
                $additional_sim_info = array();
                $sim_info = $requestInfo->simInfo;
                if (property_exists($sim_info, "simNo")) {
                    $sim_no = $sim_info->simNo;
                    $this->load->library('superadmin/org/super_utils');
                    if ($this->super_utils->cell_number_validation($sim_no) == FALSE) {
                        $response["message"] = "Please Enter a Valid Cell Number !!Supported format is now 01XXXXXXXXX. ";
                        echo json_encode($response);
                        return;
                    }
                    $additional_sim_info['sim_no'] = $sim_no;
                } else {
                    $response["message"] = " Please give a sim number! Sim Number is Required !!";
                    echo json_encode($response);
                    return;
                }
                if (property_exists($sim_info, "identifier")) {
                    $additional_sim_info['identifier'] = $sim_info->identifier;
                } else {
                    $response["message"] = " Please give an identifier ! Identifier is Required !!";
                    echo json_encode($response);
                    return;
                }
                if (property_exists($sim_info, "status")) {
                    $additional_sim_info['status'] = $sim_info->status;
                }
                if (property_exists($sim_info, "status")) {
                    $additional_sim_info['description'] = $sim_info->description;
                }


                $sim_service_list = array();
                if (property_exists($sim_info, "serviceInfoList")) {
                    $sim_service_list = $sim_info->serviceInfoList;
                } else {
                    $response["message"] = " Please assaign at least one service !";
                    echo json_encode($response);
                    return;
                }
                $result_event = $this->sim_model->add_sim($additional_sim_info, $sim_service_list);
                if (!empty($result_event)) {
                    if (property_exists($result_event, "responseCode") != FALSE) {
                        if ($result_event->responseCode == RESPONSE_CODE_SUCCESS) {
                            $response['message'] = 'Sim is added successfully.';
                        } else {
                            $response['message'] = 'Failed to add sim.';
                        }
                    } else {
                        $response['message'] = 'Error while adding the sim. Please try again later.';
                    }
                } else {
                    $response['message'] = 'Server is unaviable right now. Please try again later.';
                }
                echo json_encode($response);
                return;
            } else {
                $response['message'] = 'Invalid input formate. Please try again later.';
                echo json_encode($response);
                return;
            }
        }


        $this->load->model('superadmin/org/service_model');
        $service_list_array = $this->service_model->get_all_services()->result_array();
        foreach ($service_list_array as $service_info) {
            if ($service_info['service_id'] == SERVICE_TYPE_ID_BKASH_CASHIN) {
                $service_info['selected'] = true;
            }
            $service_list[] = $service_info;
        }
        $sim_category_list = $this->sim_model->get_sim_category_list();
        $sim_status_list = $this->sim_model->get_sim_status_list();
        $this->data['service_list'] = json_encode($service_list);
        $this->data['sim_category_list'] = $sim_category_list;
        $this->data['sim_status_list'] = $sim_status_list;
        $this->data['app'] = SIM_APP;
        $this->template->load(null, "superadmin/sims/create_sim", $this->data);
    }

    /*
     * This method will edit existing sim info
     * @author nazmul hasan on 11th June 2016
     */

    public function edit_sim($sim_no) {
        if (file_get_contents("php://input") != null) {
            $response = array();
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "simInfo") != FALSE) {
                $updated_sim_info = array();
                $sim_info = $requestInfo->simInfo;
                if (property_exists($sim_info, "simNo")) {
                    $sim_no = $sim_info->simNo;
                    $this->load->library('superadmin/org/super_utils');
                    if ($this->super_utils->cell_number_validation($sim_no) == FALSE) {
                        $response["message"] = "Please Enter a Valid Cell Number !!Supported format is now 01XXXXXXXXX. ";
                        echo json_encode($response);
                        return;
                    }
                    $updated_sim_info['sim_no'] = $sim_no;
                } else {
                    $response["message"] = " Please give a sim number! Sim Number is Required !!";
                    echo json_encode($response);
                    return;
                }
                if (property_exists($sim_info, "identifier")) {
                    $updated_sim_info['identifier'] = $sim_info->identifier;
                } else {
                    $response["message"] = " Please give an identifier ! Identifier is Required !!";
                    echo json_encode($response);
                    return;
                }
                if (property_exists($sim_info, "status")) {
                    $updated_sim_info['status'] = $sim_info->status;
                }
                if (property_exists($sim_info, "status")) {
                    $updated_sim_info['description'] = $sim_info->description;
                }


                $sim_service_list = array();
                if (property_exists($sim_info, "serviceInfoList")) {
                    $sim_service_list = $sim_info->serviceInfoList;
                } else {
                    $response["message"] = " Please assaign at least one service !";
                    echo json_encode($response);
                    return;
                }

                $result_event = $this->sim_model->edit_sim($updated_sim_info, $sim_service_list);
                if (!empty($result_event)) {
                    if (property_exists($result_event, "responseCode") != FALSE) {
                        if ($result_event->responseCode == RESPONSE_CODE_SUCCESS) {
                            $response['message'] = 'Sim informations is updated successfully.';
                        } else {
                            $response['message'] = 'Failed to update sim informations.';
                        }
                    } else {
                        $response['message'] = 'Error while updating the sim informations. Please try again later.';
                    }
                } else {
                    $response['message'] = 'Server is unaviable right now. Please try again later.';
                }
            } else {
                $response['message'] = 'Invalid input formate. Please try again later.';
            }
            echo json_encode($response);
            return;
        }
        $sim_info = $this->sim_model->get_sim_info($sim_no);
        $sim_status_list = $this->sim_model->get_sim_status_list();
        $sim_service_list = $sim_info['simServiceList'];
        $this->load->model('superadmin/org/service_model');
        $sim_category_list = $this->sim_model->get_sim_category_list();
        $service_list_array = $this->service_model->get_all_services()->result_array();
        $service_list = array();
        foreach ($service_list_array as $service_info) {
            foreach ($sim_service_list as $sim_service) {
                $service_info['categoryId'] = SIM_CATEGORY_TYPE_AGENT;
                if ($sim_service->serviceId == $service_info['service_id']) {
                    $service_info['selected'] = true;
                    $service_info['categoryId'] = $sim_service->categoryId;
                    $service_info['currentBalance'] = $sim_service->currentBalance;
                }
            }

            $service_list[] = $service_info;
        }

        $this->data['sim_info'] = json_encode($sim_info);
        $this->data['service_list'] = json_encode($service_list);
        $this->data['sim_category_list'] = $sim_category_list;
        $this->data['sim_status_list'] = $sim_status_list;
        $this->data['app'] = SIM_APP;
        $this->template->load(null, "superadmin/sims/edit_sim", $this->data);
    }

    public function get_sim_balance($sim_no) {
        $this->sim_model->check_sim_balance($sim_no);
        $this->data['app'] = SIM_APP;
        $this->template->load(null, "superadmin/sims/update_sim_balance", $this->data);
    }

    public function get_sms_list() {
        $sim_no = 0;
        $offset = SMS_PAGE_DEFAULT_OFFSET;
        $limit = SMS_PAGE_DEFAULT_LIMIT;
        $from_date = 0;
        $to_date = 0;
        if (file_get_contents("php://input") != null) {
            $response = array();
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "searchInfo") != FALSE) {
                $search_param = $requestInfo->searchInfo;
                if (property_exists($search_param, "simNo") != FALSE) {
                    $sim_no = $search_param->simNo;
                }
                if (property_exists($search_param, "fromDate") != FALSE) {
                    $from_date = $search_param->fromDate;
                }
                if (property_exists($search_param, "toDate") != FALSE) {
                    $to_date = $search_param->toDate;
                }
                if (property_exists($search_param, "offset") != FALSE) {
                    $offset = $search_param->offset;
                }
                if (property_exists($search_param, "limit") != FALSE) {
                    $limit_status = $search_param->limit;
                    if ($limit_status != FALSE) {
                        $limit = 0;
                    }
                }
            }
            $sms_info_list = $this->sim_model->get_sms_list($sim_no, $offset, $limit, $from_date, $to_date);
            $response['sms_list'] = $sms_info_list['sms_list'];
            $response['total_counter'] = $sms_info_list['total_counter'];
            echo json_encode($response);
            return;
        }
        $sms_info_list = $this->sim_model->get_sms_list($sim_no, $offset, $limit, $from_date, $to_date);
        $this->data['sms_list'] = $sms_info_list['sms_list'];
        $this->data['total_counter'] = $sms_info_list['total_counter'];
        $this->load->library('superadmin/org/super_utils');
        $current_date = $this->super_utils->get_current_date();
        $this->data['current_date'] = $current_date;
        $this->data['app'] = SIM_APP;
        $this->template->load(null, "superadmin/sms/index", $this->data);
    }

}
