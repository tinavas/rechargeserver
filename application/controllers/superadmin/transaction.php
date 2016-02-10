<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transaction extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->model("superadmin/org/transaction_model");
    }

    public function index() {
        
    }

    public function show_transactions($user_id = 0, $offset = 0, $limit = 0) {
        $offset = 0;
        $limit = 0;
        $transction_list = array();
        $resulted_transction_list = $this->transaction_model->get_all_transactions($user_id, $offset, $limit);
        if (!empty($resulted_transction_list)) {
            if (property_exists($resulted_transction_list, "result")) {
                $transction_list = $resulted_transction_list->result;
            }
        }
        $this->data['transction_list'] = $transction_list;
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, "superadmin/transaction/index", $this->data);
    }

    public function update_transaction($transction_id = 0) {
        $response = array();
        if (file_get_contents("php://input") != null) {
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "transctionInfo") != FALSE) {
                $transctionInfo = $requestInfo->transctionInfo;
            }
            $transction_info = new stdClass();
            if (property_exists($transctionInfo, "transactionId")) {
                $transction_info->transactionId = $transctionInfo->transactionId;
            }
            if (property_exists($transctionInfo, "apikey")) {
                $transction_info->apikey = $transctionInfo->apikey;
            }
            if (property_exists($transctionInfo, "balanceIn")) {
                $transction_info->balanceIn = $transctionInfo->balanceIn;
            }
            if (property_exists($transctionInfo, "balanceOut")) {
                $transction_info->balanceOut = $transctionInfo->balanceOut;
            }
            if (property_exists($transctionInfo, "transactionStatusId")) {
                $transction_info->transactionStatusId = $transctionInfo->transactionStatusId;
            }
            if (property_exists($transctionInfo, "transactionTypeId")) {
                $transction_info->transactionTypeId = $transctionInfo->transactionTypeId;
            }
            if (property_exists($transctionInfo, "cellNumber")) {
                $transction_info->cellNumber = $transctionInfo->cellNumber;
            }
            if (property_exists($transctionInfo, "description")) {
                $transction_info->description = $transctionInfo->description;
            }
            if (property_exists($transctionInfo, "createdOn")) {
                $transction_info->createdOn = $transctionInfo->createdOn;
            }
            if (property_exists($transctionInfo, "modifiedOn")) {
                $transction_info->modifiedOn = $transctionInfo->modifiedOn;
            }
            $result_event = $this->transaction_model->update_transction_info($transction_info);
            if (property_exists($result_event, "responseCode") != FALSE) {
                $response_code = $result_event->responseCode;
                if ($response_code == RESPONSE_CODE_SUCCESS) {
                    $response['message'] = "Transction is updated successfully.";
                } else {
                   $response['message'] = 'Error while updating a service.';
                }
            }
            
            echo json_encode($response);
            return ;
        }
        $transction_info = $this->transaction_model->get_transction_info($transction_id);
        if (property_exists($transction_info, "result")) {
            $transction_info = $transction_info->result;
        }
        $this->data['transction_info'] = $transction_info;
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, "superadmin/transaction/update_transaction", $this->data);
    }

    public function delete_transaction() {
        $response = array();
        $transction_id = $this->input->post('transction_id');
        $result = $this->transaction_model->delete_transaction($transction_id);
        if ($result != false) {
            $response['status'] = 1;
        } else {
            $response['status'] = 0;
        }
        echo json_encode($response);
    }

}
