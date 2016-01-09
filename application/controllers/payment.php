<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends Role_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in()) {
            //redirect('auth/login', 'refresh');
        }
    }
    
    public function test()
    {
            $this->load->library('Date_utils');
            $this->date_utils->server_start_unix_time_of_today();
    }
    
    public function index()
    {
        
    }
    
    public function create_payment($user_id)
    {
        $this->data['message'] = "";
        $this->form_validation->set_error_delimiters("<div style='color:red'>", '</div>');
        $this->form_validation->set_rules('amount', 'Amount', 'xss_clean|required');
        if ($this->input->post('submit_create_payment')) {
            if($this->form_validation->run() == true)
            {
                $payment_type_id = $this->input->post('payment_type_list');
                $amount = $this->input->post('amount');
                $sender_data = array(
                    'description' => $this->input->post('description'),
                    'balance_in' => 0,
                    'balance_out' => $amount
                );
                $receiver_data = array(
                    'description' => $this->input->post('description'),
                    'balance_in' => $amount,
                    'balance_out' => 0
                );
                if($payment_type_id == PAYMENT_TYPE_ID_SEND_CREDIT)
                {
                    $sender_data['user_id'] = $this->session->userdata('user_id');
                    $sender_data['type_id'] = PAYMENT_TYPE_ID_SEND_CREDIT;
                    
                    $receiver_data['user_id'] = $user_id;
                    $receiver_data['type_id'] = PAYMENT_TYPE_ID_RECEIVE_CREDIT;
                }
                else if($payment_type_id == PAYMENT_TYPE_ID_RETURN_CREDIT)
                {
                    $sender_data['user_id'] = $user_id;
                    $sender_data['type_id'] = PAYMENT_TYPE_ID_RETURN_CREDIT;
                    
                    $receiver_data['user_id'] = $this->session->userdata('user_id');
                    $receiver_data['type_id'] = PAYMENT_TYPE_ID_RETURN_RECEIVE_CREDIT;
                }
                
                $this->load->model('payment_model');
                if($this->payment_model->transfer_user_payment($sender_data, $receiver_data) !== FALSE)
                {
                    $this->data['message'] = 'Payment is updated successfully.';
                }
                else
                {
                    $this->data['message'] = 'Error while updating the payment. Please try later.';
                }
            }
            else
            {
                $this->data['message'] = validation_errors();
            }            
        }
        $this->data['payment_type_list'] = array(
            PAYMENT_TYPE_ID_SEND_CREDIT => 'Payment',
            PAYMENT_TYPE_ID_RETURN_CREDIT => 'Return'
        );
        $this->data['amount'] = array(
            'id' => 'amount',
            'name' => 'amount',
            'type' => 'text',
            'placeholder' => 'eg: 100'
        );
        $this->data['description'] = array(
            'id' => 'description',
            'name' => 'description',
            'type' => 'text'
        );
        $this->data['submit_create_payment'] = array(
            'id' => 'submit_create_payment',
            'name' => 'submit_create_payment',
            'type' => 'submit',
            'value' => 'Send',
        );
        $this->data['user_id'] = $user_id;
        $this->template->load('admin/templates/admin_tmpl','payment/create_payment', $this->data);
    }
    
    
}