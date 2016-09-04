<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 */
class Transaction_library {

    public function __construct() {
        $this->load->model("superadmin/org/transaction_model");
    }

    /**
     * __call
     *
     * Acts as a simple way to call model methods without loads of stupid alias'
     *
     * */
    public function __call($method, $arguments) {
        if (!method_exists($this->transaction_model, $method)) {
            throw new Exception('Undefined method Transaction_library::' . $method . '() called');
        }

        return call_user_func_array(array($this->transaction_model, $method), $arguments);
    }

    /**
     * __get
     *
     * Enables the use of CI super-global without having to define an extra variable.
     *
     * I can't remember where I first saw this, so thank you if you are the original author. -Militis
     *
     * @access	public
     * @param	$var
     * @return	mixed
     */
    public function __get($var) {
        return get_instance()->$var;
    }

    public function get_transaction_list($service_id_list, $status_id_list, $process_id_list, $from_date = 0, $to_date = 0, $offset = 0, $limit = 0) {
        $this->load->library('date_utils');
        if ($from_date != 0) {
            $from_date = $this->date_utils->server_start_unix_time_of_date($from_date);
        }
        if ($to_date != 0) {
            $to_date = $this->date_utils->server_end_unix_time_of_date($to_date);
        }
        $total_transactions = 0;
        $transaction_summary_array = $this->transaction_model->get_user_transaction_summary($service_id_list, $status_id_list, $from_date, $to_date)->result_array();
        if (!empty($transaction_summary_array)) {
            $total_transactions = (int) $transaction_summary_array[0]['total_transactions'];
        }
        $transaction_list = $this->transaction_model->get_transaction_list($service_id_list, $status_id_list, $process_id_list,$from_date,$to_date, $offset, $limit)->result_array();
        $transaction_info_list = array();
        if (!empty($transaction_list)) {
            foreach ($transaction_list as $transaction_info) {
                $transaction_info['created_on'] = $this->date_utils->get_unix_to_display($transaction_info['created_on']);
                $transaction_info_list[] = $transaction_info;
            }
        }
        $transaction_information = array();
        $transaction_information['total_transactions'] = $total_transactions;
        $transaction_information['transaction_list'] = $transaction_info_list;
        return $transaction_information;
    }

}
