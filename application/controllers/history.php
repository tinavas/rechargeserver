<?php

class History extends Role_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('transaction_library');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    public function index() {
        
    }

    public function all() {
        $offset = INITIAL_OFFSET;
        $this->data['message'] = "";
        $this->load->library('date_utils');
        $transaction_info_list = array();
        if (file_get_contents("php://input") != null) {
            $response = array();
            $from_date = 0;
            $to_date = 0;
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "fromDate") != FALSE) {
                $from_date = $requestInfo->fromDate;
            }
            if (property_exists($requestInfo, "toDate") != FALSE) {
                $to_date = $requestInfo->toDate;
            }
            if (property_exists($requestInfo, "offset") != FALSE) {
                $offset = $requestInfo->offset;
            }
            if ($from_date != 0 && $to_date != 0) {
                $from_date = $this->date_utils->convert_date_to_unix_time($from_date);
                $to_date = $this->date_utils->convert_date_to_unix_time($to_date);
            }
            $transaction_list = $this->transaction_library->get_transaction_list($offset, $from_date, $to_date);
            $response['transaction_list'] = $transaction_list;
            echo json_encode($response);
            return;
        }
        $transaction_list = $this->transaction_library->get_transaction_list($offset);
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load('admin/templates/admin_tmpl', 'history/index', $this->data);
    }

    public function topup() {
        $this->data['message'] = "";
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_list = $this->transaction_model->where($where)->get_user_transaction_list(array(SERVICE_TYPE_ID_TOPUP_GP, SERVICE_TYPE_ID_TOPUP_ROBI, SERVICE_TYPE_ID_TOPUP_BANGLALINK, SERVICE_TYPE_ID_TOPUP_AIRTEL, SERVICE_TYPE_ID_TOPUP_TELETALK))->result_array();
        $this->data['transaction_list'] = $transaction_list;
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load('admin/templates/admin_tmpl', 'history/topup/index', $this->data);
    }

    public function bkash() {
        $this->data['message'] = "";
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_list = $this->transaction_model->where($where)->get_user_transaction_list(array(SERVICE_TYPE_ID_BKASH_CASHIN))->result_array();
        $this->data['transaction_list'] = $transaction_list;
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load('admin/templates/admin_tmpl', 'history/bkash/index', $this->data);
    }

    public function dbbl() {
        $this->data['message'] = "";
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_list = $this->transaction_model->where($where)->get_user_transaction_list(array(SERVICE_TYPE_ID_DBBL_CASHIN))->result_array();
        $this->data['transaction_list'] = $transaction_list;
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load('admin/templates/admin_tmpl', 'history/dbbl/index', $this->data);
    }

    public function mcash() {
        $this->data['message'] = "";
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_list = $this->transaction_model->where($where)->get_user_transaction_list(array(SERVICE_TYPE_ID_MCASH_CASHIN))->result_array();
        $this->data['transaction_list'] = $transaction_list;
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load('admin/templates/admin_tmpl', 'history/mcash/index', $this->data);
    }

    public function ucash() {
        $this->data['message'] = "";
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_list = $this->transaction_model->where($where)->get_user_transaction_list(array(SERVICE_TYPE_ID_UCASH_CASHIN))->result_array();
        $this->data['transaction_list'] = $transaction_list;
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load('admin/templates/admin_tmpl', 'history/ucash/index', $this->data);
    }

    public function get_payment_history() {
        $limit = INITIAL_LIMIT;
        $offset = INITIAL_OFFSET;
        $payment_type_ids = array(
            PAYMENT_TYPE_ID_SEND_CREDIT,
            PAYMENT_TYPE_ID_RETURN_CREDIT
        );
        $user_id = $this->session->userdata('user_id');
        $this->load->library('date_utils');
        $payment_info_list = array();
        if (file_get_contents("php://input") != null) {
            $response = array();
            $start_date = 0;
            $end_date = 0;
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);

            if (property_exists($requestInfo, "searchParam") != FALSE) {
                $search_param = $requestInfo->searchParam;

                if (property_exists($search_param, "startDate") != FALSE) {
                    $start_date = $search_param->startDate;
                }
                if (property_exists($search_param, "endDate") != FALSE) {
                    $end_date = $search_param->endDate;
                }
                if (property_exists($search_param, "paymentTypeId") != FALSE) {
                    $payment_type_ids = array();
                    $payment_type_ids = $search_param->paymentTypeId;
                }
            }
            if (property_exists($requestInfo, "offset") != FALSE) {
                $offset = $requestInfo->offset;
            }
            if ($start_date != 0 && $end_date != 0) {
                $start_date = $this->date_utils->convert_date_to_unix_time($start_date);
                $end_date = $this->date_utils->convert_date_to_unix_time($end_date);
            }
            $payment_info_list = $this->transaction_library->get_payment_list($user_id, $payment_type_ids, $limit, $offset, $start_date, $end_date);
            $response['payment_info_list'] = $payment_info_list;
            echo json_encode($response);
            return;
        }
        $payment_info_list = $this->transaction_library->get_payment_list($user_id, $payment_type_ids, $limit, $offset);
        $payment_type_ids = array(
            PAYMENT_TYPE_ID_SEND_CREDIT => 'Send credit',
            PAYMENT_TYPE_ID_RETURN_CREDIT => 'Return Credit'
        );
        $this->data['payment_type_ids'] = $payment_type_ids;
        $this->data['payment_info_list'] = json_encode($payment_info_list);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load('admin/templates/admin_tmpl', 'history/payment_history', $this->data);
    }

    public function get_receive_history() {
        $limit = INITIAL_LIMIT;
        $offset = INITIAL_OFFSET;
        $groups = $this->ion_auth->get_current_user_types();
        $payment_type_ids = array(
            PAYMENT_TYPE_ID_RECEIVE_CREDIT,
            PAYMENT_TYPE_ID_RETURN_RECEIVE_CREDIT
        );
        $payment_types = array(
            PAYMENT_TYPE_ID_RECEIVE_CREDIT => 'Receive Credit',
            PAYMENT_TYPE_ID_RETURN_RECEIVE_CREDIT => 'Return Receive Credit'
        );
        foreach ($groups as $group) {
            if ($group == ADMIN) {
                $payment_type_ids[] = PAYMENT_TYPE_ID_LOAD_BALANCE;
                $payment_types[PAYMENT_TYPE_ID_LOAD_BALANCE] = 'Load Balance';
            }
        }
        $user_id = $this->session->userdata('user_id');
        $this->load->library('date_utils');
        if (file_get_contents("php://input") != null) {
            $response = array();
            $start_date = 0;
            $end_date = 0;
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "searchParam") != FALSE) {
                $search_param = $requestInfo->searchParam;
                if (property_exists($search_param, "startDate") != FALSE) {
                    $start_date = $search_param->startDate;
                }
                if (property_exists($search_param, "endDate") != FALSE) {
                    $end_date = $search_param->endDate;
                }
                if (property_exists($search_param, "paymentTypeId") != FALSE) {
                    $payment_type_ids = array();
                    $payment_type_ids = $search_param->paymentTypeId;
                }
            }
            if (property_exists($requestInfo, "offset") != FALSE) {
                $offset = $requestInfo->offset;
            }
            if ($start_date != 0 && $end_date != 0) {
                $start_date = $this->date_utils->convert_date_to_unix_time($start_date);
                $end_date = $this->date_utils->convert_date_to_unix_time($end_date);
            }
            $payment_info_list = $this->transaction_library->get_payment_list($user_id, $payment_type_ids, $limit, $offset, $start_date, $end_date);
            $response['payment_info_list'] = $payment_info_list;
            echo json_encode($response);
            return;
        }
        $payment_info_list = $this->transaction_library->get_payment_list($user_id, $payment_type_ids, $limit, $offset);

        $this->data['payment_type_ids'] = $payment_types;
        $this->data['payment_info_list'] = json_encode($payment_info_list);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load('admin/templates/admin_tmpl', 'history/receive_history', $this->data);
    }

}
