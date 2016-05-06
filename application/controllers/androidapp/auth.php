<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    function __construct() {
        parent::__construct();        
    }

    //log the user in
    function login() {
        $response = array();
        $this->load->library('ion_auth');
        //$email = $this->input->post('email');
        //$password = $this->input->post('password');
        $email = "admin@admin.com";
        $password = "password";
        if($this->ion_auth->login($email, $password))
        {
            $user_id = $this->session->userdata('user_id');
            $this->load->library('reseller_library');
            $user_info_array = $this->reseller_model->get_user_info($user_id)->result_array();
            if(!empty($user_info_array))
            {
                $user_info = array(
                    'user_id' => $user_id,
                    'first_name' => $user_info_array[0]['first_name'],
                    'last_name' => $user_info_array[0]['last_name']
                );
                $current_balance = $this->reseller_library->get_user_current_balance($user_id);
                $result['user_info'] = $user_info;
                $result['current_balance'] = $current_balance;
                $service_id_list = array(
                    SERVICE_TYPE_ID_BKASH_CASHIN
                );
                $result['service_id_list'] = $service_id_list;
                $response['result_event'] = $result;
                $response['response_code'] = 2000;
            }
            $response['message'] =$this->ion_auth->messages();
        }
        else
        {
            $response['response_code'] = 5001;
            $response['message'] = $this->ion_auth->errors();
        }
        echo json_encode($response);
    }
}
