<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 */
class Transaction_library {

    public function __construct() {
        $this->load->model('transaction_model');
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

    /*
     * this method add multipule transcations
     * @param $transaction_list, transction data list
     * @param $service_id_list, service id list
     * @param $total_amount, total required amount for transactions
     * @param $user_id, user_id
     * @author rashida on 9th march 2016
     */

    public function add_multipule_transactions($transaction_list, $service_id_list, $total_amount, $user_id = 0) {
        if ($user_id == 0) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->load->model('payment_model');
        $user_current_balance_array = $this->payment_model->get_users_current_balance(array($user_id))->result_array();
        if (!empty($user_current_balance_array)) {
            $user_current_balance = $user_current_balance_array[0]['current_balance'];
            if ($total_amount > $user_current_balance) {
                $this->set_error('error_insufficient_balance');
                return FALSE;
            }
        } else {
            $this->set_error('error_insufficient_balance');
            return FALSE;
        }
        $users_profit_list = $this->calculate_multipule_transactions_profit_chain($user_id, $service_id_list, $transaction_list);
        return $this->transaction_model->add_transactions($transaction_list, $users_profit_list);
    }

    /*
     * This method will calculate profit chain from user to top parent for multipule transaction
     * @param $user_id user id
     * @param $service_id_list service id list
     * @param $transction_data_list transction list
     * @author rashida on 9th march 2016
     */

    public function calculate_multipule_transactions_profit_chain($user_id, $service_id_list, $transaction_list) {
        $parent_child_id_map = array();
        $user_id_list = array($user_id);
        $flag = TRUE;
        $child_user_id = $user_id;
        while ($flag) {
            $parent_info_array = $this->reseller_model->get_parent_user_id($child_user_id)->result_array();
            foreach ($parent_info_array as $parent_info) {
                $parent_user_id = $parent_info['parent_user_id'];
                $parent_child_id_map[$parent_user_id] = $child_user_id;
                if (!in_array($parent_info['parent_user_id'], $user_id_list)) {
                    $user_id_list[] = $parent_user_id;
                }
                $child_user_id = $parent_user_id;
            }
            if (empty($parent_info_array)) {
                $flag = false;
            }
        }

        $user_id_service_info_map = array();
        $user_service_list = $this->reseller_library->get_users_service_info_list($user_id_list, $service_id_list)->result_array();
        foreach ($user_service_list as $service_info) {
            $user_id_service_info_map[$service_info['user_id']] = $service_info;
        }
        $user_profit_list = array();
        foreach ($user_service_list as $service_info) {
            foreach ($transaction_list as $transction_info) {
                if ($transction_info['service_id'] == $service_info['service_id']) {
                    $amount = $transction_info['amount'];
                    $user_profit_info = array(
                        'user_id' => $service_info['user_id'],
                        'reference_id' => $user_id,
                        'service_id' => $service_info['service_id'],
                        'rate' => $amount,
                        'status_id' => TRANSACTION_STATUS_ID_PENDING,
                        //maping id need for identify this profit for which transction
                        'mapping_id' => $transction_info['mapping_id'],
                        'created_on' => now(),
                        'modified_on' => now()
                    );
                    $commission = 0;
                    if ($service_info['user_id'] == $user_id) {
                        $commission = $amount / $service_info['rate'] * $service_info['commission'];
                    } else {
                        $commission = $amount / $service_info['rate'] * ($service_info['commission'] - $user_id_service_info_map[$parent_child_id_map[$service_info['user_id']]]['commission']);
                    }
                    $user_profit_info['amount'] = $commission;
                    $user_profit_list[] = $user_profit_info;
                }
            }
        }
        return $user_profit_list;
    }

    /*
     * this method add transcation
     * @param $api_key, API key
     * @param $transaction_data, transaction data
     * @author nazmul hasan on 24th February 2016
     */

    public function add_transaction($api_key, $transaction_data) {
        $amount = $transaction_data['amount'];
        $service_id = $transaction_data['service_id'];
        $user_id = $transaction_data['user_id'];
        $users_profit_list = $this->calculate_transaction_profit_chain($user_id, $service_id, $amount);
        return $this->transaction_model->add_transaction($api_key, $transaction_data, $users_profit_list);
    }

    /*
     * This method will calculate profit chain from user to top parent
     * @param $user_id user id
     * @param $service_id service id
     * @param $amount amount
     * @author nazmul hasan on 29th february 2016
     */

    public function calculate_transaction_profit_chain($user_id, $service_id, $amount) {
        $parent_child_id_map = array();
        $user_id_list = array($user_id);
        $flag = TRUE;
        $child_user_id = $user_id;
        while ($flag) {
            $parent_info_array = $this->reseller_model->get_parent_user_id($child_user_id)->result_array();
            foreach ($parent_info_array as $parent_info) {
                $parent_user_id = $parent_info['parent_user_id'];
                $parent_child_id_map[$parent_user_id] = $child_user_id;
                if (!in_array($parent_info['parent_user_id'], $user_id_list)) {
                    $user_id_list[] = $parent_user_id;
                }
                $child_user_id = $parent_user_id;
            }
            if (empty($parent_info_array)) {
                $flag = false;
            }
        }
        $user_id_service_info_map = array();
        $user_service_list = $this->reseller_library->get_users_service_info($user_id_list, $service_id)->result_array();
        foreach ($user_service_list as $service_info) {
            $user_id_service_info_map[$service_info['user_id']] = $service_info;
        }
        $user_profit_list = array();
        foreach ($user_service_list as $service_info) {
            $user_profit_info = array(
                'user_id' => $service_info['user_id'],
                'reference_id' => $user_id,
                'service_id' => $service_id,
                'rate' => $amount,
                'status_id' => TRANSACTION_STATUS_ID_PENDING,
                'created_on' => now(),
                'modified_on' => now()
            );
            $commission = 0;
            //if(!array_key_exists($service_info['user_id'], $child_parent_id_map) && $service_info['user_id'] == $user_id)
            if ($service_info['user_id'] == $user_id) {
                $commission = $amount / $service_info['rate'] * $service_info['commission'];
            } else {
                $commission = $amount / $service_info['rate'] * ($service_info['commission'] - $user_id_service_info_map[$parent_child_id_map[$service_info['user_id']]]['commission']);
            }
            $user_profit_info['amount'] = $commission;
            $user_profit_list[] = $user_profit_info;
        }
        return $user_profit_list;
    }

    /*
     * this method return user transaction list
     * @param $service_id_list, service id list of transactions
     * @param $from_date, start date in unix format
     * @param $to_date, end date in unix format
     * @param $limit, limit
     * @param $offset, offset
     * @param @where, where clause
     * @author nazmul hasan on 24th february 2016
     */

    public function get_user_transaction_list($service_id_list = array(), $status_id_list = array(), $from_date = 0, $to_date = 0, $limit = 0, $offset = 0, $where = array()) {
        if (!empty($where)) {
            $this->transaction_model->where($where);
        }
        $transaction_list = $this->transaction_model->get_user_transaction_list($service_id_list, $status_id_list, $from_date, $to_date, $limit, $offset)->result_array();
        $this->load->library('date_utils');
        $transation_info_list = array();
        if (!empty($transaction_list)) {
            foreach ($transaction_list as $transaction_info) {
                $transaction_info['created_on'] = $this->date_utils->get_unix_to_display($transaction_info['created_on']);
                $transation_info_list[] = $transaction_info;
            }
        }
        return $transation_info_list;
    }

    public function add_user_transaction($transaction_data) {
        //adding user transaction
        return $this->transaction_model->add_transaction($transaction_data);
    }

    /* this method calculate users profit
     * @param $user_id_list
     * @param $service_id
     * @param $service_used_amount
     * @param $user_id
     * return users profit list
     *  */

    /* public function calculate_user_profit($user_id_list = array(), $service_id, $service_used_amount, $user_id) {
      $user_profit_list = array();
      $user_service_list = $this->reseller_library->get_users_service_info($user_id_list, $service_id)->result_array();
      if (!empty($user_service_list)) {
      $user_service_size = count($user_service_list);
      $rate_ratio = $service_used_amount / $user_service_list[$user_service_size - 1]['rate'];
      foreach ($user_service_list as $key => $user_service_info) {
      $user_profit_info = array();
      $user_profit_info['user_id'] = $user_service_info['user_id'];
      $user_profit_info['reference_id'] = $user_id;
      $user_profit_info['rate'] = $service_used_amount;
      $user_profit_info['service_id'] = $service_id;
      $user_profit_info['status_id'] = TRANSACTION_STATUS_ID_PENDING;
      if ($key < $user_service_size - 1) {
      $user_profit_info['amount'] = ($user_service_list[$key]['commission'] - $user_service_list[$key + 1]['commission']) * $rate_ratio;
      } else {
      $user_profit_info['amount'] = $user_service_list[$key]['commission'] * $rate_ratio;
      }
      $user_profit_list[] = $user_profit_info;
      }
      }
      return $user_profit_list;
      } */

    /* this method return all transaction list
     * @param $offset
     * @param $from_date
     * @param $to_date
     * return users transaction list
     *  */

    public function get_transaction_list($offset = 0, $from_date = 0, $to_date = 0) {
        $limit = INITIAL_LIMIT;
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_info_list = array();
        $transaction_list = $this->transaction_model->where($where)->get_user_transaction_list(array(), $limit, $offset, $from_date, $to_date)->result_array();
        if (!empty($transaction_list)) {
            foreach ($transaction_list as $transaction_info) {
                $transaction_info['created_on'] = $this->date_utils->get_unix_to_display($transaction_info['created_on']);
                $transaction_info_list[] = $transaction_info;
            }
        }
        return $transaction_info_list;
    }

    /* this method return user payment or receive list
     * @param $user_id
     * @param $payment_type_ids
     * return users payment list
     *  */

    public function get_payment_list($user_id = 0, $payment_type_ids = array(), $limit = 0, $offset = 0, $start_date = 0, $end_date = 0) {
        $this->load->model('transaction_model');
        $this->load->library('date_utils');
        $payment_info_list = array();
        $payment_list = $this->transaction_model->get_payment_history($user_id, $payment_type_ids, $limit, $offset, $start_date, $end_date)->result_array();
        if (!empty($payment_list)) {
            foreach ($payment_list as $payment_info) {
                $payment_info['created_on'] = $this->date_utils->get_unix_to_display($payment_info['created_on']);
                $payment_info_list[] = $payment_info;
            }
        }
        return $payment_info_list;
    }

}
