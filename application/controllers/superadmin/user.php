<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in()) {
            redirect('superadmin/auth/login', 'refresh');
        }
    }

    public function index() {
        
    }

    /*
     * This method will update user info
     */
    function update_user() {
        $user_id = $this->session->userdata('user_id');
        if (file_get_contents("php://input") != null) {
            $response = array();        
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "userInfo") != FALSE) {
                $userInfo = $requestInfo->userInfo;
                $new_password = "";
                $username = "";
                $email = "";
                $first_name = "";
                $last_name = "";
                $mobile = "";
                $pin = DEFAULT_PIN;
                if (!property_exists($userInfo, "username")) {
                    $response["message"] = "Please assign a user name !!";
                    echo json_encode($response);
                    return;
                }
                if (!property_exists($userInfo, "pin")) {
                    $response["message"] = "Please assign a pin!!";
                    echo json_encode($response);
                    return;
                }
                if (property_exists($userInfo, "username")) {
                    $username = $userInfo->username;
                }
                if (property_exists($userInfo, "new_password")) {
                    $new_password = $userInfo->new_password;
                }
                if (property_exists($userInfo, "pin")) {
                    $pin = $userInfo->pin;
                }
                if (property_exists($userInfo, "email")) {
                    $email = $userInfo->email;
                }
                if (property_exists($userInfo, "first_name")) {
                    $first_name = $userInfo->first_name;
                }
                if (property_exists($userInfo, "last_name")) {
                    $last_name = $userInfo->last_name;
                }
                if (property_exists($userInfo, "mobile")) {
                    $mobile = $userInfo->mobile;
                }
                if (property_exists($userInfo, "note")) {
                    $note = $userInfo->note;
                }
                $additional_data = array(
                    'username' => $username,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'mobile' => $mobile,
                    'email' => $email,
                    'note' => $note,
                    'pin' => $pin
                );
                $this->load->library("security");
                $additional_data = $this->security->xss_clean($additional_data);
                if ($this->ion_auth->update($user_id, $additional_data) !== FALSE) {
                    $response['message'] = 'User is updated successfully.';
                } else {
                    $response['message'] = $this->ion_auth->errors();
                }
            }
            else
            {
                $response['message'] = "Invalid user info.";
            }
            echo json_encode($response);
            return;
        }
        $this->load->model('superadmin/org/user_model');
        $user_info_array = $this->user_model->get_user_info($user_id)->result_array();
        if (!empty($user_info_array)) {
            $user_info = $user_info_array[0];
            $user_info['ip_address'] = "";
            $user_pin_info_array = $this->ion_auth->get_pin_info($user_id)->result_array();
            if (!empty($user_pin_info_array)) {
                $user_info['pin'] = $user_pin_info_array[0]['pin'];
            }
            $this->data['user_info'] = json_encode($user_info);
        }
        $this->data['app'] = S_USER_APP;
        $this->template->load(null, 'superadmin/user/update_user', $this->data);
    }
}
