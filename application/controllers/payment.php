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
            //redirect('auth/login', 'refresh');
        }
    }

    public function test() {
        $user_id = $this->session->userdata('user_id');
        $result = $this->payment_model->get_user_current_balance($user_id)->result_array();
        var_dump($result);
        exit;


//        $this->load->library('Date_utils');
//            $this->date_utils->server_start_unix_time_of_today();
    }

    public function index() {
        
    }

    public function amount_validation($user_id, $amount) {
        $current_balance = $this->payment_model->get_user_current_balance($user_id)->result_array();
        if ($current_balance != null) {
            $current_balance = $current_balance[0];
            $current_available_blance = $current_balance['current_balance'];
            $balance_after_transction = $current_available_blance - $amount;
            if ($amount > $current_available_blance || $balance_after_transction < USER_MINIMUM_REQUIRED_BALANCE) {
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }

    public function create_payment($user_id = 0) {
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
                if ($payment_type_id == PAYMENT_TYPE_ID_SEND_CREDIT) {
                    if ($this->amount_validation($this->session->userdata('user_id'), $amount) == FALSE) {
                        $response['message'] = 'Sorry! Insaficient Balance !';
                        echo json_encode($response);
                        return;
                    };

                    $sender_data['user_id'] = $this->session->userdata('user_id');
                    $sender_data['type_id'] = PAYMENT_TYPE_ID_SEND_CREDIT;

                    $receiver_data['user_id'] = $user_id;
                    $receiver_data['type_id'] = PAYMENT_TYPE_ID_RECEIVE_CREDIT;
                } else if ($payment_type_id == PAYMENT_TYPE_ID_RETURN_CREDIT) {
                  if ($this->amount_validation($user_id, $amount) == FALSE) {
                        $response['message'] = 'Sorry! Insaficient Balance !';
                        echo json_encode($response);
                        return;
                    };
                    $sender_data['user_id'] = $user_id;
                    $sender_data['type_id'] = PAYMENT_TYPE_ID_RETURN_CREDIT;

                    $receiver_data['user_id'] = $this->session->userdata('user_id');
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
        $this->data['user_id'] = $user_id;
        $this->template->load('admin/templates/admin_tmpl', 'payment/create_payment', $this->data);
    }

}
