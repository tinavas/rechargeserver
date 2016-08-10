<?php

class Admin extends Role_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    public function index() {
        
    }

    /*
     * This method will raise balance
     * @author nazmul hasan on 2nd March 2016
     */

    public function load_balance() {
        //checking whether user has administrator access or not
        $group = $this->session->userdata('group');
        if ($group != GROUP_ADMIN) {
            //you are not allowed to update rate
            $this->data['app'] = RESELLER_APP;
            $this->data['error_message'] = "Sorry !! You are not allowed to raise your balance.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }
        if (file_get_contents("php://input") != null) {
            $response = array();
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

                $user_id = $this->session->userdata('user_id');
                $this->load->library('utils');
                $transaction_id = $this->utils->get_transaction_id();
                $payment_data = array(
                    'user_id' => $user_id,
                    'reference_id' => $user_id,
                    'transaction_id' => $transaction_id,
                    'status_id' => TRANSACTION_STATUS_ID_SUCCESSFUL,
                    'description' => $paymentInfo->description,
                    'balance_in' => $paymentInfo->amount,
                    'balance_out' => 0,
                    'type_id' => PAYMENT_TYPE_ID_LOAD_BALANCE
                );
                $this->load->library("security");
                $payment_data = $this->security->xss_clean($payment_data);
                $this->load->model('admin/admin_payment_model');
                if ($this->admin_payment_model->load_balance($payment_data) !== FALSE) {
                    $response['message'] = 'Balance is raised successfully.';
                } else {
                    $response['message'] = 'Error while raising balance. Please try again later.';
                }
            }
            echo json_encode($response);
            return;
        }
        $this->data['message'] = "";
        $this->data['app'] = PAYMENT_APP;
        $this->template->load('admin/templates/admin_tmpl', 'admin/payment/load_balance', $this->data);
    }

}
