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
        $result = array();
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
        $this->load->library('transaction_library');
        if ($this->transaction_library->add_transaction($api_key, $transaction_data) !== FALSE) {
            $response['message'] = $this->transaction_library->messages_array();
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
        } else {
            $response['message'] = $this->transaction_library->errors_array();
            $response['response_code'] = ERROR_CODE_SERVER_EXCEPTION;
        }
        $response['result_event'] = $result;
        echo json_encode($response);
    }

    public function dbbl() {
        $cell_no = $this->input->post('number');
        $amount = $this->input->post('amount');
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $result = array();
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
        $this->load->library('transaction_library');
        if ($this->transaction_library->add_transaction($api_key, $transaction_data) !== FALSE) {
            $response['message'] = $this->transaction_library->messages_array();
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
        } else {
            $response['message'] = $this->transaction_library->errors_array();
            $response['response_code'] = ERROR_CODE_SERVER_EXCEPTION;
        }
        $response['result_event'] = $result;
        echo json_encode($response);
    }

    public function mcash() {
        $cell_no = $this->input->post('number');
        $amount = $this->input->post('amount');
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $result = array();
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
        $this->load->library('transaction_library');
        if ($this->transaction_library->add_transaction($api_key, $transaction_data) !== FALSE) {
            $response['message'] = $this->transaction_library->messages_array();
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
        } else {
            $response['message'] = $this->transaction_library->errors_array();
            $response['response_code'] = ERROR_CODE_SERVER_EXCEPTION;
        }
        $response['result_event'] = $result;
        echo json_encode($response);
    }

    public function ucash() {
        $cell_no = $this->input->post('number');
        $amount = $this->input->post('amount');
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $result = array();
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
        $this->load->library('transaction_library');
        if ($this->transaction_library->add_transaction($api_key, $transaction_data) !== FALSE) {
            $response['message'] = $this->transaction_library->messages_array();
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
        } else {
            $response['message'] = $this->transaction_library->errors_array();
            $response['response_code'] = ERROR_CODE_SERVER_EXCEPTION;
        }
        $response['result_event'] = $result;
        echo json_encode($response);
    }

    public function topup() {
        $cell_no = $this->input->post('number');
        $amount = $this->input->post('amount');
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $service_id = $this->input->post('topup_type_id');
        $topup_type_id = $this->input->post('operator_type_id');
        $result = array();
        $this->load->model('androidapp/app_reseller_model');
        $app_session_id_array = $this->app_reseller_model->get_app_session_id($user_id)->result_array();
        if (!empty($app_session_id_array) && $session_id != $app_session_id_array[0]['app_session_id']) {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
            $response['message'] = "Sorry !! session id doesn't match !";
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
        $this->load->library('transaction_library');
        if ($this->transaction_library->add_transaction($api_key, $transaction_data) !== FALSE) {
            $response['message'] = $this->transaction_library->messages_array();
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
        } else {
            $response['message'] = $this->transaction_library->errors_array();
            $response['response_code'] = ERROR_CODE_SERVER_EXCEPTION;
        }
        $response['result_event'] = $result;
        echo json_encode($response);
    }

    public function get_bkash_transaction_list() {
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $response = array();
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
            $transaction_list = $transaction_list_array['transaction_list'];
            $response['transaction_list'] = $transaction_list;
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
            $response['message'] = "Transaction list.";
        }
        echo json_encode($response);
    }

    public function get_dbbl_transaction_list() {
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $response = array();
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
            $transaction_list = $transaction_list_array['transaction_list'];
            $response['transaction_list'] = $transaction_list;
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
            $response['message'] = "Transaction list.";
        }
        echo json_encode($response);
    }

    public function get_mcash_transaction_list() {
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $response = array();
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
            $transaction_list = $transaction_list_array['transaction_list'];
            $response['transaction_list'] = $transaction_list;
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
            $response['message'] = "Transaction list.";
        }
        echo json_encode($response);
    }

    public function get_ucash_transaction_list() {
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $response = array();
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
            $transaction_list = $transaction_list_array['transaction_list'];
            $response['transaction_list'] = $transaction_list;
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
            $response['message'] = "Transaction list.";
        }
        echo json_encode($response);
    }

    public function get_topup_transaction_list() {
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $response = array();
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
            $transaction_list = $transaction_list_array['transaction_list'];
            $response['transaction_list'] = $transaction_list;
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
            $response['message'] = "Transaction list.";
        }
        echo json_encode($response);
    }

    public function get_payment_transaction_list() {
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $response = array();
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
        $payment_info_list = $this->payment_library->get_payment_history(array(PAYMENT_TYPE_ID_SEND_CREDIT, PAYMENT_TYPE_ID_RETURN_CREDIT), array(), 0, 0, PAYMENT_LIST_DEAFULT_LIMIT, PAYMENT_LIST_DEAFULT_OFFSET, 'desc', $where);
        if (!empty($payment_info_list)) {
            $response['payment_list'] = $payment_info_list['payment_list'];
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
            $response['message'] = "Transaction list.";
        }
        echo json_encode($response);
    }

    public function qrtransaction() {
        $cell_no = $this->input->post('number');
        $amount = $this->input->post('amount');
        $response['response_code'] = 2000;
        $response['message'] = "Transaction is executed successfully";
        echo json_encode($response);
    }

}
