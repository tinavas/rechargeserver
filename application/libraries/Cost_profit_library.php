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
        $profit_information = array();
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
        $payment_summary_array = $this->cost_profit_model->get_profit_history_summary($status_id_list, $service_id_list, $start_time, $end_time)->result_array();
        if (!empty($payment_summary_array)) {
            $total_transactions = (int) $payment_summary_array[0]['total_transactions'];
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

    public function get_report_detail_history($status_id_list = array(), $service_id_list = array(), $start_date = 0, $end_date = 0, $limit = 0, $offset = 0) {
        $report_infomation = array();
        $start_time = 0;
        $end_time = 0;
        if ($start_date != 0 && $end_date != 0) {
            $this->load->library('Date_utils');
            $start_time = $this->date_utils->server_start_unix_time_of_date($start_date);
            $end_time = $this->date_utils->server_end_unix_time_of_date($end_date);
        }

        if (!empty($where)) {
            $this->cost_profit_model->where($where);
        }
        $report_information = array();
        $report_list = array();
        $report_summary = array();
        $report_summary['total_request'] = 0;
        $report_summary['total_pending'] = 0;
        $report_summary['total_success'] = 0;
        $report_summary['total_processed'] = 0;
        $report_summary['total_failed'] = 0;
        $report_list_array = $this->cost_profit_model->get_report_detail_history($status_id_list, $service_id_list, $start_time, $end_date, $limit, $offset)->result_array();
        $temp_service_id_list = array();
        foreach ($report_list_array as $user_report_info) {
            $report_info = array();
            $report_info['title'] = $user_report_info['title'];
            $report_info['status_id'] = $user_report_info['status_id'];
            $report_info['total_status_request'] = $user_report_info['total_status_request'];
            $report_info['success'] = 0;
            $report_info['pending'] = 0;
            $report_info['processed'] = 0;
            $report_info['failed'] = 0;
            if ($user_report_info['status_id'] == TRANSACTION_STATUS_ID_SUCCESSFUL) {
                $report_info['success'] = $user_report_info['total_status_request'];
            } else if ($user_report_info['status_id'] == TRANSACTION_STATUS_ID_PENDING) {
                $report_info['pending'] = $user_report_info['total_status_request'];
            } else if ($user_report_info['status_id'] == TRANSACTION_STATUS_ID_PROCESSED) {
                $report_info['processed'] = $user_report_info['total_status_request'];
            } else if ($user_report_info['status_id'] == TRANSACTION_STATUS_ID_FAILED) {
                $report_info['failed'] = $user_report_info['total_status_request'];
            }
            if (in_array($user_report_info['service_id'], $temp_service_id_list)) {
                $old_report_info = $report_list[$user_report_info['service_id']];
                $report_info['total_request'] = $old_report_info['total_request'] + $user_report_info['total_request'];
                if ($report_info['success'] == 0) {
                    $report_info['success'] = $old_report_info['success'];
                }
                if ($report_info['pending'] == 0) {
                    $report_info['pending'] = $old_report_info['pending'];
                }
                if ($report_info['processed'] == 0) {
                    $report_info['processed'] = $old_report_info['processed'];
                }
                if ($report_info['failed'] == 0) {
                    $report_info['failed'] = $old_report_info['failed'];
                }
            } else {
                $report_info['total_request'] = $user_report_info['total_request'];
                $temp_service_id_list[] = $user_report_info['service_id'];
            }

            if ($report_info['success'] != 0 && $report_info['total_request'] != 0) {
                $report_info['per_of_success'] = ($report_info['success'] * 100) / $report_info['total_request'] . "%";
            } else {
                $report_info['per_of_success'] = 0 . "%";
            }
            $report_list[$user_report_info['service_id']] = $report_info;
            $report_summary['total_request'] = $report_summary['total_request'] + $report_info['total_request'];
            $report_summary['total_pending'] = $report_summary['total_pending'] + $report_info['pending'];
            $report_summary['total_success'] = $report_summary['total_success'] + $report_info['success'];
            $report_summary['total_processed'] = $report_summary['total_processed'] + $report_info['processed'];
            $report_summary['total_failed'] = $report_summary['total_failed'] + $report_info['failed'];
        }
        $report_information['report_list'] = $report_list;
        $report_information['report_summary'] = $report_summary;
        return $report_information;
    }

    public function get_user_profit_loss($status_id_list = array(), $service_id_list = array(), $start_date, $end_date, $limit, $offset) {
        $profit_loss_infomation = array();
        $start_time = 0;
        $end_time = 0;
        if ($start_date != 0 && $end_date != 0) {
            $this->load->library('Date_utils');
            $start_time = $this->date_utils->server_start_unix_time_of_date($start_date);
            $end_time = $this->date_utils->server_end_unix_time_of_date($end_date);
        }

        if (!empty($where)) {
            $this->cost_profit_model->where($where);
        }
        $report_status_list = array();
        $success_status_report_array = $this->cost_profit_model->get_user_status_report(array(TRANSACTION_STATUS_ID_SUCCESSFUL), $service_id_list, $start_time, $end_time, $limit, $offset)->result_array();
        foreach ($success_status_report_array as $report_status_info) {
            $report_status_list[$report_status_info['service_id']] = $report_status_info['total_status_request'];
        }
        $profit_loss_array = $this->cost_profit_model->get_user_profit_loss($status_id_list, $service_id_list, $start_time, $end_time, $limit, $offset)->result_array();
        $profit_loss_list = array();
        $profit_loss_summary = array();
        $profit_loss_summary['total_request'] = 0;
        $profit_loss_summary['total_status_request'] = 0;
        $profit_loss_summary['total_amount'] = 0;
        $profit_loss_summary['total_used_amount'] = 0;
        $profit_loss_summary['total_profit'] = 0;
      
        foreach ($profit_loss_array as $profit_loss_info) {
            $profit_loss_info['total_status_request'] = 0;
            if (!empty($report_status_list)) {
                $profit_loss_info['total_status_request'] = $report_status_list[$profit_loss_info['service_id']];
            }
            $profit_loss_info['total_amount'] = $profit_loss_info['total_used_amount'] + $profit_loss_info['total_profit'];
            $profit_loss_list[] = $profit_loss_info;
            $profit_loss_summary['total_request'] = $profit_loss_summary['total_request'] + $profit_loss_info['total_request'];
            $profit_loss_summary['total_status_request'] = $profit_loss_summary['total_status_request'] + $profit_loss_info['total_status_request'];
            $profit_loss_summary['total_amount'] = $profit_loss_summary['total_amount'] + $profit_loss_info['total_amount'];
            $profit_loss_summary['total_used_amount'] = $profit_loss_summary['total_used_amount'] + $profit_loss_info['total_used_amount'];
            $profit_loss_summary['total_profit'] = $profit_loss_summary['total_profit'] + $profit_loss_info['total_profit'];
        }
        $profit_loss_infomation['report_list'] = $profit_loss_list;
        $profit_loss_infomation['report_summary'] = $profit_loss_summary;
        return $profit_loss_infomation;
    }

}
