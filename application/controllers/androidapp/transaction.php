<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function bkash() {
        $cell_no = $this->input->post('number');
        $amount = $this->input->post('amount');
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $response = array();
        $service_status_type = SERVICE_TYPE_ID_ALLOW_TO_USE_LOCAL_SERVER;
        $this->load->model('service_model');
        $service_info_array = $this->service_model->get_service_info_list(array(SERVICE_TYPE_ID_BKASH_CASHIN))->result_array();
        if (!empty($service_info_array)) {
            $service_status_type = $service_info_array[0]['type_id'];
        }
        if ($service_status_type == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION) {
            $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
            $response["message"] = "Sorry !! Bkash service is unavailable right now! please try again later!.";
            echo json_encode($response);
            return;
        }
        $this->load->model('androidapp/app_reseller_model');
        $app_session_id_array = $this->app_reseller_model->get_app_session_id($user_id)->result_array();
        if (!empty($app_session_id_array) && $session_id != $app_session_id_array[0]['app_session_id']) {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
            $response['message'] = "Sorry !! session id doesn't match !";
            echo json_encode($response);
            return;
        }
        $api_key = API_KEY_BKASH_CASHIN;
        $transaction_id = "";
        $description = "test";
        $transaction_data = array(
            'user_id' => $user_id,
            'transaction_id' => $transaction_id,
            'service_id' => SERVICE_TYPE_ID_BKASH_CASHIN,
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
        $this->load->library('transaction_library');
        if ($this->transaction_library->add_transaction($api_key, $transaction_data) !== FALSE) {
            $this->load->library('reseller_library');
            $current_balance = $this->reseller_library->get_user_current_balance($user_id);
            $response['current_balance'] = $current_balance;

            $response['message'] = $this->transaction_library->messages_array();
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
        } else {
            $response['message'] = $this->transaction_library->errors_array();
            $response['response_code'] = ERROR_CODE_SERVER_EXCEPTION;
        }
        echo json_encode($response);
    }

    public function dbbl() {
        $cell_no = $this->input->post('number');
        $amount = $this->input->post('amount');
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $response = array();
        $service_status_type = SERVICE_TYPE_ID_ALLOW_TO_USE_LOCAL_SERVER;
        $this->load->model('service_model');
        $service_info_array = $this->service_model->get_service_info_list(array(SERVICE_TYPE_ID_DBBL_CASHIN))->result_array();
        if (!empty($service_info_array)) {
            $service_status_type = $service_info_array[0]['type_id'];
        }
        if ($service_status_type == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION) {
            $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
            $response["message"] = "Sorry !! DBBL service is unavailable right now! please try again later!.";
            echo json_encode($response);
            return;
        }
        $this->load->model('androidapp/app_reseller_model');
        $app_session_id_array = $this->app_reseller_model->get_app_session_id($user_id)->result_array();
        if (!empty($app_session_id_array) && $session_id != $app_session_id_array[0]['app_session_id']) {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
            $response['message'] = "Sorry !! session id doesn't match !";
            echo json_encode($response);
            return;
        }
        $api_key = API_KEY_DBBL_CASHIN;
        $transaction_id = "";
        $description = "test";
        $transaction_data = array(
            'user_id' => $user_id,
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
        $this->load->library('transaction_library');
        if ($this->transaction_library->add_transaction($api_key, $transaction_data) !== FALSE) {
            $this->load->library('reseller_library');
            $current_balance = $this->reseller_library->get_user_current_balance($user_id);
            $response['current_balance'] = $current_balance;

            $response['message'] = $this->transaction_library->messages_array();
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
        } else {
            $response['message'] = $this->transaction_library->errors_array();
            $response['response_code'] = ERROR_CODE_SERVER_EXCEPTION;
        }
        echo json_encode($response);
    }

    public function mcash() {
        $cell_no = $this->input->post('number');
        $amount = $this->input->post('amount');
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $response = array();
        $service_status_type = SERVICE_TYPE_ID_ALLOW_TO_USE_LOCAL_SERVER;
        $this->load->model('service_model');
        $service_info_array = $this->service_model->get_service_info_list(array(SERVICE_TYPE_ID_MCASH_CASHIN))->result_array();
        if (!empty($service_info_array)) {
            $service_status_type = $service_info_array[0]['type_id'];
        }
        if ($service_status_type == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION) {
            $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
            $response["message"] = "Sorry !! Mcash service is unavailable right now! please try again later!.";
            echo json_encode($response);
            return;
        }
        $this->load->model('androidapp/app_reseller_model');
        $app_session_id_array = $this->app_reseller_model->get_app_session_id($user_id)->result_array();
        if (!empty($app_session_id_array) && $session_id != $app_session_id_array[0]['app_session_id']) {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
            $response['message'] = "Sorry !! session id doesn't match !";
            echo json_encode($response);
            return;
        }
        $api_key = API_KEY_MKASH_CASHIN;
        $transaction_id = "";
        $description = "test";
        $transaction_data = array(
            'user_id' => $user_id,
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
        $this->load->library('transaction_library');
        if ($this->transaction_library->add_transaction($api_key, $transaction_data) !== FALSE) {
            $this->load->library('reseller_library');
            $current_balance = $this->reseller_library->get_user_current_balance($user_id);
            $response['current_balance'] = $current_balance;

            $response['message'] = $this->transaction_library->messages_array();
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
        } else {
            $response['message'] = $this->transaction_library->errors_array();
            $response['response_code'] = ERROR_CODE_SERVER_EXCEPTION;
        }
        echo json_encode($response);
    }

    public function ucash() {
        $cell_no = $this->input->post('number');
        $amount = $this->input->post('amount');
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $response = array();
        $service_status_type = SERVICE_TYPE_ID_ALLOW_TO_USE_LOCAL_SERVER;
        $this->load->model('service_model');
        $service_info_array = $this->service_model->get_service_info_list(array(SERVICE_TYPE_ID_UCASH_CASHIN))->result_array();
        if (!empty($service_info_array)) {
            $service_status_type = $service_info_array[0]['type_id'];
        }
        if ($service_status_type == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION) {
            $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
            $response["message"] = "Sorry !! Ucash service is unavailable right now! please try again later!.";
            echo json_encode($response);
            return;
        }
        $this->load->model('androidapp/app_reseller_model');
        $app_session_id_array = $this->app_reseller_model->get_app_session_id($user_id)->result_array();
        if (!empty($app_session_id_array) && $session_id != $app_session_id_array[0]['app_session_id']) {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
            $response['message'] = "Sorry !! session id doesn't match !";
            echo json_encode($response);
            return;
        }
        $api_key = API_KEY_UKASH_CASHIN;
        $transaction_id = "";
        $description = "test";
        $transaction_data = array(
            'user_id' => $user_id,
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
        $this->load->library('transaction_library');
        if ($this->transaction_library->add_transaction($api_key, $transaction_data) !== FALSE) {
            $this->load->library('reseller_library');
            $current_balance = $this->reseller_library->get_user_current_balance($user_id);
            $response['current_balance'] = $current_balance;

            $response['message'] = $this->transaction_library->messages_array();
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
        } else {
            $response['message'] = $this->transaction_library->errors_array();
            $response['response_code'] = ERROR_CODE_SERVER_EXCEPTION;
        }
        echo json_encode($response);
    }

    public function topup() {
        $cell_no = $this->input->post('number');
        $amount = $this->input->post('amount');
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        //$service_id = $this->input->post('topup_type_id');
        $topup_type_id = $this->input->post('operator_type_id');
        $response = array();
        $service_status_type = SERVICE_TYPE_ID_ALLOW_TO_USE_LOCAL_SERVER;
        $this->load->model('service_model');
        $service_info_array = $this->service_model->get_service_info_list(array(SERVICE_TYPE_ID_TOPUP_GP, SERVICE_TYPE_ID_TOPUP_ROBI, SERVICE_TYPE_ID_TOPUP_BANGLALINK, SERVICE_TYPE_ID_TOPUP_AIRTEL, SERVICE_TYPE_ID_TOPUP_TELETALK))->result_array();
        foreach ($service_info_array as $service_info) {
            $service_info_list[$service_info['service_id']] = $service_info;
        }
        if ($service_info_list[SERVICE_TYPE_ID_TOPUP_GP]['type_id'] == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION && $service_info_list[SERVICE_TYPE_ID_TOPUP_ROBI]['type_id'] == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION && $service_info_list[SERVICE_TYPE_ID_TOPUP_BANGLALINK]['type_id'] == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION && $service_info_list[SERVICE_TYPE_ID_TOPUP_AIRTEL]['type_id'] == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION && $service_info_list[SERVICE_TYPE_ID_TOPUP_TELETALK]['type_id'] == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION) {
            $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
            $response["message"] = "Sorry !! TopUp service is unavailable right now! please try again later!.";
            echo json_encode($response);
            return;
        }
        $this->load->model('androidapp/app_reseller_model');
        $app_session_id_array = $this->app_reseller_model->get_app_session_id($user_id)->result_array();
        if (!empty($app_session_id_array) && $session_id != $app_session_id_array[0]['app_session_id']) {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
            $response['message'] = "Sorry !! session id doesn't match !";
            echo json_encode($response);
            return;
        }
        $api_key = "";
        $service_id = 0;
        if (strpos($cell_no, "+88017") === 0 || strpos($cell_no, "88017") === 0 || strpos($cell_no, "017") === 0) {
            $service_id = 101;
        } else if (strpos($cell_no, "+88018") === 0 || strpos($cell_no, "88018") === 0 || strpos($cell_no, "018") === 0) {
            $service_id = 102;
        }
        if (strpos($cell_no, "+88019") === 0 || strpos($cell_no, "88019") === 0 || strpos($cell_no, "019") === 0) {
            $service_id = 103;
        }
        if (strpos($cell_no, "+88016") === 0 || strpos($cell_no, "88016") === 0 || strpos($cell_no, "016") === 0) {
            $service_id = 104;
        }
        if (strpos($cell_no, "+88015") === 0 || strpos($cell_no, "88015") === 0 || strpos($cell_no, "015") === 0) {
            $service_id = 105;
        }
        if ($service_info_list[$service_id]['type_id'] == SERVICE_TYPE_ID_NOT_ALLOW_TRNASCATION) {
            $response['response_code'] = ERROR_CODE_SERVICE_UNAVAILABLE;
            $response["message"] = $service_info_list[$service_id]['title'] . " Service Unavailable right now that you assigned  at serial number ";
            echo json_encode($response);
            return;
        }

        if ($service_id == SERVICE_TYPE_ID_TOPUP_GP) {
            $api_key = API_KEY_CASHIN_GP;
        } else if ($service_id == SERVICE_TYPE_ID_TOPUP_ROBI) {
            $api_key = API_KEY_CASHIN_ROBI;
        } else if ($service_id == SERVICE_TYPE_ID_TOPUP_BANGLALINK) {
            $api_key = API_KEY_CASHIN_BANGLALINK;
        } else if ($service_id == SERVICE_TYPE_ID_TOPUP_AIRTEL) {
            $api_key = API_KEY_CASHIN_AIRTEL;
        } else if ($service_id == SERVICE_TYPE_ID_TOPUP_TELETALK) {
            $api_key = API_KEY_CASHIN_TELETALK;
        }

        $transaction_id = "";
        $description = "test";
        $transaction_data = array(
            'user_id' => $user_id,
            'transaction_id' => $transaction_id,
            'service_id' => $service_id,
            'amount' => $amount,
            'cell_no' => $cell_no,
            'description' => $description
        );
        if ($service_info_list[$service_id]['type_id'] == SERVICE_TYPE_ID_ALLOW_TO_USE_LOCAL_SERVER) {
            $transaction_data['process_type_id'] = TRANSACTION_PROCESS_TYPE_ID_AUTO;
        } else if ($service_info_list[$service_id]['type_id'] == SERVICE_TYPE_ID_ALLOW_TO_USE_WEBSERVER) {
            $transaction_data['process_type_id'] = TRANSACTION_PROCESS_TYPE_ID_MANUAL;
        }
        $this->load->library("security");
        $transaction_data = $this->security->xss_clean($transaction_data);
        $this->load->library('transaction_library');
        if ($this->transaction_library->add_transaction($api_key, $transaction_data) !== FALSE) {
            $this->load->library('reseller_library');
            $current_balance = $this->reseller_library->get_user_current_balance($user_id);
            $response['current_balance'] = $current_balance;

            $response['message'] = $this->transaction_library->messages_array();
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
        } else {
            $response['message'] = $this->transaction_library->errors_array();
            $response['response_code'] = ERROR_CODE_SERVER_EXCEPTION;
        }
        echo json_encode($response);
    }

    public function get_bkash_transaction_list() {
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $response = array(
            'response_code' => RESPONSE_CODE_SUCCESS,
            'transaction_list' => array()
        );
        $this->load->model('androidapp/app_reseller_model');
        $app_session_id_array = $this->app_reseller_model->get_app_session_id($user_id)->result_array();
        if (!empty($app_session_id_array) && $session_id != $app_session_id_array[0]['app_session_id']) {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
            $response['message'] = "Sorry !! session id doesn't match !";
            echo json_encode($response);
            return;
        }
        $where = array(
            'user_id' => $user_id
        );
        $this->load->library('transaction_library');
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_BKASH_CASHIN), array(), 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, 0, $where);
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            foreach ($transaction_list_array['transaction_list'] as $temp_transaction_info) {
                $transaction_info = array(
                    'cell_no' => $temp_transaction_info['cell_no'],
                    'amount' => $temp_transaction_info['amount'],
                    'title' => $temp_transaction_info['service_title'],
                    'status' => $temp_transaction_info['status']
                );
                $transaction_list[] = $transaction_info;
            }
            $response['transaction_list'] = $transaction_list;
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
            $response['message'] = "Transaction list.";
        }
        echo json_encode($response);
    }

    public function get_dbbl_transaction_list() {
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $response = array(
            'response_code' => RESPONSE_CODE_SUCCESS,
            'transaction_list' => array()
        );
        $this->load->model('androidapp/app_reseller_model');
        $app_session_id_array = $this->app_reseller_model->get_app_session_id($user_id)->result_array();
        if (!empty($app_session_id_array) && $session_id != $app_session_id_array[0]['app_session_id']) {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
            $response['message'] = "Sorry !! session id doesn't match !";
            echo json_encode($response);
            return;
        }
        $where = array(
            'user_id' => $user_id
        );
        $this->load->library('transaction_library');
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_DBBL_CASHIN), array(), 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, 0, $where);
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            foreach ($transaction_list_array['transaction_list'] as $temp_transaction_info) {
                $transaction_info = array(
                    'cell_no' => $temp_transaction_info['cell_no'],
                    'amount' => $temp_transaction_info['amount'],
                    'title' => $temp_transaction_info['service_title'],
                    'status' => $temp_transaction_info['status']
                );
                $transaction_list[] = $transaction_info;
            }
            $response['transaction_list'] = $transaction_list;
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
            $response['message'] = "Transaction list.";
        }
        echo json_encode($response);
    }

    public function get_mcash_transaction_list() {
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $response = array(
            'response_code' => RESPONSE_CODE_SUCCESS,
            'transaction_list' => array()
        );
        $this->load->model('androidapp/app_reseller_model');
        $app_session_id_array = $this->app_reseller_model->get_app_session_id($user_id)->result_array();
        if (!empty($app_session_id_array) && $session_id != $app_session_id_array[0]['app_session_id']) {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
            $response['message'] = "Sorry !! session id doesn't match !";
            echo json_encode($response);
            return;
        }
        $where = array(
            'user_id' => $user_id
        );
        $this->load->library('transaction_library');
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_MCASH_CASHIN), array(), 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, 0, $where);
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            foreach ($transaction_list_array['transaction_list'] as $temp_transaction_info) {
                $transaction_info = array(
                    'cell_no' => $temp_transaction_info['cell_no'],
                    'amount' => $temp_transaction_info['amount'],
                    'title' => $temp_transaction_info['service_title'],
                    'status' => $temp_transaction_info['status']
                );
                $transaction_list[] = $transaction_info;
            }
            $response['transaction_list'] = $transaction_list;
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
            $response['message'] = "Transaction list.";
        }
        echo json_encode($response);
    }

    public function get_ucash_transaction_list() {
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $response = array(
            'response_code' => RESPONSE_CODE_SUCCESS,
            'transaction_list' => array()
        );
        $this->load->model('androidapp/app_reseller_model');
        $app_session_id_array = $this->app_reseller_model->get_app_session_id($user_id)->result_array();
        if (!empty($app_session_id_array) && $session_id != $app_session_id_array[0]['app_session_id']) {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
            $response['message'] = "Sorry !! session id doesn't match !";
            echo json_encode($response);
            return;
        }
        $where = array(
            'user_id' => $user_id
        );
        $this->load->library('transaction_library');
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_UCASH_CASHIN), array(), 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, 0, $where);
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            foreach ($transaction_list_array['transaction_list'] as $temp_transaction_info) {
                $transaction_info = array(
                    'cell_no' => $temp_transaction_info['cell_no'],
                    'amount' => $temp_transaction_info['amount'],
                    'title' => $temp_transaction_info['service_title'],
                    'status' => $temp_transaction_info['status']
                );
                $transaction_list[] = $transaction_info;
            }
            $response['transaction_list'] = $transaction_list;
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
            $response['message'] = "Transaction list.";
        }
        echo json_encode($response);
    }

    public function get_topup_transaction_list() {
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $response = array(
            'response_code' => RESPONSE_CODE_SUCCESS,
            'transaction_list' => array()
        );
        $this->load->model('androidapp/app_reseller_model');
        $app_session_id_array = $this->app_reseller_model->get_app_session_id($user_id)->result_array();
        if (!empty($app_session_id_array) && $session_id != $app_session_id_array[0]['app_session_id']) {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
            $response['message'] = "Sorry !! session id doesn't match !";
            echo json_encode($response);
            return;
        }
        $where = array(
            'user_id' => $user_id
        );
        $this->load->library('transaction_library');
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_TOPUP_GP, SERVICE_TYPE_ID_TOPUP_ROBI, SERVICE_TYPE_ID_TOPUP_BANGLALINK, SERVICE_TYPE_ID_TOPUP_AIRTEL, SERVICE_TYPE_ID_TOPUP_TELETALK), array(), 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, 0, $where);
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            foreach ($transaction_list_array['transaction_list'] as $temp_transaction_info) {
                $transaction_info = array(
                    'cell_no' => $temp_transaction_info['cell_no'],
                    'amount' => $temp_transaction_info['amount'],
                    'title' => $temp_transaction_info['service_title'],
                    'status' => $temp_transaction_info['status']
                );
                $transaction_list[] = $transaction_info;
            }
            $response['transaction_list'] = $transaction_list;
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
            $response['message'] = "Transaction list.";
        }
        echo json_encode($response);
    }

    public function get_payment_transaction_list() {
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $response = array(
            'response_code' => RESPONSE_CODE_SUCCESS
        );
        $this->load->model('androidapp/app_reseller_model');
        $app_session_id_array = $this->app_reseller_model->get_app_session_id($user_id)->result_array();
        if (!empty($app_session_id_array) && $session_id != $app_session_id_array[0]['app_session_id']) {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
            $response['message'] = "Sorry !! session id doesn't match !";
            echo json_encode($response);
            return;
        }
        $where = array(
            'user_id' => $user_id
        );
        $payment_list = array();
        $this->load->library('payment_library');
        $payment_info_list = $this->payment_library->get_payment_history(array(PAYMENT_TYPE_ID_SEND_CREDIT, PAYMENT_TYPE_ID_RETURN_CREDIT), array(), 0, 0, PAYMENT_LIST_DEAFULT_LIMIT, PAYMENT_LIST_DEAFULT_OFFSET, 'desc', $where);
        if (!empty($payment_info_list)) {
            foreach ($payment_info_list['payment_list'] as $temp_payment_info) {
                $payment_info = array(
                    'username' => $temp_payment_info['username'],
                    'amount' => $temp_payment_info['balance_out'],
                    'date' => $temp_payment_info['created_on']
                );
                $payment_list[] = $payment_info;
            }
            $response['payment_list'] = $payment_list;
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
            $response['message'] = "Transaction list.";
        }
        echo json_encode($response);
    }

}
