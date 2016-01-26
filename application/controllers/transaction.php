<?php

class Transaction extends Role_Controller {

    public $message_codes = array();

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->library('transaction_library');
        $this->load->library('utils');
        $this->load->config('ion_auth', TRUE);
        $this->message_codes = $this->config->item('message_codes', 'ion_auth');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    public function index() {
        
    }

    public function bkash() {
        $response = array();
        if (file_get_contents("php://input") != null) {
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "bkashInfo") != FALSE) {
                $bkashInfo = $requestInfo->bkashInfo;
                if (property_exists($bkashInfo, "number")) {
                    $cell_no = $bkashInfo->number;
                } else {
                    $response["message"] = "Cell Number is Required !!";
                    echo json_encode($response);
                    return;
                }
                if (property_exists($bkashInfo, "amount")) {
                    $amount = $bkashInfo->amount;
                } else {
                    $response["message"] = "Amount is Required !!";
                    echo json_encode($response);
                    return;
                }
            }
            if (isset($amount)) {
                if ($amount < BKASH_MINIMUM_CASH_IN_AMOUNT || $amount > BKASH_MAXIMUM_CASH_IN_AMOUNT) {
                    $response["message"] = "Please Give a Valid Amount !!";
                    echo json_encode($response);
                    return;
                }
            }
            if ($this->utils->cell_number_validation($cell_no) == FALSE) {
                $response["message"] = "Please Enter a Valid Cell Number !!";
                echo json_encode($response);
                return;
            }
            $api_key = API_KEY_BKASH_CASHIN;
            $description = "test";
            $transaction_id = "";
            $transaction_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'transaction_id' => $transaction_id,
                'service_id' => SERVICE_TYPE_ID_BKASH_CASHIN,
                'amount' => $amount,
                'cell_no' => $cell_no,
                'description' => $description,
                'status_id' => TRANSACTION_STATUS_ID_SUCCESSFUL
            );
            if ($this->transaction_model->add_transaction($api_key, $transaction_data) !== FALSE) {
                $response['message'] = "Transaction is created successfully.";
            } else {
                $response['message'] = 'Error while processing the transaction.';
            }

            echo json_encode($response);
            return;
        }
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_list = $this->transaction_model->where($where)->get_user_transaction_list(array(SERVICE_TYPE_ID_BKASH_CASHIN), 10)->result_array();
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->template->load('admin/templates/admin_tmpl', 'transaction/bkash/index', $this->data);
    }

    public function dbbl() {
        $response = array();
        if (file_get_contents("php://input") != null) {
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "dbblInfo")) {
                $dbblInfo = $requestInfo->dbblInfo;
                if (property_exists($dbblInfo, "number")) {
                    $cell_no = $dbblInfo->number;
                } else {
                    $response["message"] = "Cell Number is Required !!";
                    echo json_encode($response);
                    return;
                }
                if (property_exists($dbblInfo, "amount")) {
                    $amount = $dbblInfo->amount;
                } else {
                    $response["message"] = "Amount is Required !!";
                    echo json_encode($response);
                    return;
                }
            }
            if (isset($amount)) {
                if ($amount < DBBL_MINIMUM_CASH_IN_AMOUNT || $amount > DBBL_MAXIMUM_CASH_IN_AMOUNT) {
                    $response["message"] = "Please Give a Valid Amount !!";
                    echo json_encode($response);
                    return;
                }
            }
            if ($this->utils->cell_number_validation($cell_no) == FALSE) {
                $response["message"] = "Please Enter a Valid Cell Number !!";
                echo json_encode($response);
                return;
            }
            $api_key = API_KEY_DBBL_CASHIN;
            $description = "test";
            $transaction_id = "";
            $transaction_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'transaction_id' => $transaction_id,
                'service_id' => SERVICE_TYPE_ID_DBBL_CASHIN,
                'amount' => $amount,
                'cell_no' => $cell_no,
                'description' => $description,
                'status_id' => TRANSACTION_STATUS_ID_SUCCESSFUL
            );
            if ($this->transaction_model->add_transaction($transaction_data) !== FALSE) {
                $response['message'] = "Transaction is created successfully.";
            } else {
                $response['message'] = 'Error while processing the transaction.';
            }

            echo json_encode($response);
            return;
        }
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_list = $this->transaction_model->where($where)->get_user_transaction_list(array(SERVICE_TYPE_ID_DBBL_CASHIN), 10)->result_array();
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->template->load('admin/templates/admin_tmpl', 'transaction/dbbl/index', $this->data);
    }

    public function mcash() {

        $response = array();
        if (file_get_contents("php://input") != null) {
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "mCashInfo")) {
                $mCashInfo = $requestInfo->mCashInfo;
                if (property_exists($mCashInfo, "number")) {
                    $cell_no = $mCashInfo->number;
                } else {
                    $response["message"] = "Cell Number is Required !!";
                    echo json_encode($response);
                    return;
                }
                if (property_exists($mCashInfo, "amount")) {
                    $amount = $mCashInfo->amount;
                } else {
                    $response["message"] = "Amount is Required !!";
                    echo json_encode($response);
                    return;
                }
            }
            if (isset($amount)) {
                if ($amount < MCASH_MINIMUM_CASH_IN_AMOUNT || $amount > MCASH_MAXIMUM_CASH_IN_AMOUNT) {
                    $response["message"] = "Please Give a Valid Amount !!";
                    echo json_encode($response);
                    return;
                }
            }
            if ($this->utils->cell_number_validation($cell_no) == FALSE) {
                $response["message"] = "Please Enter a Valid Cell Number !!";
                echo json_encode($response);
                return;
            }
            $api_key = API_KEY_MCASH_CASHIN;
            $description = "test";
            $transaction_id = "";
            $transaction_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'transaction_id' => $transaction_id,
                'service_id' => SERVICE_TYPE_ID_DBBL_CASHIN,
                'amount' => $amount,
                'cell_no' => $cell_no,
                'description' => $description,
                'status_id' => TRANSACTION_STATUS_ID_SUCCESSFUL
            );
            if ($this->transaction_model->add_transaction($transaction_data) !== FALSE) {
                $response['message'] = "Transaction is created successfully.";
            } else {
                $response['message'] = 'Error while processing the transaction.';
            }

            echo json_encode($response);
            return;
        }
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_list = $this->transaction_model->where($where)->get_user_transaction_list(array(SERVICE_TYPE_ID_MCASH_CASHIN), 10)->result_array();
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->template->load('admin/templates/admin_tmpl', 'transaction/mcash/index', $this->data);
    }

    public function ucash() {
        $response = array();
        if (file_get_contents("php://input") != null) {
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "uCashInfo")) {
                $uCashInfo = $requestInfo->uCashInfo;
                if (property_exists($uCashInfo, "number")) {
                    $cell_no = $uCashInfo->number;
                } else {
                    $response["message"] = "Cell Number is Required !!";
                    echo json_encode($response);
                    return;
                }
                if (property_exists($uCashInfo, "amount")) {
                    $amount = $uCashInfo->amount;
                } else {
                    $response["message"] = "Amount is Required !!";
                    echo json_encode($response);
                    return;
                }
            }
            if (isset($amount)) {
                if ($amount < UCASH_MINIMUM_CASH_IN_AMOUNT || $amount > UCASH_MAXIMUM_CASH_IN_AMOUNT) {
                    $response["message"] = "Please Give a Valid Amount !!";
                    echo json_encode($response);
                    return;
                }
            }
            if ($this->utils->cell_number_validation($cell_no) == FALSE) {
                $response["message"] = "Please Enter a Valid Cell Number !!";
                echo json_encode($response);
                return;
            }
            $api_key = API_KEY_UCASH_CASHIN;
            $description = "test";
            $transaction_id = "";
            $transaction_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'transaction_id' => $transaction_id,
                'service_id' => SERVICE_TYPE_ID_UCASH_CASHIN,
                'amount' => $amount,
                'cell_no' => $cell_no,
                'description' => $description,
                'status_id' => TRANSACTION_STATUS_ID_SUCCESSFUL
            );
            if ($this->transaction_model->add_transaction($transaction_data) !== FALSE) {
                $response['message'] = "Transaction is created successfully.";
            } else {
                $response['message'] = 'Error while processing the transaction.';
            }

            echo json_encode($response);
            return;
        }

        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_list = $this->transaction_model->where($where)->get_user_transaction_list(array(SERVICE_TYPE_ID_UCASH_CASHIN), 10)->result_array();
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->template->load('admin/templates/admin_tmpl', 'transaction/ucash/index', $this->data);
    }

    public function topup() {
        $response = array();
        if (file_get_contents("php://input") != null) {
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "uCashInfo")) {
                $uCashInfo = $requestInfo->uCashInfo;
                if (property_exists($uCashInfo, "number")) {
                    $cell_no = $uCashInfo->number;
                } else {
                    $response["message"] = "Cell Number is Required !!";
                    echo json_encode($response);
                    return;
                }
                if (property_exists($uCashInfo, "amount")) {
                    $amount = $uCashInfo->amount;
                } else {
                    $response["message"] = "Amount is Required !!";
                    echo json_encode($response);
                    return;
                }
            }
            if (isset($amount)) {
                if ($amount < UCASH_MINIMUM_CASH_IN_AMOUNT || $amount > UCASH_MAXIMUM_CASH_IN_AMOUNT) {
                    $response["message"] = "Please Give a Valid Amount !!";
                    echo json_encode($response);
                    return;
                }
            }
            if ($this->utils->cell_number_validation($cell_no) == FALSE) {
                $response["message"] = "Please Enter a Valid Cell Number !!";
                echo json_encode($response);
                return;
            }
            $api_key = API_KEY_UCASH_CASHIN;
            $description = "test";
            $transaction_id = "";
            $transaction_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'transaction_id' => $transaction_id,
                'service_id' => SERVICE_TYPE_ID_UCASH_CASHIN,
                'amount' => $amount,
                'cell_no' => $cell_no,
                'description' => $description,
                'status_id' => TRANSACTION_STATUS_ID_SUCCESSFUL
            );
            if ($this->transaction_model->add_transaction($transaction_data) !== FALSE) {
                $response['message'] = "Transaction is created successfully.";
            } else {
                $response['message'] = 'Error while processing the transaction.';
            }

            echo json_encode($response);
            return;
        }
