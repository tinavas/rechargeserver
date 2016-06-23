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
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, "superadmin/sims/index", $this->data);
    } 
    
    /*
     * This method will add a new sim
     * @author nazmul hasan on 11th June 2016
     */
    public function add_sim()
    {
        $response = array();
        if (file_get_contents("php://input") != null) {
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "simInfo") != FALSE) {
                $sim_info = $requestInfo->simInfo;
                $sim_no = "";
                $identifier = "";
                $description = "";
                $current_balance = 0;
                $status = 0;
                if (property_exists($sim_info, "sim_no")) {
                    $sim_no = $sim_info->sim_no;
                }
                $this->load->library('utils');
                if ($this->utils->cell_number_validation($sim_no) == FALSE) {
                    $response["message"] = "Please Enter a Valid Cell Number !!";
                    echo json_encode($response);
                    return;
                }
                if (property_exists($sim_info, "identifier")) {
                    $identifier = $sim_info->identifier;
                }
                if (property_exists($sim_info, "description")) {
                    $description = $sim_info->description;
                }
                if (property_exists($sim_info, "current_balance")) {
                    $current_balance = $sim_info->current_balance;
                }
                if (property_exists($sim_info, "status")) {
                    $status = $sim_info->status;
                }
                $additional_data = array(
                    'sim_no' => $sim_no,
                    'identifier' => $identifier,
                    'description' => $description,
                    'current_balance' => $current_balance,
                    'status' => $status
                );
            }
            $result_event = $this->sim_model->add_sim($additional_data);
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
            }
            echo json_encode($response);
            return;
        }

        $obj = new stdClass();
        $obj->service_id = SERVICE_TYPE_ID_BKASH_CASHIN;
        $obj->title = "BKash";
        
        $service_list = array(
        );
        $service_list[] = $obj;
        $this->data['service_list'] = json_encode($service_list);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, "superadmin/sims/create_sim", $this->data);
    }
    
    /*
     * This method will edit existing sim info
     * @author nazmul hasan on 11th June 2016
     */
    public function edit_sim($sim_no)
    {
        if (file_get_contents("php://input") != null) {
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "simInfo") != FALSE) {
                $sim_info = $requestInfo->simInfo;
                $sim_no = "";
                $identifier = "";
                $description = "";
                $current_balance = 0;
                $status = 0;
                if (property_exists($sim_info, "sim_no")) {
                    $sim_no = $sim_info->sim_no;
                }
                $this->load->library('utils');
                if ($this->utils->cell_number_validation($sim_no) == FALSE) {
                    $response["message"] = "Please Enter a Valid Cell Number !!";
                    echo json_encode($response);
                    return;
                }
                if (property_exists($sim_info, "identifier")) {
                    $identifier = $sim_info->identifier;
                }
                if (property_exists($sim_info, "description")) {
                    $description = $sim_info->description;
                }
                if (property_exists($sim_info, "current_balance")) {
                    $current_balance = $sim_info->current_balance;
                }
                if (property_exists($sim_info, "status")) {
                    $status = $sim_info->status;
                }
                $additional_data = array(
                    'sim_no' => $sim_no,
                    'identifier' => $identifier,
                    'description' => $description,
                    'current_balance' => $current_balance,
                    'status' => $status
                );
            }
            $result_event = $this->sim_model->edit_sim($additional_data);
            if (!empty($result_event)) {
                if (property_exists($result_event, "responseCode") != FALSE) {
                    if ($result_event->responseCode == RESPONSE_CODE_SUCCESS) {
                        $response['message'] = 'Sim is updated successfully.';
                    } else {
                        $response['message'] = 'Failed to update sim.';
                    }
                } else {
                    $response['message'] = 'Error while updating the sim. Please try again later.';
                }
            }
            echo json_encode($response);
            return;
        }
        $sim_info = $this->sim_model->get_sim_info($sim_no);
        $this->data['sim_info'] = json_encode($sim_info);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, "superadmin/sims/edit_sim", $this->data);
    }
    
    public function get_sim_balance($sim_no)
    {
        $this->sim_model->check_sim_balance($sim_no);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, "superadmin/sims/update_sim_balance", $this->data);
    }
}
