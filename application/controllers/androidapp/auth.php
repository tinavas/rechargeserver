<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function login() {
        $response = array();
        $this->load->library('ion_auth');
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        if ($this->ion_auth->login($email, $password)) {
            $user_id = $this->session->userdata('user_id');
            $session_id = $this->session->userdata('session_id');
            $this->load->model('androidapp/app_reseller_model');
            $is_updated = $this->app_reseller_model->add_app_session_id($user_id, $session_id);
            if ($is_updated != FALSE) {
                $result['session_id'] = $session_id;
            }      
            $this->load->library('reseller_library');
            $user_info_array = $this->reseller_model->get_user_info($user_id)->result_array();
            if (!empty($user_info_array)) {
                $user_info = array(
                    'user_id' => $user_id,
                    'first_name' => $user_info_array[0]['first_name'],
                    'last_name' => $user_info_array[0]['last_name']
                );
                $current_balance = $this->reseller_library->get_user_current_balance($user_id);
                $result['user_info'] = $user_info;
                $result['current_balance'] = $current_balance;
                $service_id_list = array(
                    SERVICE_TYPE_ID_BKASH_CASHIN,
                    SERVICE_TYPE_ID_DBBL_CASHIN,
                    SERVICE_TYPE_ID_MCASH_CASHIN,
                    SERVICE_TYPE_ID_UCASH_CASHIN,
                    SERVICE_TYPE_ID_TOPUP_GP,
                    SERVICE_TYPE_ID_TOPUP_ROBI,
                    SERVICE_TYPE_ID_TOPUP_BANGLALINK,
                    SERVICE_TYPE_ID_TOPUP_AIRTEL,
                    SERVICE_TYPE_ID_TOPUP_TELETALK
                );
                $result['service_id_list'] = $service_id_list;
                $response['result_event'] = $result;
                $response['response_code'] = RESPONSE_CODE_SUCCESS;
            }
            $response['message'] = $this->ion_auth->messages();
        } else {
            $response['response_code'] = ERROR_CODE_SERVER_EXCEPTION;
            $response['message'] = $this->ion_auth->errors();
        }
        echo json_encode($response);
    }

    function get_user_basic_info() {
        $response = array();
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        
//        $user_id = 1;
//        $session_id = 1234;
        $where = array(
            'id' => $user_id,
            'app_session_id' => $session_id
        );
        $this->load->model('androidapp/app_reseller_model');
        $user_info_array = $this->app_reseller_model->where($where)->get_user_information()->result_array();
        if (!empty($user_info_array)) {
            $user_info = array(
                'user_id' => $user_id,
                'first_name' => $user_info_array[0]['first_name'],
                'last_name' => $user_info_array[0]['last_name']
            );
            $result['user_info'] = $user_info;
            $this->load->library('reseller_library');
            $current_balance = $this->reseller_library->get_user_current_balance($user_id);
            $result['current_balance'] = $current_balance;
            $service_id_list = array(
                SERVICE_TYPE_ID_BKASH_CASHIN,
                SERVICE_TYPE_ID_DBBL_CASHIN,
                SERVICE_TYPE_ID_MCASH_CASHIN,
                SERVICE_TYPE_ID_UCASH_CASHIN,
                SERVICE_TYPE_ID_TOPUP_GP,
                SERVICE_TYPE_ID_TOPUP_ROBI,
                SERVICE_TYPE_ID_TOPUP_BANGLALINK,
                SERVICE_TYPE_ID_TOPUP_AIRTEL,
                SERVICE_TYPE_ID_TOPUP_TELETALK
            );
            $result['service_id_list'] = $service_id_list;
            $response['result_event'] = $result;
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
        } else {
            $response['response_code'] = ERROR_CODE_SERVER_EXCEPTION;
        }
        echo json_encode($response);
    }

}
