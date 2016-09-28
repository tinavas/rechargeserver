<?php

class Transaction extends Role_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->library('transaction_library');
        $this->load->library('utils');
        $this->load->model('service_model');
        $this->load->config('ion_auth', TRUE);
        $this->lang->load('auth');
        $this->load->helper('language');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    public function index() {
        //handle code if you want to redirect to any other location
    }

    /*
     * This method will send transaction code to the user via sms or email based on configuration. 
     * //right now we are using this feature for bkash service
     */

    public function send_transaction_code() {
        $response = "";
        $user_id = $this->session->userdata('user_id');
        $permission_exists = FALSE;
        $service_list = $this->service_model->get_user_assigned_services($user_id)->result_array();
        foreach ($service_list as $service_info) {
            //if in future there is new service under bkash then update the logic here
            if ($service_info['service_id'] == SERVICE_TYPE_ID_BKASH_CASHIN) {
                $permission_exists = TRUE;
                $bkash_service_info = $service_info;
            }
        }
        if (!$permission_exists) {
            //you are not allowed to use bkash transaction
            $response = "Sorry !! You are not allowed to use bkash service.";
            echo $response;
            return;
        }
        if ($bkash_service_info['sms_verification'] == 1 || $bkash_service_info['email_verification'] == 1) {
            //transaction verification code is generated here
            $this->load->library('Utils');
            $verification_code = $this->utils->get_transaction_verification_code();
            //Storing code into the database
            $updated_data = array(
                'id' => $bkash_service_info['user_service_id'],
                'verification_code' => $verification_code
            );
            $this->service_model->update_user_rates(array($updated_data));
            if ($bkash_service_info['sms_verification'] == 1) {
                //send verification code via sms to the client
                $response = "Code is sent successfully. Please check your phone.";
            }
            if ($bkash_service_info['email_verification'] == 1) {
//                send verification code via email to the client
                $email = "";
                $profile_info = $this->reseller_model->get_user_info($user_id)->result_array();
                if (!empty($profile_info)) {
                    $email = $profile_info[0]['email'];
                }
                $this->transaction_library->send_email($email, $verification_code);
                $response = "Code is sent successfully. Please check your email.";
            }
        } else {
            $response = "Sorry! You have no permission to use transaction code.";
        }
        echo $response;
    }

    /*
     * This method will process bkash transaction
     * @author nazmul hasan on 24th february 2016
     */

    public function bkash($transaction_id = '') {
        $user_id = $this->session->userdata('user_id');
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        //checking whether user has permission for bkash transaction
        $service_status_type = SERVICE_TYPE_ID_ALLOW_TO_USE_LOCAL_SERVER;
        $permission_exists = FALSE;
        $bkash_service_info = array();
        $service_list = $this->service_model->get_user_assigned_services($user_id)->result_array();
        foreach ($service_list as $service_info) {
            //if in future there is new service under bkash then update the logic here
            if ($service_info['service_id'] == SERVICE_TYPE_ID_BKASH_CASHIN) {
                $permission_exists = TRUE;
                $bkash_service_info = $service_info;
            }
        }
        if (!$permission_exists) {
            //you are not allowed to use bkash transaction
            $this->data['app'] = TRANSCATION_APP;
            $this->data['error_message'] = "Sorry !! You are not allowed to use bkash service.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }
        $service_info_array = $this->service_model->get_service_info_list(array(SERVICE_TYPE_ID_BKASH_CASHIN))->result_array();
        if (!empty($service_info_array)) {
            $service_status_type = $service_info_array[0]['type_id'];
        }
        if (file_get_contents("php://input") != null) {
            $response = array();
            if ($service_status_type == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION) {
                $response["message"] = "Sorry !! Bkash service is unavailable right now! please try again later!.";
                echo json_encode($response);
                return;
            }
            $transaction_id = "";
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "bkashInfo") != FALSE) {
                $bkashInfo = $requestInfo->bkashInfo;
                if (property_exists($bkashInfo, "number")) {
                    $cell_no = $bkashInfo->number;
                } else {
                    $response["message"] = " Please give a Cell number! Cell Number is Required !!";
                    echo json_encode($response);
                    return;
                }
                $validation_result = $this->transaction_library->check_transaction_interval_validation(array($cell_no), array(SERVICE_TYPE_ID_BKASH_CASHIN), $where);
                if ($validation_result['validation_flag'] != FALSE) {
                    $response["message"] = "Sorry !! Bkash service is unavailable right now! please try again later!.";
                    echo json_encode($response);
                    return;
                }
                if (property_exists($bkashInfo, "amount")) {
                    $amount = $bkashInfo->amount;
                } else {
                    $response["message"] = "Please give an Amount! Amount is Required !!";
                    echo json_encode($response);
                    return;
                }
                if (property_exists($bkashInfo, "code")) {
                    $code = $bkashInfo->code;
                    //verify code here
                    if ($bkash_service_info['sms_verification'] == 1 || $bkash_service_info['email_verification'] == 1) {
                        if ($code != $bkash_service_info['verification_code']) {
                            $response['message'] = "Invalid verification code!!!!.";
                            echo json_encode($response);
                            return;
                        }
                    } else if ($bkash_service_info['code'] != "") {
                        if ($code != $bkash_service_info['code']) {
                            $response['message'] = "Invalid code!!!!.";
                            echo json_encode($response);
                            return;
                        }
                    }
                } else {
                    $response["message"] = "Please give a Varification Code! Code is Required !!";
                    echo json_encode($response);
                    return;
                }
                if (property_exists($bkashInfo, "transaction_id") && $bkashInfo->transaction_id != '') {
                    //update transaction
                    $transaction_id = $bkashInfo->transaction_id;
                    $transaction_info_array = $this->transaction_model->get_transaction_info($transaction_id)->result_array();
                    if (!empty($transaction_info_array) && !$transaction_info_array[0]['editable']) {
                        //restrict to edit
                        $response['message'] = "Sorry! You are not allowed to edit this transaction.";
                        echo json_encode($response);
                        return;
                    }
                } else {
                    //create transaction
                }
            }
            if (isset($amount)) {
                if ($amount < BKASH_MINIMUM_CASH_IN_AMOUNT) {
                    $response["message"] = "Please give a minimum amount TK. " . BKASH_MINIMUM_CASH_IN_AMOUNT . "!";
                    echo json_encode($response);
                    return;
                }
                if ($amount > BKASH_MAXIMUM_CASH_IN_AMOUNT) {
                    $response["message"] = "Please give a maximum amount TK." . BKASH_MAXIMUM_CASH_IN_AMOUNT . "!";
                    echo json_encode($response);
                    return;
                }
            }
            if ($this->utils->cell_number_validation($cell_no) == FALSE) {
                $response["message"] = "Please Enter a Valid Cell Number! Supported format is now 01XXXXXXXXX. ";
                echo json_encode($response);
                return;
            }
            $api_key = API_KEY_BKASH_CASHIN;
            $description = "test";
            $transaction_data = array(
                'user_id' => $user_id,
                'transaction_id' => $transaction_id,
                'service_id' => SERVICE_TYPE_ID_BKASH_CASHIN,
                'amount' => $amount,
                'cell_no' => $cell_no,
                'description' => $description,
                'editable' => true
            );
            if ($service_status_type == SERVICE_TYPE_ID_ALLOW_TO_USE_LOCAL_SERVER) {
                $transaction_data['process_type_id'] = TRANSACTION_PROCESS_TYPE_ID_AUTO;
            } else if ($service_status_type == SERVICE_TYPE_ID_ALLOW_TO_USE_WEBSERVER) {
                $transaction_data['process_type_id'] = TRANSACTION_PROCESS_TYPE_ID_MANUAL;
                $transaction_data['editable'] = false;
            }
            $this->load->library("security");
            $transaction_data = $this->security->xss_clean($transaction_data);
            if ($transaction_id == '') {
                if ($this->transaction_library->add_transaction($api_key, $transaction_data) !== FALSE) {
                    $response['message'] = $this->transaction_library->messages_array();
                } else {
                    $response['message'] = $this->transaction_library->errors_array();
                }
            } else {
                if ($this->transaction_library->update_transaction_info($transaction_data) !== FALSE) {
                    $response['message'] = $this->transaction_model->messages_array();
                } else {
                    $response['message'] = $this->transaction_model->errors_array();
                }
            }
            echo json_encode($response);
            return;
        }

        if ($service_status_type == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION) {
            $this->data['app'] = TRANSCATION_APP;
            $this->data['error_message'] = "Sorry !! Bkash service is unavailable right now! please try again later!.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }
        $code_verification = false;
        $sms_or_email_verification = false;
        //if we have code/sms verification/email verification then we will display user to assign code
        if ($bkash_service_info['sms_verification'] == 1 || $bkash_service_info['email_verification'] == 1) {
            $sms_or_email_verification = true;
            //transaction verification code is generated here
            /* $this->load->library('Utils');
              $verification_code = $this->utils->get_transaction_verification_code();
              //Storing code into the database
              $updated_data = array(
              'id' => $bkash_service_info['user_service_id'],
              'verification_code' => $verification_code
              );
              $this->service_model->update_user_rates(array($updated_data));
              if($bkash_service_info['sms_verification'] == 1)
              {
              //send verification code via sms to the client
              }
              if($bkash_service_info['email_verification'] == 1)
              {
              //send verification code via email to the client
              $email = "";
              $profile_info = $this->reseller_model->get_user_info($user_id)->result_array();
              if (!empty($profile_info)) {
              $email = $profile_info[0]['email'];
              }
              $this->transaction_library->send_email($email, $verification_code);
              } */
            $code_verification = true;
        } else if ($bkash_service_info['code'] != "") {
            $code_verification = true;
        }
        $this->data['code_verification'] = $code_verification;
        $this->data['sms_or_email_verification'] = $sms_or_email_verification;
        //if sms verification is enabled then send sms with generate code
        //if email verification is enabled then send email with generate code
        //if transaction id is valid the retrieve transaction info
        $transaction_info = array();
        if ($transaction_id != '') {
            $transaction_info_array = $this->transaction_model->get_transaction_info($transaction_id)->result_array();
            if (!empty($transaction_info_array) && $transaction_info_array[0]['editable']) {
                $transaction_info['number'] = $transaction_info_array[0]['cell_no'];
                $transaction_info['amount'] = $transaction_info_array[0]['amount'];
                $transaction_info['transaction_id'] = $transaction_id;
                $transaction_info['code'] = '';
            } else {
                $this->data['app'] = TRANSCATION_APP;
                $this->data['error_message'] = "Time is expired. Sorry !! You are not allowed to edit the transaction anymore.";
                $this->template->load(null, 'common/error_message', $this->data);
                return;
            }
        } else {
            $transaction_info['number'] = '';
            $transaction_info['amount'] = '';
            $transaction_info['transaction_id'] = '';
            $transaction_info['code'] = '';
        }

        $this->data['transaction_info'] = json_encode($transaction_info);

        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_BKASH_CASHIN), array(), 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, 0, $where);
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            $transaction_list = $transaction_list_array['transaction_list'];
        }
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'transaction/bkash/index', $this->data);
    }

    /*
     * This method will process dbbl transaction
     * @author nazmul hasan on 2nd March 2016
     */

    public function dbbl() {
        $service_status_type = SERVICE_TYPE_ID_ALLOW_TO_USE_LOCAL_SERVER;
        $service_info_array = $this->service_model->get_service_info_list(array(SERVICE_TYPE_ID_DBBL_CASHIN))->result_array();
        if (!empty($service_info_array)) {
            $service_status_type = $service_info_array[0]['type_id'];
        }
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );

        if (file_get_contents("php://input") != null) {
            $response = array();
            if ($service_status_type == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION) {
                $response["message"] = "Sorry !! DBBL service is unavailable right now! please try again later!.";
                echo json_encode($response);
                return;
            }
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "dbblInfo")) {
                $dbblInfo = $requestInfo->dbblInfo;
                if (property_exists($dbblInfo, "number")) {
                    $cell_no = $dbblInfo->number;
                } else {
                    $response["message"] = "  Please give a Cell number! Cell Number is Required    !!";
                    echo json_encode($response);
                    return;
                }
                $validation_result = $this->transaction_library->check_transaction_interval_validation(array($cell_no), array(SERVICE_TYPE_ID_DBBL_CASHIN), $where);
                if ($validation_result['validation_flag'] != FALSE) {
                    $response["message"] = "Sorry !! DBBL service is unavailable right now! please try again later!.";
                    echo json_encode($response);
                    return;
                }
                if (property_exists($dbblInfo, "amount")) {
                    $amount = $dbblInfo->amount;
                } else {
                    $response["message"] = "Please give an Amount! Amount is Required  !!";
                    echo json_encode($response);
                    return;
                }
            }
            if (isset($amount)) {
                if ($amount < DBBL_MINIMUM_CASH_IN_AMOUNT) {
                    $response["message"] = "Please give a minimum amount TK. " . DBBL_MINIMUM_CASH_IN_AMOUNT . "!";
                    echo json_encode($response);
                    return;
                }
                if ($amount > DBBL_MAXIMUM_CASH_IN_AMOUNT) {
                    $response["message"] = "Please give a maximum amount TK." . DBBL_MAXIMUM_CASH_IN_AMOUNT . "!";
                    echo json_encode($response);
                    return;
                }
            }
            if ($this->utils->cell_number_validation($cell_no) == FALSE) {
                $response["message"] = "Please Enter a Valid Cell Number !! Supported format is now 01XXXXXXXXX";
                echo json_encode($response);
                return;
            }
            $user_id = $this->session->userdata('user_id');
            $api_key = API_KEY_DBBL_CASHIN;
            $description = "test";
            $transaction_id = "";
            $transaction_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'transaction_id' => $transaction_id,
                'service_id' => SERVICE_TYPE_ID_DBBL_CASHIN,
                'amount' => $amount,
                'cell_no' => $cell_no,
                'description' => $description
            );
            if ($service_status_type == SERVICE_TYPE_ID_ALLOW_TO_USE_LOCAL_SERVER) {
                $transaction_data['process_type_id'] = TRANSACTION_PROCESS_TYPE_ID_AUTO;
            } else if ($service_status_type == SERVICE_TYPE_ID_ALLOW_TO_USE_WEBSERVER) {
                $transaction_data['process_type_id'] = TRANSACTION_PROCESS_TYPE_ID_MANUAL;
            }
            $this->load->library("security");
            $transaction_data = $this->security->xss_clean($transaction_data);
            if ($this->transaction_library->add_transaction($api_key, $transaction_data) !== FALSE) {
                $response['message'] = "Transaction is created successfully.";
            } else {
                $response['message'] = $this->ion_auth->messages_array();
            }

            echo json_encode($response);
            return;
        }
        if ($service_status_type == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION) {
            $this->data['app'] = TRANSCATION_APP;
            $this->data['error_message'] = "Sorry !! DBBL service is unavailable right now! please try again later!.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }
        //checking whether user has permission for dbbl transaction
        $user_id = $this->session->userdata('user_id');
        $permission_exists = FALSE;
        $service_list = $this->service_model->get_user_assigned_services($user_id)->result_array();
        foreach ($service_list as $service_info) {
            //if in future there is new service under dbbl then update the logic here
            if ($service_info['service_id'] == SERVICE_TYPE_ID_DBBL_CASHIN) {
                $permission_exists = TRUE;
            }
        }
        if (!$permission_exists) {
            //you are not allowed to use dbbl transaction
            $this->data['app'] = TRANSCATION_APP;
            $this->data['error_message'] = "Sorry !! You are not allowed to use dbbl service.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }

        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_DBBL_CASHIN), array(), 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, 0, $where);
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            $transaction_list = $transaction_list_array['transaction_list'];
        }
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'transaction/dbbl/index', $this->data);
    }

    /*
     * This method will process mcash transaction
     * @author nazmul hasan on 2nd March 2016
     */

    public function mcash() {
        $service_status_type = SERVICE_TYPE_ID_ALLOW_TO_USE_LOCAL_SERVER;
        $service_info_array = $this->service_model->get_service_info_list(array(SERVICE_TYPE_ID_MCASH_CASHIN))->result_array();
        if (!empty($service_info_array)) {
            $service_status_type = $service_info_array[0]['type_id'];
        }

        $user_id = $this->session->userdata('user_id');
        $where = array(
            'user_id' => $user_id
        );
        if (file_get_contents("php://input") != null) {
            $response = array();
            if ($service_status_type == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION) {
                $response["message"] = "Sorry !! Mcash service is unavailable right now! please try again later!.";
                echo json_encode($response);
                return;
            }
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "mCashInfo")) {
                $mCashInfo = $requestInfo->mCashInfo;
                if (property_exists($mCashInfo, "number")) {
                    $cell_no = $mCashInfo->number;
                } else {
                    $response["message"] = "Please give a Cell number! Cell Number is Required    !!";
                    echo json_encode($response);
                    return;
                }
                $validation_result = $this->transaction_library->check_transaction_interval_validation(array($cell_no), array(SERVICE_TYPE_ID_MCASH_CASHIN), $where);
                if ($validation_result['validation_flag'] != FALSE) {
                    $response["message"] = "Sorry !! Mcash service is unavailable right now! please try again later!.";
                    echo json_encode($response);
                    return;
                }
                if (property_exists($mCashInfo, "amount")) {
                    $amount = $mCashInfo->amount;
                } else {
                    $response["message"] = "Please give an Amount! Amount is Required  !!";
                    echo json_encode($response);
                    return;
                }
            }
            if (isset($amount)) {
                if ($amount < MCASH_MINIMUM_CASH_IN_AMOUNT) {
                    $response["message"] = "Please give a minimum amount TK. " . MCASH_MINIMUM_CASH_IN_AMOUNT . "!";
                    echo json_encode($response);
                    return;
                }
                if ($amount > MCASH_MAXIMUM_CASH_IN_AMOUNT) {
                    $response["message"] = "Please give a maximum amount TK." . MCASH_MAXIMUM_CASH_IN_AMOUNT . "!";
                    echo json_encode($response);
                    return;
                }
            }
            if ($this->utils->cell_number_validation($cell_no) == FALSE) {
                $response["message"] = "Please Enter a Valid Cell Number !! Supported format is now 01XXXXXXXXX";
                echo json_encode($response);
                return;
            }
            $api_key = API_KEY_MKASH_CASHIN;
            $description = "test";
            $transaction_id = "";
            $transaction_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'transaction_id' => $transaction_id,
                'service_id' => SERVICE_TYPE_ID_MCASH_CASHIN,
                'amount' => $amount,
                'cell_no' => $cell_no,
                'description' => $description
            );
            if ($service_status_type == SERVICE_TYPE_ID_ALLOW_TO_USE_LOCAL_SERVER) {
                $transaction_data['process_type_id'] = TRANSACTION_PROCESS_TYPE_ID_AUTO;
            } else if ($service_status_type == SERVICE_TYPE_ID_ALLOW_TO_USE_WEBSERVER) {
                $transaction_data['process_type_id'] = TRANSACTION_PROCESS_TYPE_ID_MANUAL;
            }
            $this->load->library("security");
            $transaction_data = $this->security->xss_clean($transaction_data);
            if ($this->transaction_library->add_transaction($api_key, $transaction_data) !== FALSE) {
                $response['message'] = "Transaction is created successfully.";
            } else {
                $response['message'] = $this->ion_auth->messages_array();
            }

            echo json_encode($response);
            return;
        }
        if ($service_status_type == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION) {
            $this->data['app'] = TRANSCATION_APP;
            $this->data['error_message'] = "Sorry !! Mcash service is unavailable right now! please try again later!.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }
        //checking whether user has permission for mcash transaction
        $permission_exists = FALSE;
        $service_list = $this->service_model->get_user_assigned_services($user_id)->result_array();
        foreach ($service_list as $service_info) {
            //if in future there is new service under mcash then update the logic here
            if ($service_info['service_id'] == SERVICE_TYPE_ID_MCASH_CASHIN) {
                $permission_exists = TRUE;
            }
        }
        if (!$permission_exists) {
            //you are not allowed to use mcash transaction
            $this->data['app'] = TRANSCATION_APP;
            $this->data['error_message'] = "Sorry !! You are not allowed to use mcash service.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }

        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_MCASH_CASHIN), array(), 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, 0, $where);
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            $transaction_list = $transaction_list_array['transaction_list'];
        }
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'transaction/mcash/index', $this->data);
    }

    /*
     * This method will process ucash transaction
     * @author nazmul hasan on 2nd March 2016
     */

    public function ucash() {
        $service_status_type = SERVICE_TYPE_ID_ALLOW_TO_USE_LOCAL_SERVER;
        $service_info_array = $this->service_model->get_service_info_list(array(SERVICE_TYPE_ID_UCASH_CASHIN))->result_array();
        if (!empty($service_info_array)) {
            $service_status_type = $service_info_array[0]['type_id'];
        }

        $user_id = $this->session->userdata('user_id');
        $where = array(
            'user_id' => $user_id
        );
        if (file_get_contents("php://input") != null) {
            $response = array();
            if ($service_status_type == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION) {
                $response["message"] = "Sorry !! Ucash service is unavailable right now! please try again later!.";
                echo json_encode($response);
                return;
            }
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "uCashInfo")) {
                $uCashInfo = $requestInfo->uCashInfo;
                if (property_exists($uCashInfo, "number")) {
                    $cell_no = $uCashInfo->number;
                } else {
                    $response["message"] = "Please give a Cell number! Cell Number is Required    !!";
                    echo json_encode($response);
                    return;
                }
                $validation_result = $this->transaction_library->check_transaction_interval_validation(array($cell_no), array(SERVICE_TYPE_ID_UCASH_CASHIN), $where);
                if ($validation_result['validation_flag'] != FALSE) {
                    $response["message"] = "Sorry !! Ucash service is unavailable right now! please try again later!.";
                    echo json_encode($response);
                    return;
                }
                if (property_exists($uCashInfo, "amount")) {
                    $amount = $uCashInfo->amount;
                } else {
                    $response["message"] = "Please give an Amount! Amount is Required  !!";
                    echo json_encode($response);
                    return;
                }
            }
            if (isset($amount)) {
                if ($amount < UCASH_MINIMUM_CASH_IN_AMOUNT) {
                    $response["message"] = "Please give a minimum amount TK. " . UCASH_MINIMUM_CASH_IN_AMOUNT . "!";
                    echo json_encode($response);
                    return;
                }
                if ($amount > UCASH_MAXIMUM_CASH_IN_AMOUNT) {
                    $response["message"] = "Please give a maximum amount TK." . UCASH_MAXIMUM_CASH_IN_AMOUNT . "!";
                    echo json_encode($response);
                    return;
                }
            }
            if ($this->utils->cell_number_validation($cell_no) == FALSE) {
                $response["message"] = "Please Enter a Valid Cell Number !! Supported format is now 01XXXXXXXXX";
                echo json_encode($response);
                return;
            }
            $api_key = API_KEY_UKASH_CASHIN;
            $description = "test";
            $transaction_id = "";
            $transaction_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'transaction_id' => $transaction_id,
                'service_id' => SERVICE_TYPE_ID_UCASH_CASHIN,
                'amount' => $amount,
                'cell_no' => $cell_no,
                'description' => $description
            );
            if ($service_status_type == SERVICE_TYPE_ID_ALLOW_TO_USE_LOCAL_SERVER) {
                $transaction_data['process_type_id'] = TRANSACTION_PROCESS_TYPE_ID_AUTO;
            } else if ($service_status_type == SERVICE_TYPE_ID_ALLOW_TO_USE_WEBSERVER) {
                $transaction_data['process_type_id'] = TRANSACTION_PROCESS_TYPE_ID_MANUAL;
            }
            $this->load->library("security");
            $transaction_data = $this->security->xss_clean($transaction_data);
            if ($this->transaction_library->add_transaction($api_key, $transaction_data) !== FALSE) {
                $response['message'] = "Transaction is created successfully.";
                ;
            } else {
                $response['message'] = $this->ion_auth->messages_array();
            }

            echo json_encode($response);
            return;
        }
        if ($service_status_type == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION) {
            $this->data['app'] = TRANSCATION_APP;
            $this->data['error_message'] = "Sorry !! Ucash service is unavailable right now! please try again later!.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }
        //checking whether user has permission for ucash transaction
        $permission_exists = FALSE;
        $service_list = $this->service_model->get_user_assigned_services($user_id)->result_array();
        foreach ($service_list as $service_info) {
            //if in future there is new service under ucash then update the logic here
            if ($service_info['service_id'] == SERVICE_TYPE_ID_UCASH_CASHIN) {
                $permission_exists = TRUE;
            }
        }
        if (!$permission_exists) {
            //you are not allowed to use ucash transaction
            $this->data['app'] = TRANSCATION_APP;
            $this->data['error_message'] = "Sorry !! You are not allowed to use ucash service.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }

        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_UCASH_CASHIN), array(), 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, 0, $where);
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            $transaction_list = $transaction_list_array['transaction_list'];
        }
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'transaction/ucash/index', $this->data);
    }

    /*
     * This method will process topup transaction
     * @author nazmul hasan on 2nd March 2016
     */

    public function topup() {
        $service_info_list = array();
        $service_info_array = $this->service_model->get_service_info_list(array(SERVICE_TYPE_ID_TOPUP_GP, SERVICE_TYPE_ID_TOPUP_ROBI, SERVICE_TYPE_ID_TOPUP_BANGLALINK, SERVICE_TYPE_ID_TOPUP_AIRTEL, SERVICE_TYPE_ID_TOPUP_TELETALK))->result_array();
        foreach ($service_info_array as $service_info) {
            $service_info_list[$service_info['service_id']] = $service_info;
        }
        if ($service_info_list[SERVICE_TYPE_ID_TOPUP_GP]['type_id'] == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION && $service_info_list[SERVICE_TYPE_ID_TOPUP_ROBI]['type_id'] == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION && $service_info_list[SERVICE_TYPE_ID_TOPUP_BANGLALINK]['type_id'] == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION && $service_info_list[SERVICE_TYPE_ID_TOPUP_AIRTEL]['type_id'] == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION && $service_info_list[SERVICE_TYPE_ID_TOPUP_TELETALK]['type_id'] == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION) {
            $this->data['app'] = TRANSCATION_APP;
            $this->data['error_message'] = "Sorry !! Topup service is unavailable right now! please try again later!.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }
        $user_id = $this->session->userdata('user_id');
        $where = array(
            'user_id' => $user_id
        );
        if (file_get_contents("php://input") != null) {
            $response = array();
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            $cell_number_list = array();
            $service_id_list = array();
            if (property_exists($requestInfo, "transactionDataList")) {
                $user_assigned_service_id_list = array();
                $user_topup_operator_id_list = $this->service_model->get_user_assigned_services($user_id)->result_array();
                if (!empty($user_topup_operator_id_list)) {
                    // generate user assign service id list and declare specific transction data list
                    foreach ($user_topup_operator_id_list as $operator_id_info) {
                        $user_assigned_service_id_list[] = $operator_id_info['service_id'];
                    }
                }

                $transaction_data_list = $requestInfo->transactionDataList;
                $transction_list = array();
                $total_amount = 0;
                foreach ($transaction_data_list as $key => $transaction_data) {
                    $mapping_id = $this->utils->get_random_mapping_id();
                    $description = "test";
                    $transaction_id = "";
                    $topup_data_info = array(
                        'user_id' => $user_id,
                        'transaction_id' => $transaction_id,
                        'description' => $description,
                        'mapping_id' => $mapping_id
                    );
                    if (property_exists($transaction_data, "topupOperatorId")) {
                        $service_id = $transaction_data->topupOperatorId;
                        if (!in_array($service_id, $user_assigned_service_id_list)) {
                            $response["message"] = "The Operator Id  is not assigned to you at serial number " . ($key + 1);
                            echo json_encode($response);
                            return;
                        }
                        if ($service_info_list[$service_id]['type_id'] == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION) {
                            $response["message"] = $service_info_list[$service_id]['title'] . " Service Unavailable right now that you assigned  at serial number " . ($key + 1);
                            echo json_encode($response);
                            return;
                        }

                        $topup_data_info['service_id'] = $service_id;
                        $service_id_list[] = $service_id;
                        if ($service_info_list[$service_id]['type_id'] == SERVICE_TYPE_ID_ALLOW_TO_USE_LOCAL_SERVER) {
                            $topup_data_info['process_type_id'] = TRANSACTION_PROCESS_TYPE_ID_AUTO;
                        } else if ($service_info_list[$service_id]['type_id'] == SERVICE_TYPE_ID_ALLOW_TO_USE_WEBSERVER) {
                            $topup_data_info['process_type_id'] = TRANSACTION_PROCESS_TYPE_ID_MANUAL;
                        }
                    } else {
                        $response["message"] = "Operator Id  is Required at serial number " . ($key + 1);
                        echo json_encode($response);
                        return;
                    }
                    if (property_exists($transaction_data, "number")) {
                        $cell_no = $transaction_data->number;
                        if ($this->utils->cell_number_validation($cell_no) == FALSE) {
                            $response["message"] = "Please Enter a Valid Cell Number at serial number " . ($key + 1);
                            echo json_encode($response);
                            return;
                        }
                        $topup_data_info['cell_no'] = $cell_no;
                        $cell_number_list[] = $cell_no;
                    } else {
                        $response["message"] = "Please give a Cell number! Cell Number is Required   at serial number " . ($key + 1);
                        echo json_encode($response);
                        return;
                    }

                    if (property_exists($transaction_data, "topupType")) {
                        $topup_type_id = $transaction_data->topupType;
                        if ($topup_type_id != OPERATOR_TYPE_ID_PREPAID && $topup_type_id != OPERATOR_TYPE_ID_POSTPAID) {
                            $response["message"] = "Please give valid Operator Type Id at serial number " . ($key + 1);
                            echo json_encode($response);
                            return;
                        }
                        $topup_data_info['operator_type_id'] = $topup_type_id;
                    } else {
                        $response["message"] = "Operator Type Id  is Required at serial number " . ($key + 1);
                        echo json_encode($response);
                        return;
                    }
                    if (property_exists($transaction_data, "amount")) {
                        $amount = $transaction_data->amount;
                        $total_amount = $total_amount + $amount;
                        if (isset($amount)) {
                            if ($transaction_data->topupType == OPERATOR_TYPE_ID_POSTPAID && $transaction_data->topupOperatorId == SERVICE_TYPE_ID_TOPUP_GP) {
                                if ($amount < TOPUP_POSTPAID_GP_MINIMUM_CASH_IN_AMOUNT) {
                                    $response["message"] = "Please give GP postpaid minimum amount TK. " . TOPUP_POSTPAID_GP_MINIMUM_CASH_IN_AMOUNT . "! at serial number" . ($key + 1);
                                    echo json_encode($response);
                                    return;
                                }
                            } else if ($amount < TOPUP_MINIMUM_CASH_IN_AMOUNT) {
                                $response["message"] = "Please give a minimum amount TK. " . TOPUP_MINIMUM_CASH_IN_AMOUNT . "! at serial number" . ($key + 1);
                                echo json_encode($response);
                                return;
                            }
                            if ($amount > TOPUP_MAXIMUM_CASH_IN_AMOUNT) {
                                $response["message"] = "Please give a maximum amount TK." . TOPUP_MAXIMUM_CASH_IN_AMOUNT . "! at serial number" . ($key + 1);
                                echo json_encode($response);
                                return;
                            }
                        }
                        $topup_data_info['amount'] = $amount;
                    } else {
                        $response["message"] = "Please give an Amount! Amount is Required   at serial number " . ($key + 1);
                        echo json_encode($response);
                        return;
                    }
                    $transction_list[] = $topup_data_info;
                    $validation_result = $this->transaction_library->check_transaction_interval_validation($cell_number_list, $service_id_list, $where);
                    if ($validation_result['validation_flag'] != FALSE) {
                        $transction_info = $validation_result['transction_info'];
                        $response["message"] = "Sorry !!" . $service_info_list[$transction_info['service_id']]['title'] . " service is unavailable right now for this number " . $transction_info['cell_no'];
                        echo json_encode($response);
                        return;
                    }
                }

                $this->load->library("security");
                $transction_list = $this->security->xss_clean($transction_list);
                if ($this->transaction_library->add_multipule_transactions($transction_list, $user_assigned_service_id_list, $total_amount, $user_id) !== FALSE) {
                    $response['message'] = $this->transaction_library->messages_array();
                } else {
                    $response['message'] = $this->transaction_library->errors_array();
                }
                echo json_encode($response);
                return;
            } else {
                $response['message'] = "Sorry!! Please give a transaction Info";
                echo json_encode($response);
                return;
            }
        } else if ($this->input->post('submit_btn')) {
            $error_messages = array();
            $cell_number_list = array();
            $config['upload_path'] = TOPUP_FILE_UPLOAD_DIRECTORY;
            $config['allowed_types'] = 'xlsx';
            $random_string = $this->utils->get_random_string();
            $file_name = $user_id . "_" . $random_string . ".xlsx";
            $config['file_name'] = $file_name;
            $config['overwrite'] = FALSE;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload()) {
                $this->data['error_message'] = $this->upload->display_errors();
            } else {
                $this->load->library('excel');
                $file = TOPUP_FILE_UPLOAD_DIRECTORY . $file_name;

                //read file from path
                $objPHPExcel = PHPExcel_IOFactory::load($file);

                //get only the Cell Collection
                $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

                //extract to a PHP readable array format
                $header = array();
                $arr_data = array();
                foreach ($cell_collection as $cell) {

                    $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();

                    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();

                    $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();

                    //header will/should be in row 1 only. of course this can be modified to suit your need.
                    if ($row == 1) {
                        $header[$row][$column] = $data_value;
                    } else {
                        $arr_data[$row][$column] = $data_value;
                    }
                }
                $header_len = sizeof($header[1]);
                $row_counter = 1;
                $transction_list = array();
                foreach ($arr_data as $result_data) {
                    if (sizeof($result_data) != $header_len) {
                        $error_messages[] = 'Row no ' . $row_counter . ' is not a valid row';
                        break;
                    }
                    if (strip_tags($result_data['A']) == '' || strip_tags($result_data['B']) == '' || strip_tags($result_data['C']) == '') {
                        $row_counter++;
                        continue;
                    }
                    if ((array_key_exists('A', $result_data) && $this->utils->cell_number_validation($result_data['A']) == FALSE)) {
                        $error_messages[] = 'Please Enter a Valid Cell Number at row number ' . $row_counter;
                        break;
                    }
                    if (in_array($result_data['A'], $cell_number_list)) {
                        $error_messages[] = 'Duplicate Number' . $result_data['A'] . 'at row number' . $row_counter;
                        break;
                    }
                    $cell_number_list[] = ($result_data['A']);
                    if ((array_key_exists('B', $result_data)) && $result_data['B'] < TOPUP_MINIMUM_CASH_IN_AMOUNT || $result_data['B'] > TOPUP_MAXIMUM_CASH_IN_AMOUNT) {

                        $error_messages[] = 'Please Give a Valid Amount at row number ' . $row_counter;
                        break;
                    }
                    $row_counter++;
                    $transction_data = array(
                        'number' => strip_tags($result_data['A']),
                        'amount' => strip_tags($result_data['B']),
                        'topupType' => strip_tags($result_data['C']),
                        'topupOperatorId' => $this->utils->get_operator_type_id(strip_tags($result_data['A']))
                    );
                    $transction_list[] = $transction_data;
                }
                if (empty($error_messages)) {
                    $this->data['transactions_data'] = json_encode($transction_list);
                } else {
                    $this->data['transactions_data'] = json_encode(array());
                    $this->data['error_message'] = $error_messages[0];
                }
            }
        }
        //checking whether user has permission for topup transaction
        $permission_exists = FALSE;
        $service_list = $this->service_model->get_user_assigned_services($user_id)->result_array();
        foreach ($service_list as $service_info) {
            //if in future there is new service under topup then update the logic here
            $service_id = $service_info['service_id'];
            if ($service_id == SERVICE_TYPE_ID_TOPUP_GP || $service_id == SERVICE_TYPE_ID_TOPUP_ROBI || $service_id == SERVICE_TYPE_ID_TOPUP_BANGLALINK || $service_id == SERVICE_TYPE_ID_TOPUP_AIRTEL || $service_id == SERVICE_TYPE_ID_TOPUP_TELETALK) {
                $permission_exists = TRUE;
            }
        }
        if (!$permission_exists) {
            //you are not allowed to use topup transaction
            $this->data['app'] = TRANSCATION_APP;
            $this->data['error_message'] = "Sorry !! You are not allowed to use topup service.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }

        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_TOPUP_GP, SERVICE_TYPE_ID_TOPUP_ROBI, SERVICE_TYPE_ID_TOPUP_BANGLALINK, SERVICE_TYPE_ID_TOPUP_AIRTEL, SERVICE_TYPE_ID_TOPUP_TELETALK), array(), 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, 0, $where);
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            $transaction_list = $transaction_list_array['transaction_list'];
        }
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->load->model('service_model');
        $topup_type_list = $this->service_model->get_all_operator_types()->result_array();
        $topup_operator_list = $this->service_model->get_user_topup_services($user_id)->result_array();
        $this->data['app'] = TRANSCATION_APP;
        $this->data['topup_type_list'] = json_encode($topup_type_list);
        $this->data['topup_operator_list'] = json_encode($topup_operator_list);
        $this->template->load(null, 'transaction/topup/index', $this->data);
    }

    /*
     * This method will send bulk sms
     * @author nazmul hasan on 17th april 2016
     */

    public function sms() {
        $user_id = $this->session->userdata('user_id');
        $transction_list = array();
        if (file_get_contents("php://input") != null) {
            $response = array();
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "transactionDataList")) {
                $transaction_data_list = $requestInfo->transactionDataList;
                $transction_list = array();
                foreach ($transaction_data_list as $key => $transaction_data) {
                    $sms_data_info = array();
                    if (property_exists($transaction_data, "number")) {
                        $cell_no = $transaction_data->number;
                        if ($this->utils->cell_number_validation($cell_no) == FALSE) {
                            $response["message"] = "Please Enter a Valid Cell Number at row number " . $key + 1;
                            echo json_encode($response);
                            return;
                        }
                        $sms_data_info['cell_no'] = $cell_no;
                    } else {
                        $response["message"] = " Please give a Cell number! Cell Number is Required   at row number " . $key + 1;
                        echo json_encode($response);
                        return;
                    }
                    $sms_data_info['user_id'] = $user_id;
                    $sms_data_info['service_id'] = SERVICE_TYPE_ID_SEND_SMS;
                    $transction_list[] = $sms_data_info;
                    $sms = "";
                }
                if (property_exists($requestInfo, "smsInfo")) {
                    $smsInfo = $requestInfo->smsInfo;
                    if (property_exists($smsInfo, "sms")) {
                        $sms = $smsInfo->sms;
                    } else {
                        $response["message"] = "Please write sms text.";
                        echo json_encode($response);
                        return;
                    }
                }
                $this->load->library("security");
                $transction_list = $this->security->xss_clean($transction_list);
                if ($this->transaction_library->add_sms_transactions($transction_list, $sms, $user_id) !== FALSE) {
                    $response['message'] = $this->transaction_library->messages_array();
                } else {
                    $response['message'] = $this->transaction_library->errors_array();
                }
                //$response['message'] = "Processing";
                echo json_encode($response);
                return;
            } else {
                $response['message'] = "Sorry!! Please provide cell number to send sms";
                echo json_encode($response);
                return;
            }
        } else if ($this->input->post('submit_btn')) {
            $config = array();
            $config['upload_path'] = SMS_FILE_UPLOAD_DIRECTORY;
            $config['allowed_types'] = 'xlsx';
            $random_string = $this->utils->get_random_string();
            $file_name = $user_id . "_" . $random_string . ".xlsx";
            $config['file_name'] = $file_name;
            $config['overwrite'] = FALSE;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload()) {
                $this->data['error_message'] = $this->upload->display_errors();
            } else {
                $this->load->library('excel');
                $file = SMS_FILE_UPLOAD_DIRECTORY . $file_name;
                //read file from path
                $objPHPExcel = PHPExcel_IOFactory::load($file);
                //get only the Cell Collection
                $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                //extract to a PHP readable array format
                $header = array();
                $arr_data = array();
                //task_tanvir validate each row before extracting information
                foreach ($cell_collection as $cell) {
                    $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                    $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
                    //header will/should be in row 1 only. of course this can be modified to suit your need.
                    if ($row == 1) {
                        $header[$row][$column] = $data_value;
                    } else {
                        $arr_data[$row][$column] = $data_value;
                    }
                }
                $header_len = sizeof($header[1]);
                $row_counter = 1;
                $error_messages = array();
                foreach ($arr_data as $result_data) {
                    if (sizeof($result_data) != $header_len) {
                        $error_messages[] = 'Row no ' . $row_counter . ' is not a valid row';
                        break;
                    }
                    //ignoring empty row
                    if (array_key_exists('A', $result_data) && strip_tags($result_data['A']) == '') {
                        $row_counter++;
                        continue;
                    }
                    if ((array_key_exists('A', $result_data) && strip_tags($result_data['A']) != '' && $this->utils->cell_number_validation($result_data['A']) == FALSE)) {
                        $error_messages[] = 'Please Enter a Valid Cell Number at row number ' . $row_counter;
                        break;
                    }
                    $row_counter++;
                    $transction_data = array(
                        'number' => strip_tags($result_data['A'])
                    );
                    $transction_list[] = $transction_data;
                }
                if (empty($error_messages)) {
                    $this->data['transactions_data'] = json_encode($transction_list);
                } else {
                    $this->data['transactions_data'] = json_encode($transction_list);
                    $this->data['error_message'] = $error_messages[0];
                }
            }
        }
        //checking whether user has permission for bkash transaction
        $permission_exists = FALSE;
        $service_list = $this->service_model->get_user_assigned_services($user_id)->result_array();
        foreach ($service_list as $service_info) {
            //if in future there is new service under bkash then update the logic here
            if ($service_info['service_id'] == SERVICE_TYPE_ID_SEND_SMS) {
                $permission_exists = TRUE;
            }
        }
        if (!$permission_exists) {
            //you are not allowed to use bkash transaction
            $this->data['app'] = TRANSCATION_APP;
            $this->data['error_message'] = "Sorry !! You are not allowed to use sms service.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }
        $this->data['app'] = TRANSCATION_APP;
        $this->data['transaction_list'] = json_encode($transction_list);
        $this->template->load(null, 'transaction/sms/index', $this->data);
    }

    function get_transaction_list() {
        $response = array('transaction_list' => array());
        if (file_get_contents("php://input") != null) {
            $where = array(
                'user_id' => $this->session->userdata('user_id')
            );
            $service_id_list = array();
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "serviceIdList")) {
                $service_id_list = $requestInfo->serviceIdList;
            }
            $transaction_list_array = $this->transaction_library->get_user_transaction_list($service_id_list, array(), 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, 0, $where);
            $transaction_list = array();
            if (!empty($transaction_list_array)) {
                $response['transaction_list'] = $transaction_list_array['transaction_list'];
            }
        }
        echo json_encode($response);
    }

}
