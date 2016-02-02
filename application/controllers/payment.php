<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payment extends Role_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->library('ion_auth');
        $this->load->model('payment_model');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    public function index() {
        
    }

    public function create_payment($child_id = 0) {
        $parent_id = $this->session->userdata('user_id');
        $response = array();
        if (file_get_contents("php://input") != null) {
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "paymentInfo") != FALSE) {
                $paymentInfo = $requestInfo->paymentInfo;
                if (property_exists($paymentInfo, "amount")) {
                    $amount = $paymentInfo->amount;
                }
                if (property_exists($paymentInfo, "payment_type")) {
                    $payment_type_id = $paymentInfo->payment_type;
                }
                if (property_exists($paymentInfo, "description")) {
                    $description = $paymentInfo->description;
                }

                $sender_data = array(
                    'balance_in' => 0,
                    'balance_out' => $amount
                );

                $receiver_data = array(
                    'balance_out' => 0,
                    'balance_in' => $amount
                );
                if (isset($description)) {
                    $sender_data['description'] = $description;
                    $receiver_data['description'] = $description;
                }
                $this->load->library('reseller_library');
                if ($payment_type_id == PAYMENT_TYPE_ID_SEND_CREDIT) {
                    if ($amount > $this->reseller_library->get_user_current_balance($parent_id)) {
                        $response['message'] = 'Sorry! Insaficient Balance !';
                        echo json_encode($response);
                        return;
                    };

                    $sender_data['user_id'] = $parent_id;
                    $sender_data['type_id'] = PAYMENT_TYPE_ID_SEND_CREDIT;

                    $receiver_data['user_id'] = $child_id;
                    $receiver_data['type_id'] = PAYMENT_TYPE_ID_RECEIVE_CREDIT;
                } else if ($payment_type_id == PAYMENT_TYPE_ID_RETURN_CREDIT) {
                    if ($amount > $this->reseller_library->get_user_current_balance($child_id)) {
                        $response['message'] = 'Sorry! Insaficient Balance !';
                        echo json_encode($response);
                        return;
                    };
                    $sender_data['user_id'] = $child_id;
                    $sender_data['type_id'] = PAYMENT_TYPE_ID_RETURN_CREDIT;

                    $receiver_data['user_id'] = $parent_id;
                    $receiver_data['type_id'] = PAYMENT_TYPE_ID_RETURN_RECEIVE_CREDIT;
                }

                if ($this->payment_model->transfer_user_payment($sender_data, $receiver_data) !== FALSE) {
                    $response['message'] = 'Payment is updated successfully.';
                } else {
                    $response['message'] = 'Error while updating the payment. Please try later.';
                }
            }
            echo json_encode($response);
            return;
        }

        $payment_type_list = array(
            PAYMENT_TYPE_ID_SEND_CREDIT => 'Payment',
            PAYMENT_TYPE_ID_RETURN_CREDIT => 'Return'
        );

        $this->data['payment_type_list'] = json_encode($payment_type_list);
        $this->data['user_id'] = $child_id;
        $this->data['app'] = PAYMENT_APP;
        $this->template->load(null, 'payment/create_payment', $this->data);
    }

    /**
     * this method return balance from child to parent
     * 
     * 
     *  */
    public function reseller_return_balance() {
        $user_id = $this->session->userdata('user_id');
        $this->load->library('reseller_library');
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
                if ($amount > $this->reseller_library->get_user_current_balance($user_id)) {
                    $response['message'] = 'Sorry! Insaficient Balance !';
                    echo json_encode($response);
                    return;
                };

                $parent_user_id = $this->reseller_library->get_parent_user_id($user_id);
                if ($parent_user_id == 0) {
                    $response['message'] = 'Error !Parent information not found  !';
                    echo json_encode($response);
                    return;
                }
                // child payment info
                $user_data = array(
                    'balance_in' => 0,
                    'balance_out' => $amount
                );
                $user_data['user_id'] = $user_id;
                $user_data['type_id'] = PAYMENT_TYPE_ID_SEND_CREDIT;
                // parent payment info
                $parent_data = array(
                    'balance_in' => $amount,
                    'balance_out' => 0
                );
                $parent_data['user_id'] = $parent_user_id;
                $parent_data['type_id'] = PAYMENT_TYPE_ID_RECEIVE_CREDIT;
                if (isset($description)) {
                    $user_data['description'] = $description;
                    $parent_data['description'] = $description;
                }
                $this->load->model('payment_model');
                if ($this->payment_model->transfer_user_payment($user_data, $parent_data) !== FALSE) {
                    $response['message'] = 'Return balance successfully.';
                } else {
                    $response['message'] = 'Error while return the balance. Please try later.';
                }
            }
            echo json_encode($response);
            return;
        }
        $this->data['app'] = PAYMENT_APP;
        $this->template->load(null, 'reseller/return_balance', $this->data);
    }

}
