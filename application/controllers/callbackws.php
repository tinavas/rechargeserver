<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Callbackws extends CI_Controller {
    function __construct() {
        parent::__construct();    
    }
    /*
     * Call back function to update trnsaction status
     */
    public function update_transaction_status()
    {
        $transaction_id = $_POST["transaction_id"];
        $status_id = $_POST["status_id"];
        $sender_cell_number = $_POST["sender_cell_number"];
        $trx_id_operator = $_POST["trx_id_operator"];
        $this->load->model('transaction_model');
        echo $this->transaction_model->update_transaction_callbackws($transaction_id, $status_id, $sender_cell_number, $trx_id_operator);        
    }
    
    public function update_transaction_editable_status()
    {
        $transaction_id = $_POST["transaction_id"];
        $editable = $_POST["editable"];
        $this->load->model('transaction_model');
        $this->transaction_model->callbackws_update_transaction_editable_status($transaction_id, $editable);
        echo true;
    }
}