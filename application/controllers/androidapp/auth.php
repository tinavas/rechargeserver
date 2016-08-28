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
			else
			{
				$response['response_code'] = ERROR_CODE_UNABLE_TO_CREATE_SESSION;
				echo json_encode($response);
				return;
			}
            $this->load->library('reseller_library');
            $user_info_array = $this->reseller_model->get_user_info($user_id)->result_array();
            if (!empty($user_info_array)) {
                //we will show username instead of first name and last name
				$user_info = array(
                    'user_id' => $user_id,
                    'first_name' => $user_info_array[0]['username'],
                    'last_name' => ''
                );
                $current_balance = $this->reseller_library->get_user_current_balance($user_id);
                $result['user_info'] = $user_info;
                $result['current_balance'] = $current_balance;
                $service_id_list = array();
                $this->load->model('service_model');
                $service_list = $this->service_model->get_user_assigned_services($user_id)->result_array();
                if (!empty($service_list)) {
                    foreach ($service_list as $service) {
                        //in json int database type will be used
						$service_id_list[] = +($service['service_id']);                        
                    }
                }
                $result['service_id_list'] = $service_id_list;
                $response['result_event'] = $result;
                $response['response_code'] = RESPONSE_CODE_SUCCESS;
            }
            $response['message'] = $this->ion_auth->messages();
        } else {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
            $response['message'] = $this->ion_auth->errors();
        }
        echo json_encode($response);
    }

    function get_user_basic_info() {
        $response = array();
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        
        //check if user name or password or pin code is changed or not. If changed then return an error code
        $user_name = $this->input->post('user_name');
        $password = $this->input->post('password');
        $pin_code = $this->input->post('pin_code');
        
        $this->load->model('androidapp/app_reseller_model');
        $app_session_id_array = $this->app_reseller_model->get_app_session_id($user_id)->result_array();
        if (!empty($app_session_id_array) && $session_id != $app_session_id_array[0]['app_session_id']) {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
            $response['message'] = "Sorry !! session id doesn't match !";
            echo json_encode($response);
            return;
        }
        $where = array(
            'id' => $user_id,
            'app_session_id' => $session_id
        );
        $this->load->model('androidapp/app_reseller_model');
        $user_info_array = $this->app_reseller_model->where($where)->get_user_information()->result_array();
        if (!empty($user_info_array)) {
            //we will show username instead of first name and last name
			$user_info = array(
				'user_id' => $user_id,
				'first_name' => $user_info_array[0]['username'],
				'last_name' => ''
			);
            $result['user_info'] = $user_info;
            $this->load->library('reseller_library');
            $current_balance = $this->reseller_library->get_user_current_balance($user_id);
            $result['current_balance'] = $current_balance;
            $result['session_id'] = $session_id;
            $service_id_list = array();
                $this->load->model('service_model');
                $service_list = $this->service_model->get_user_assigned_services($user_id)->result_array();
                if (!empty($service_list)) {
                    foreach ($service_list as $service) {
                        //in json int database type will be used
						$service_id_list[] = +($service['service_id']);                        
                    }
                }
            $result['service_id_list'] = $service_id_list;
            $response['result_event'] = $result;
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
        } else {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
        }
        echo json_encode($response);
    }

    function check_user_varification() {
        $response = array();        
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $pin_code = $this->input->post('pin_code');	
		$this->load->model('androidapp/app_reseller_model');
        $pin_info_array = $this->app_reseller_model->where(array('user_id' => $user_id, 'pin' => $pin_code))->get_pin_info()->result_array();
        if (empty($pin_info_array)) {
            $response['response_code'] = ERROR_CODE_INVALID_PIC_CODE;
            echo json_encode($response);
            return;
        }
        $where = array(
            'id' => $user_id,
            'app_session_id' => $session_id
        );
        $user_info_array = $this->app_reseller_model->where($where)->get_user_information()->result_array();
		if (!empty($user_info_array)) {
            //we will show username instead of first name and last name
			$user_info = array(
				'user_id' => $user_id,
				'first_name' => $user_info_array[0]['username'],
				'last_name' => ''
			);
            $result['user_info'] = $user_info;
            $this->load->library('reseller_library');
            $current_balance = $this->reseller_library->get_user_current_balance($user_id);
            $result['current_balance'] = $current_balance;
			$result['session_id'] = $session_id;
            $service_id_list = array();
            $this->load->model('service_model');
            $service_list = $this->service_model->get_user_assigned_services($user_id)->result_array();
            if (!empty($service_list)) {
                foreach ($service_list as $service) {
                    //in json int database type will be used
					$service_id_list[] = +($service['service_id']);                        
                }
            }
            $result['service_id_list'] = $service_id_list;
            $response['result_event'] = $result;
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
        } else {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
        }
         echo json_encode($response);
    }
	
    function update_password() 
    {
        $response = array();
        $user_id = $this->input->post('user_id');
        $session_id = $this->input->post('session_id');
        $password = $this->input->post('pwd');
        
        $this->load->model('androidapp/app_reseller_model');
        $app_session_id_array = $this->app_reseller_model->get_app_session_id($user_id)->result_array();
        if (!empty($app_session_id_array) && $session_id != $app_session_id_array[0]['app_session_id']) {
            $response['response_code'] = ERROR_CODE_SESSION_EXPIRED;
            $response['message'] = "Sorry !! session id doesn't match !";
            echo json_encode($response);
            return;
        }
        
        $additional_data['password'] = $password;
        $this->load->library("security");
        $additional_data = $this->security->xss_clean($additional_data);
        if ($this->ion_auth->update($user_id, $additional_data) !== FALSE) {
            $response['message'] = 'User is updated successfully.';
            $response['response_code'] = RESPONSE_CODE_SUCCESS;
        } else {
            $response['message'] = 'Error while updating the user. Please try again later.';
            $response['response_code'] = ERROR_CODE_SERVER_EXCEPTION;
        }
        echo json_encode($response);
    }

}
