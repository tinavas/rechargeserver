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
		$session_id = $this->input->post('session_id');
		$current_session_id = $this->session->userdata('app_session_id');
		/*if($session_id != $current_session_id)
		{
			$response['current_session_id'] = $current_session_id;
			$response['response_code'] = 5001;
			$response['message'] = "Successful";
			echo json_encode($response);
			return;
		}*/
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
	
	public function dbbl()
    {
		$response['response_code'] = 2000;
        $response['message'] = "Successful";
        $result = array(
            'current_balance' => 9950
        );        
        $response['result_event'] = $result;
        echo json_encode($response);
	}
	
	public function mcash()
    {
		$response['response_code'] = 2000;
        $response['message'] = "Successful";
        $result = array(
            'current_balance' => 9950
        );        
        $response['result_event'] = $result;
        echo json_encode($response);
	}
	
	public function ucash()
    {
		$response['response_code'] = 2000;
        $response['message'] = "Successful";
        $result = array(
            'current_balance' => 9950
        );        
        $response['result_event'] = $result;
        echo json_encode($response);
	}
	
	public function topup()
    {
		$response['response_code'] = 2000;
        $response['message'] = "Successful";
        $result = array(
            'current_balance' => 9950
        );        
        $response['result_event'] = $result;
        echo json_encode($response);
	}
    
    public function get_bkash_transaction_list()
    {
        //param1 user id
        //param2 service id
        //param3 offset
        //param4 limit
        
        $transaction1 = array(
            'cell_no' => '01723456789',
            'amount' => 4000,
            'title' => 'BKash',
			'status' => 'Success'
        );
        
        $transaction_list = array($transaction1);
		$response = array(
			'transaction_list' => $transaction_list
		);
        $response['response_code'] = 2000;
        $response['message'] = "Transaction list.";
        echo json_encode($response);        
    }
	
	public function get_dbbl_transaction_list()
    {
        $transaction1 = array(
            'cell_no' => '01723456789',
            'amount' => 3000,
            'title' => 'DBBL',
			'status' => 'Success'
        );
        
        $transaction_list = array($transaction1);
		$response = array(
			'transaction_list' => $transaction_list
		);
        $response['response_code'] = 2000;
        $response['message'] = "Transaction list.";
        echo json_encode($response);        
    }
	
	public function get_mcash_transaction_list()
    {
        $transaction1 = array(
            'cell_no' => '01723456789',
            'amount' => 2000,
            'title' => 'Mcash',
			'status' => 'Success'
        );
        
        $transaction_list = array($transaction1);
		$response = array(
			'transaction_list' => $transaction_list
		);
        $response['response_code'] = 2000;
        $response['message'] = "Transaction list.";
        echo json_encode($response);        
    }
	
	public function get_ucash_transaction_list()
    {
        $transaction1 = array(
            'cell_no' => '01723456789',
            'amount' => 1000,
            'title' => 'Ucash',
			'status' => 'Success'
        );
        
        $transaction_list = array($transaction1);
		$response = array(
			'transaction_list' => $transaction_list
		);
        $response['response_code'] = 2000;
        $response['message'] = "Transaction list.";
        echo json_encode($response);        
    }
	
	public function get_topup_transaction_list()
    {
        $transaction1 = array(
            'cell_no' => '01711123456',
            'amount' => 500,
            'title' => 'GP',
			'status' => 'Success'
        );
		$transaction2 = array(
            'cell_no' => '01811123456',
            'amount' => 400,
            'title' => 'Robi',
			'status' => 'Success'
        );
		$transaction3 = array(
            'cell_no' => '01911123456',
            'amount' => 300,
            'title' => 'Banglalink',
			'status' => 'Success'
        );
		$transaction4 = array(
            'cell_no' => '01611123456',
            'amount' => 200,
            'title' => 'Airtel',
			'status' => 'Success'
        );
		$transaction5 = array(
            'cell_no' => '01511123456',
            'amount' => 100,
            'title' => 'Teletalk',
			'status' => 'Success'
        );
        
        $transaction_list = array($transaction1, $transaction2, $transaction3, $transaction4, $transaction5);
		$response = array(
			'transaction_list' => $transaction_list
		);
        $response['response_code'] = 2000;
        $response['message'] = "Transaction list.";
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
