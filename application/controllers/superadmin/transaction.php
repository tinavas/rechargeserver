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

    public function get_transaction_list() {
        $this->load->library("superadmin/org/transaction_library");
        $offset = S_TRANSACTION_PAGE_DEFAULT_OFFSET;
        $limit = S_TRANSACTION_PAGE_DEFAULT_LIMIT;
        $transction_list = array();
        $service_id_list = array();
        if (file_get_contents("php://input") != null) {
            $response = array();
            $status_id_list = array();
            $from_date = 0;
            $to_date = 0;
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "searchInfo") != FALSE) {
                $search_param = $requestInfo->searchInfo;
                if (property_exists($search_param, "fromDate") != FALSE) {
                    $from_date = $search_param->fromDate;
                }
                if (property_exists($search_param, "toDate") != FALSE) {
                    $to_date = $search_param->toDate;
                }
                if (property_exists($search_param, "offset") != FALSE) {
                    $offset = $search_param->offset;
                }
                if (property_exists($search_param, "statusId") != FALSE) {
                    $status_id = $search_param->statusId;
                    $status_id_list = array($status_id);
                }
                if (property_exists($search_param, "limit") != FALSE) {
                    $limit_status = $search_param->limit;
                    if ($limit_status != FALSE) {
                        $limit = 0;
                    }
                }
            }
            $transction_list_array = $this->transaction_library->get_transaction_list($service_id_list, $status_id_list, array(TRANSACTION_PROCESS_TYPE_ID_MANUAL), $from_date, $to_date, $offset, $limit);
            $response['transaction_list'] = $transction_list_array['transaction_list'];
            $response['total_transactions'] = $transction_list_array['total_transactions'];
            echo json_encode($response);
            return;
        }
        $transction_list_array = $this->transaction_library->get_transaction_list($service_id_list, array(TRANSACTION_STATUS_ID_PENDING), array(TRANSACTION_PROCESS_TYPE_ID_MANUAL), 0, 0, $offset, $limit);
        $this->data['transaction_list'] = $transction_list_array['transaction_list'];
        $this->data['total_transactions'] = $transction_list_array['total_transactions'];
        $this->load->library('superadmin/org/Date_utils');
        $current_date = $this->date_utils->get_current_date();
        $this->data['current_date'] = $current_date;
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, "superadmin/transaction/index", $this->data);
    }

    public function update_transaction($transction_id = 0) {
        if (file_get_contents("php://input") != null) {
            $response = array();
            $transction_info = array();
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "transactionInfo") != FALSE) {
                $transactionInfo = $requestInfo->transactionInfo;
                if (property_exists($transactionInfo, "transaction_id")) {
                    $transaction_id = $transactionInfo->transaction_id;
                }
                if (property_exists($transactionInfo, "trx_id_operator")) {
                    $transction_info['trx_id_operator'] = $transactionInfo->trx_id_operator;
                }
                if (property_exists($transactionInfo, "status_id")) {
                    $transction_info['status_id'] = $transactionInfo->status_id;
                }
            }
            $result = $this->transaction_model->update_transction_info($transaction_id, $transction_info);
            if ($result != false) {
                $response['message'] = "Transction is updated successfully.";
            } else {
                $response['message'] = 'Error while updating a service.';
            }
            echo json_encode($response);
            return;
        }
        $transaction_info = array();
        $transaction_info_array = $this->transaction_model->get_transaction_info($transction_id)->result_array();
        if (!empty($transaction_info_array)) {
            $transaction_info = $transaction_info_array[0];
        }
        $transaction_status_list = array();
        $transaction_status_array = $this->transaction_model->get_transaction_status_list()->result_array();
        foreach ($transaction_status_array as $status_info) {
            if ($status_info['id'] == $transaction_info['status_id']) {
                $status_info['selected'] = true;
            } else {
                $status_info['selected'] = false;
            }
            $transaction_status_list[] = $status_info;
        }
        $this->data['transaction_info'] = $transaction_info;
        $this->data['transaction_status_list'] = $transaction_status_list;
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

    public function show_sims() {
        $result_event = $this->transaction_model->get_sim_list();
        $sim_info = new stdClass();
        $sim_info->sim_id = "1";
        $sim_info->sim_no = "01723598606";
        $sim_info->regitration_date = "16-05-16";
        $sim_info->total_balance = "5000";
        $sim_info->status = "1";
        $sim_list = array();
        $sim_list[] = $sim_info;
        $this->data['sim_list'] = json_encode($sim_list);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, "superadmin/sims/index", $this->data);
    }

    public function add_sim() {
        $response = array();
        if (file_get_contents("php://input") != null) {
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "simInfo") != FALSE) {
                $balanceInfo = $requestInfo->simInfo;

                if (property_exists($balanceInfo, "registrationDate")) {
                    $registration_date = $balanceInfo->registrationDate;
                }
                if (property_exists($balanceInfo, "simNumber")) {
                    $sim_no = $balanceInfo->simNumber;
                }
                $this->load->library('utils');
                if ($this->utils->cell_number_validation($sim_no) == FALSE) {
                    $response["message"] = "Please Enter a Valid Cell Number !!";
                    echo json_encode($response);
                    return;
                }
                $selected_service_id_list = array();
                if (property_exists($balanceInfo, "selectedIdList")) {
                    $selected_service_id_list = $balanceInfo->selectedIdList;
                }
                $additional_data = array(
                    'sim_number' => $sim_no,
                    'registration_date' => $registration_date,
                    'service_list' => $selected_service_id_list
                );
            }
            $result_event = $this->transaction_model->add_sim($additional_data);
            if (!empty($result_event)) {
                if (property_exists($result_event_event, "responseCode") != FALSE) {
                    if ($result_event_event->responseCode == RESPONSE_CODE_SUCCESS) {
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
        $obj2 = new stdClass();
        $obj2->service_id = SERVICE_TYPE_ID_DBBL_CASHIN;
        $obj2->title = "DBBL";
        $obj3 = new stdClass();
        $obj3->service_id = SERVICE_TYPE_ID_MCASH_CASHIN;
        $obj3->title = "M-Cash";
        $obj4 = new stdClass();
        $obj4->service_id = SERVICE_TYPE_ID_UCASH_CASHIN;
        $obj4->title = "U-Cash";
        $obj5 = new stdClass();
        $obj5->service_id = SERVICE_TYPE_ID_TOPUP_GP;
        $obj5->title = "Topup-GP";
        $obj6 = new stdClass();
        $obj6->service_id = SERVICE_TYPE_ID_TOPUP_ROBI;
        $obj6->title = "Topup-Robi";
        $obj7 = new stdClass();
        $obj7->service_id = SERVICE_TYPE_ID_TOPUP_BANGLALINK;
        $obj7->title = "Topup-BanglaLink";
        $obj8 = new stdClass();
        $obj8->service_id = SERVICE_TYPE_ID_TOPUP_AIRTEL;
        $obj8->title = "Topup-Airtel";
        $obj9 = new stdClass();
        $obj9->service_id = SERVICE_TYPE_ID_TOPUP_TELETALK;
        $obj9->title = "Topup-Teletalk";

        $service_list = array(
        );
        $service_list[] = $obj;
        $service_list[] = $obj2;
        $service_list[] = $obj3;
        $service_list[] = $obj4;
        $service_list[] = $obj5;
        $service_list[] = $obj6;
        $service_list[] = $obj7;
        $service_list[] = $obj8;
        $service_list[] = $obj9;
        $this->data['service_list'] = json_encode($service_list);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, "superadmin/sims/create_sim", $this->data);
    }

    public function sim_details($sim_no = 0) {
        $obj = new stdClass();
        $obj->service_id = "1";
        $obj->service_title = "Bkash";
        $obj->current_balance = "1000";
        $obj2 = new stdClass();
        $obj2->service_id = "2";
        $obj2->service_title = "BBDL";
        $obj2->current_balance = "5000";
        $sim_service_info_list = array();
        $sim_service_info_list[] = $obj;
        $sim_service_info_list[] = $obj2;
        $this->data['sim_service_info_list'] = json_encode($sim_service_info_list);
        $this->data['sim_no'] = $sim_no;
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, "superadmin/sims/sim_details", $this->data);
    }

    public function edit_sim() {
        $response = array();
        if (file_get_contents("php://input") != null) {
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "simInfo") != FALSE) {
                $balanceInfo = $requestInfo->simInfo;

                if (property_exists($balanceInfo, "registrationDate")) {
                    $registration_date = $balanceInfo->registrationDate;
                }
                if (property_exists($balanceInfo, "simNumber")) {
                    $sim_number = $balanceInfo->simNumber;
                }
                $selected_service_id_list = array();
                if (property_exists($balanceInfo, "selectedIdList")) {
                    $selected_service_id_list = $balanceInfo->selectedIdList;
                }
                $additional_data = array(
                    'sim_number' => $sim_number,
                    'registration_date' => $registration_date,
                    'service_list' => $selected_service_id_list
                );
            }
            $result_event = $this->transaction_model->edit_sim($additional_data);
            if (!empty($result_event)) {
                if (property_exists($result_event_event, "responseCode") != FALSE) {
                    if ($result_event_event->responseCode == RESPONSE_CODE_SUCCESS) {
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
    }

    public function add_service_balance() {
        $response = array();
        if (file_get_contents("php://input") != null) {
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "balanceInfo") != FALSE) {
                $balanceInfo = $requestInfo->balanceInfo;

                if (property_exists($balanceInfo, "amount")) {
                    $amount = $balanceInfo->amount;
                } else {
                    $response['message'] = 'Please give an amount';
                    echo json_encode($response);
                    return;
                }
                if (property_exists($balanceInfo, "serviceId")) {
                    $service_id = $balanceInfo->serviceId;
                }
                if (property_exists($balanceInfo, "serviceId")) {
                    $service_id = $balanceInfo->serviceId;
                }
                if (property_exists($balanceInfo, "simNo")) {
                    $sim_no = $balanceInfo->simNo;
                }
                $additional_data = array(
                    'sim_number' => $sim_no,
                    'service_id' => $service_id,
                    'amount' => $amount
                );
            }
            $result_event = $this->transaction_model->add_service_balance($additional_data);
            if (!empty($result_event)) {
                if (property_exists($result_event_event, "responseCode") != FALSE) {
                    if ($result_event_event->responseCode == RESPONSE_CODE_SUCCESS) {
                        $response['message'] = 'Balance is added successfully.';
                    } else {
                        $response['message'] = 'Failed to add balance.';
                    }
                } else {
                    $response['message'] = 'Error while adding the balance. Please try again later.';
                }
            }
            echo json_encode($response);
            return;
        }
    }

    public function get_sim_service_list() {
        $response = array();
        if (file_get_contents("php://input") != null) {
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "simNumber") != FALSE) {
                $sim_number = $requestInfo->simNumber;
                $result_event = $this->transaction_model->get_sim_service_list($sim_number);
//                if (!empty($result_event)) {
//                    if (property_exists($result_event_event, "responseCode") != FALSE) {
//                        if ($result_event_event->responseCode == RESPONSE_CODE_SUCCESS) {
//                            $response['message'] = 'Balance is added successfully.';
//                        } else {
//                            $response['message'] = 'Failed to add balance.';
//                        }
//                    } else {
//                        $response['message'] = 'Error while adding the balance. Please try again later.';
//                    }
//                }

                $obj = new stdClass();
                $obj->service_id = SERVICE_TYPE_ID_BKASH_CASHIN;
                $obj->title = "BKash";
                $obj->selected = TRUE;
                $obj2 = new stdClass();
                $obj2->service_id = SERVICE_TYPE_ID_DBBL_CASHIN;
                $obj2->title = "DBBL";
                $obj3 = new stdClass();
                $obj3->service_id = SERVICE_TYPE_ID_MCASH_CASHIN;
                $obj3->title = "M-Cash";
                $obj3->selected = TRUE;
                $obj4 = new stdClass();
                $obj4->service_id = SERVICE_TYPE_ID_UCASH_CASHIN;
                $obj4->title = "U-Cash";
                $obj5 = new stdClass();
                $obj5->service_id = SERVICE_TYPE_ID_TOPUP_GP;
                $obj5->title = "Topup-GP";
                $obj5->selected = TRUE;
                $obj6 = new stdClass();
                $obj6->service_id = SERVICE_TYPE_ID_TOPUP_ROBI;
                $obj6->title = "Topup-Robi";
                $obj7 = new stdClass();
                $obj7->service_id = SERVICE_TYPE_ID_TOPUP_BANGLALINK;
                $obj7->title = "Topup-BanglaLink";
                $obj8 = new stdClass();
                $obj8->service_id = SERVICE_TYPE_ID_TOPUP_AIRTEL;
                $obj8->title = "Topup-Airtel";
                $obj9 = new stdClass();
                $obj9->service_id = SERVICE_TYPE_ID_TOPUP_TELETALK;
                $obj9->title = "Topup-Teletalk";

                $service_list = array(
                );
                $service_list[] = $obj;
                $service_list[] = $obj2;
                $service_list[] = $obj3;
                $service_list[] = $obj4;
                $service_list[] = $obj5;
                $service_list[] = $obj6;
                $service_list[] = $obj7;
                $service_list[] = $obj8;
                $service_list[] = $obj9;
                $response['service_list'] = $service_list;

                echo json_encode($response);
                return;
            }
        }
    }

    public function get_sim_transactions() {
        $response = array();
        if (file_get_contents("php://input") != null) {
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
            $result_event = $this->transaction_model->get_sim_transactions($start_date, $end_date);
//                  if (!empty($result_event)) {
//                    if (property_exists($result_event_event, "responseCode") != FALSE) {
//                        if ($result_event_event->responseCode == RESPONSE_CODE_SUCCESS) {
//                            $response['message'] = 'Balance is added successfully.';
//                        } else {
//                            $response['message'] = 'Failed to add balance.';
//                        }
//                    } else {
//                        $response['message'] = 'Error while adding the balance. Please try again later.';
//                    }
//                }
            $obj = new stdClass();
            $obj->transctionId = "1";
            $obj->number = "01717862408";
            $obj->amount = "100";
            $obj->status = "pending";
            $obj->date = "12-12-16";
            $transctionList = array();
            $transctionList[] = $obj;
            $response['transction_list'] = $transctionList;
            echo json_encode($response);
            return;
        }
        $obj = new stdClass();
        $obj->transctionId = "1";
        $obj->number = "01723598606";
        $obj->amount = "50000";
        $obj->status = "successful";
        $obj->date = "12-12-16";
        $transctionList = array();
        $transctionList[] = $obj;
        $this->data['transction_list'] = json_encode($transctionList);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, "superadmin/sims/transactions", $this->data);
    }

    /*
     * This method will load pagination template
     * @author rashida on 26th April 2016 
     */

    function pagination_tmpl_load() {
        $this->load->view('dir_pagination');
    }

}
