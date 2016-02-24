<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 */
class Reseller_library {

    public function __construct() {
        $this->load->model('reseller_model');
    }

    /**
     * __call
     *
     * Acts as a simple way to call model methods without loads of stupid alias'
     *
     * */
    public function __call($method, $arguments) {
        if (!method_exists($this->reseller_model, $method)) {
            throw new Exception('Undefined method Reseller_library::' . $method . '() called');
        }

        return call_user_func_array(array($this->reseller_model, $method), $arguments);
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
     * This method will return current available balance of a user
     * @param $user_id, user id
     * @return current available balance of the user 
     * @author nazmul hasan on 24th february 2016
     */
    public function get_user_current_balance($user_id = 0) {
        $current_balance = 0;
        if (0 == $user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->load->model('payment_model');
        $user_balance_array = $this->payment_model->get_users_current_balance(array($user_id))->result_array();
        if (!empty($user_balance_array)) {
            $current_balance = $user_balance_array[0]['current_balance'];
        }
        return $current_balance;
    }
    
    /*
     * This method will return user title
     * @param $user_id, user id
     * @author nazmul hasan 24th february 2016
     */
    public function get_user_title($user_id = 0) {
        $user_title = "";
        if ( 0 == $user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $user_title_info_array = $this->reseller_model->get_user_title_info($user_id)->result_array();
        if (!empty($user_title_info_array)) {
            $user_title = $user_title_info_array[0]['username'] . ' ' . $user_title_info_array[0]['description'];
        }
        return $user_title;
    }
    
    /*
     * This method will return dashboard data of a user
     * @param $user_id, user id
     * @author nazmul hasan on 24th february 2016
     */
    public function get_user_dashboard_data($user_id) {
        if ( 0 == $user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $data = array();
        $this->load->model('payment_model');
        $where_payment = array(
            'user_id' => $user_id
        );
        $data['payment_list'] = $this->payment_model->where($where_payment)->get_payment_history(array(PAYMENT_TYPE_ID_SEND_CREDIT), DASHBOARD_PAYMENT_LIMIT)->result_array();
        $data['receive_list'] = $this->payment_model->where($where_payment)->get_receive_history(array(PAYMENT_TYPE_ID_RECEIVE_CREDIT), DASHBOARD_RECEIVE_LIMIT)->result_array();

        $this->load->library('Date_utils');
        $this->load->model('transaction_model');
        $where_transaction = array(
            'user_id' => $user_id,
            'created_on >= ' => $this->date_utils->server_start_unix_time_of_today(),
            'created_on <= ' => $this->date_utils->server_end_unix_time_of_today(),
        );
        $bkash_total_transactions = 0;
        $bkash_transaction_list = $this->transaction_model->where($where_transaction)->get_user_transaction_list(array(SERVICE_TYPE_ID_BKASH_CASHIN))->result_array();
        foreach ($bkash_transaction_list as $bkash_transaction_info) {
            if($bkash_transaction_info['status_id'] == TRANSACTION_STATUS_ID_PENDING || $bkash_transaction_info['status_id'] == TRANSACTION_STATUS_ID_SUCCESSFUL)
            {
                $bkash_total_transactions = $bkash_total_transactions + $bkash_transaction_info['amount'];
            }            
        }
        $data['bkash_total_transactions'] = $bkash_total_transactions;
        return $data;
    }

    public function get_reseller_list($user_id, $filter_data = array()) {
        $reseller_list = array();
        $user_id_list = array();
        $user_id_balance_map = array();
        $user_list = $this->reseller_model->get_reseller_list($user_id)->result_array();
        foreach ($user_list as $user_info) {
            if (!in_array($user_info['user_id'], $user_id_list)) {
                $user_id_list[] = $user_info['user_id'];
                $user_id_balance_map[$user_info['user_id']] = 0;
            }
        }
        if (!empty($user_id_list)) {
            $this->load->model('payment_model');
            $users_balance_list = $this->payment_model->get_users_current_balance($user_id_list)->result_array();
            foreach ($users_balance_list as $user_balance_info) {
                $user_id_balance_map[$user_balance_info['user_id']] = $user_balance_info['current_balance'];
            }
        }
        $this->load->library('Date_utils');
        foreach ($user_list as $user_info) {
            $user_info['current_balance'] = $user_id_balance_map[$user_info['user_id']];
            $user_info['last_login'] = $this->date_utils->get_unix_to_display($user_info['last_login']);
            $user_info['created_on'] = $this->date_utils->get_unix_to_display($user_info['created_on']);
            $reseller_list[] = $user_info;
        }
        return $reseller_list;
    }

    

    /*
     * This method will return maximum allowable users to be created under that user
     * @param $user_id, user id
     * @return int, maximum allowable users to be created under this user
     * @author nazmul hasan on 30th January 2016
     */

    public function get_maximum_children($user_id = 0) {
        if ($user_id == 0) {
            $user_id = $this->session->userdata('user_id');
        }
        $reseller_info_array = $this->reseller_model->get_reseller_info($user_id)->result_array();
        if (!empty($reseller_info_array)) {
            $reseller_info = $reseller_info_array[0];
            return $reseller_info['max_user_no'];
        }
        return 0;
    }

    public function get_parent_user_id($user_id = 0) {
        if ($user_id == 0) {
            $user_id = $this->session->userdata('user_id');
        }
        $parent_user_id = 0;
        $parent_info_array = $this->reseller_model->get_parent_user_id($user_id)->result_array();
        if (!empty($parent_info_array)) {
            $parent_info = $parent_info_array[0];
            $parent_user_id = $parent_info['parent_user_id'];
        }
        return $parent_user_id;
    }

    /**
     * this method will return all parents list of a user
     * @param  $user_id 
     * @author  Rashida Sultana 17 jan 2016
     * 
     *  */
    public function get_user_parent_id_list($user_id = 0) {
        $user_id_list = array($user_id);
        $flag = true;
        while ($flag) {
            $parent_info_array = $this->reseller_model->get_parent_user_id($user_id)->result_array();
            foreach ($parent_info_array as $parent_info) {
                $user_id = $parent_info['parent_user_id'];
                if (!in_array($parent_info['parent_user_id'], $user_id_list)) {
                    $user_id_list[] = $parent_info['parent_user_id'];
                }
            }
            if (empty($parent_info_array)) {
                $flag = false;
            }
        }

        return $user_id_list;
    }

    
    public function get_bfs_user_id_list($user_id) {
        $user_id_list = array();
        $flag = true;
        $parent_id_list = array($user_id);
        while ($flag) {
            $child_list_array = $this->reseller_model->get_child_user_id_list($parent_id_list)->result_array();
            $parent_id_list = array();
            foreach ($child_list_array as $child_info) {
                if (!in_array($child_info['child_user_id'], $parent_id_list)) {
                    $parent_id_list[] = $child_info['child_user_id'];
                }
                if (!in_array($child_info['child_user_id'], $user_id_list)) {
                    $user_id_list[] = $child_info['child_user_id'];
                }
            }
            if (empty($child_list_array)) {
                $flag = false;
            }
        }

        return $user_id_list;
    }

}
