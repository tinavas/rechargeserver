<?php

class Admin extends Role_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        //$this->load->library('transaction_library');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    public function index() {
        
    }

    public function load_balance() {
        $user_id = $this->session->userdata('user_id');
        $response = array();
        if (file_get_contents("php://input") != null) {
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "paymentInfo") != FALSE) {
                $paymentInfo = $requestInfo->paymentInfo;
                if (property_exists($paymentInfo, "amount")) {
                    $amount = $paymentInfo->amount;
                }
                if (property_exists($paymentInfo, "description")) {
                    $description = $paymentInfo->description;
                }

                $user_data = array(
                    'balance_in' => $amount,
                    'balance_out' => 0
                );

                if (isset($description)) {
                    $user_data['description'] = $description;
                }
                $user_data['user_id'] = $user_id;
                $user_data['reference_id'] = $user_id;
                $user_data['type_id'] = PAYMENT_TYPE_ID_LOAD_BALANCE;
                $this->load->library('utils');
                $user_data['transaction_id'] = $this->utils->get_transaction_id();
                $this->load->model('admin/admin_payment_model');
                if ($this->admin_payment_model->load_balance($user_data) !== FALSE) {
                    $response['message'] = 'Load Balance successfully.';
                } else {
                    $response['message'] = 'Error while updating the payment. Please try later.';
                }
            }
            echo json_encode($response);
            return;
        }


        $this->data['message'] = "";
        $this->data['app'] = PAYMENT_APP;
        $this->template->load('admin/templates/admin_tmpl', 'admin/payment/load_balance', $this->data);
    }

    /**
     * this method return admin profile info and used services
     * 
     */

    function get_profile_info() {
        $this->load->model('service_model');
        $user_id = $this->session->userdata('user_id');
        $service_list = $this->service_model->get_user_services($user_id)->result_array();
        $this->load->model('reseller_model');
        $user_profile_info = array();
        $profile_info = $this->reseller_model->get_profile_info($user_id)->result_array();
        if (!empty($profile_info)) {
            $profile_info = $profile_info[0];
            $user_profile_info = $profile_info;
        }
        $this->data['profile_info'] = $user_profile_info;
        $this->data['service_list'] = $service_list;
        $this->data['app'] = RESELLER_APP;
        $this->template->load(null, 'admin/account/profile', $this->data);
    }

}