//        $this->data['message'] = "";
//        $this->form_validation->set_error_delimiters("<div style='color:red'>", '</div>');
//        $this->form_validation->set_rules('number', 'Number', 'xss_clean|required');
//        $this->form_validation->set_rules('amount', 'Amount', 'xss_clean|required');
//        if ($this->input->post('submit_create_transaction')) {
//            if ($this->form_validation->run() == true) {
//                $amount = $this->input->post('amount');
//                $api_key = API_KEY_UKASH_CASHIN;
//                $cell_no = $this->input->post('number');
//                $description = "test";
//
//                $transaction_id = "";
                //calling the webservice for the transaction
//                $this->curl->create(WEBSERVICE_URL_CREATE_TRANSACTION);
//                $this->curl->post(array("APIKey" => $api_key, "amount" => $amount, "cell_no" => $cell_no, "description" => $description ));
//                $result_event = json_decode($this->curl->execute());
//                if (!empty($result_event)) {
//                    $response_code = '';
//                    if (property_exists($result_event, "responseCode") != FALSE) {
//                        $response_code = $result_event->responseCode;
//                    }
//                    if($response_code == RESPONSE_CODE_SUCCESS)
//                    {
//                        if (property_exists($result_event, "result") != FALSE) {
//                            $transaction_info = $result_event->result;
//                            $transaction_id = $transaction_info->transactionId;
//                            if(empty($transaction_id) || $transaction_id == "")
//                            {
//                                //Handle a message if there is no transaction id
//                                $this->data['message'] = $this->message_codes[$response_code];
//                            }
//                            else
//                            {
//                $transaction_data = array(
//                    'user_id' => $this->session->userdata('user_id'),
//                    'transaction_id' => $transaction_id,
//                    'service_id' => $this->input->post('topup_operator_list'),
//                    'amount' => $this->input->post('amount'),
//                    'cell_no' => $this->input->post('number'),
//                    'description' => $description,
//                    'status_id' => TRANSACTION_STATUS_ID_SUCCESSFUL
//                );
//                if ($this->transaction_model->add_transaction($transaction_data) !== FALSE) {
//                    $this->data['message'] = "Transaction is created successfully.";
//                } else {
//                    $this->data['message'] = 'Error while processing the transaction.';
//                }
//                            }
//                        }
//                        else
//                        {
//                            //Handle a message if there is no result even though response code is success
//                            $this->data['message'] = $this->message_codes[$response_code];
//                        }
//                    }
//                    else
//                    {
//                        //Add a message for this response code
//                        $this->data['message'] = $this->message_codes[$response_code];
//                    }
//                } 
//                else
//                {
//                    $this->data['message'] = 'Server is unavailable. Please try later.';
//                }
//            } else {
//                $this->data['message'] = validation_errors();
//            }
//        }
        
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_list = $this->transaction_model->where($where)->get_user_transaction_list(array(SERVICE_TYPE_ID_TOPUP_GP, SERVICE_TYPE_ID_TOPUP_ROBI, SERVICE_TYPE_ID_TOPUP_BANGLALINK, SERVICE_TYPE_ID_TOPUP_AIRTEL, SERVICE_TYPE_ID_TOPUP_TELETALK), 10)->result_array();
        $this->data['transaction_list'] = json_encode($transaction_list);
        $topup_type_list= array(
            '1' => 'Prepaid',
            '2' => 'Postpaid'
        );
        //this list will be dynamic based on api subscription of the user
      $topup_operator_list =  array(
            SERVICE_TYPE_ID_TOPUP_GP => 'GP',
            SERVICE_TYPE_ID_TOPUP_ROBI => 'Robi',
            SERVICE_TYPE_ID_TOPUP_BANGLALINK => 'BanglaLink',
            SERVICE_TYPE_ID_TOPUP_AIRTEL => 'Airtel',
            SERVICE_TYPE_ID_TOPUP_TELETALK => 'TeleTalk'
        );
        
        $this->data['topup_type_list'] = json_encode($topup_type_list);
        $this->data['topup_operator_list'] = json_encode($topup_operator_list);
       
        $this->template->load('admin/templates/admin_tmpl', 'transaction/topup/index', $this->data);
    }

}
