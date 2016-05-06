<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends CI_Controller {

    function __construct() {
        parent::__construct();        
    }

    public function bkash()
    {
        $cell_no = $this->input->post('number');
        $amount = $this->input->post('amount');
        $user_id = $this->input->post('user_id');
        /*$api_key = API_KEY_BKASH_CASHIN;
        $transaction_id = "";
        $description = "test";
        $transaction_data = array(
            'user_id' => $user_id,
            'transaction_id' => $transaction_id,
            'service_id' => SERVICE_TYPE_ID_BKASH_CASHIN,
            'amount' => $amount,
            'cell_no' => $cell_no,
            'description' => $description
        );
        $this->load->library('transaction_library');
        if ($this->transaction_library->add_transaction($api_key, $transaction_data) !== FALSE) {
            $response['message'] = $this->transaction_library->messages_array();
            $response['response_code'] = 2000;
        } else {
            $response['message'] = $this->transaction_library->errors_array();
            $response['response_code'] = 50001;
        }*/
        $response['response_code'] = 2000;
        $response['message'] = "Successful";
        $result = array(
            'current_balance' => 9950
        );        
        $response['result_event'] = $result;
        echo json_encode($response);
    }
    
    public function get_transaction_list()
    {
        //param1 user id
        //param2 service id
        //param3 offset
        //param4 limit
        
        $transaction1 = array(
            'cell_no' => 01678112509,
            'amount' => 500,
            'title' => 'bKash cashin'
        );
        $transaction2 = array(
            'cell_no' => 01678112509,
            'amount' => 100,
            'title' => 'Gp topup'
        );
        $transaction_list = array($transaction1, $transaction2);
        $result = array(
            'transaction_list' => $transaction_list
        );
        $response['response_code'] = 2000;
        $response['message'] = "Transaction list.";
        $response['result_event'] = $result;
        echo json_encode($response);
        
    }
	
	public function qrtransaction()
    {
        $cell_no = $this->input->post('number');
        $amount = $this->input->post('amount');
        
        $response['response_code'] = 2000;
        $response['message'] = "Transaction is executed successfully";
        echo json_encode($response);
    }
}
