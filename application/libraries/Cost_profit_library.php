<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 */
class Cost_profit_library {

    public function __construct() {
        $this->load->model('cost_profit_model');
    }

    /**
     * __call
     *
     * Acts as a simple way to call model methods without loads of stupid alias'
     *
     * */
    public function __call($method, $arguments) {
        if (!method_exists($this->cost_profit_model, $method)) {
            throw new Exception('Undefined method Cost_profit_library::' . $method . '() called');
        }

        return call_user_func_array(array($this->cost_profit_model, $method), $arguments);
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

    /*
     * This method will return profit history
     * @author nazmul hasan on 3rd March 2016
     */

    public function get_profit_history($status_id_list = array(), $service_id_list = array(), $start_date = 0, $end_date = 0, $limit = 0, $offset = 0, $where = array()) {
       $profit_information =array();
        $this->load->library('Date_utils');
        $start_time = 0;
        $end_time = 0;
        if ($start_date != 0 && $end_date != 0) {
            $start_time = $this->date_utils->server_start_unix_time_of_date($start_date);
            $end_time = $this->date_utils->server_end_unix_time_of_date($end_date);
        }
        $total_transactions = 0;
        $total_amount_in = 0;
        if (!empty($where)) {
            $this->cost_profit_model->where($where);
        }
        $payment_summery_array = $this->cost_profit_model->get_profit_history_summary($status_id_list, $service_id_list, $start_time, $end_time)->result_array();
        if (!empty($payment_summery_array)) {
            $total_transactions = (int) $payment_summery_array[0]['total_transactions'];
        }
        $profit_list = array();
        if (!empty($where)) {
            $this->cost_profit_model->where($where);
        }
        $profit_list_array = $this->cost_profit_model->get_profit_history($status_id_list, $service_id_list, $start_time, $end_time, $limit, $offset)->result_array();
        foreach ($profit_list_array as $profit_info) {
            $profit_info['created_on'] = $this->date_utils->get_unix_to_display($profit_info['created_on']);
            $profit_list[] = $profit_info;
        }
        $profit_information['total_transactions'] = $total_transactions;
        $profit_information['profit_list'] = $profit_list;
        return $profit_information;
    }

}
