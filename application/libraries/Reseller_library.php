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
        if (0 == $user_id) {
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

    public function get_user_dashboard_data($user_id, $group) {
        if (0 == $user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $data = array();
        $this->load->library('payment_library');
        $where_payment = array(
            'user_id' => $user_id
        );
        $pay_id_list = array(
            PAYMENT_TYPE_ID_SEND_CREDIT, PAYMENT_TYPE_ID_RETURN_CREDIT
        );
        $data['payment_list'] = $this->payment_library->where($where_payment)->get_payment_history($pay_id_list, array(), 0, 0, DASHBOARD_PAYMENT_LIMIT, 0, 'desc', $where_payment)->result_array();

        $receive_id_list = array(
            PAYMENT_TYPE_ID_RECEIVE_CREDIT, PAYMENT_TYPE_ID_RETURN_RECEIVE_CREDIT
        );
        if (GROUP_ADMIN == $group) {
            $receive_id_list[] = PAYMENT_TYPE_ID_LOAD_BALANCE;
        }
        $data['receive_list'] = $this->payment_library->where($where_payment)->get_payment_history($receive_id_list, array(), 0, 0, DASHBOARD_PAYMENT_LIMIT, 0, 'desc', $where_payment)->result_array();

        $this->load->library('Date_utils');
        $this->load->model('transaction_model');
        $where_transaction = array(
            'user_id' => $user_id,
            'created_on >= ' => $this->date_utils->server_start_unix_time_of_today(),
            'created_on <= ' => $this->date_utils->server_end_unix_time_of_today(),
        );
        $today_usages = array();
        $service_id_list = array();
        $this->load->model('service_model');
        $service_list = $this->service_model->get_user_assigned_services($user_id)->result_array();
        foreach ($service_list as $service_info) {
            if (!in_array($service_info['service_id'], $service_id_list)) {
                $service_id_list[] = $service_info['service_id'];
            }
            if (SERVICE_TYPE_ID_BKASH_CASHIN == $service_info['service_id']) {
                $today_usages['bkash'] = 0;
            } else if (SERVICE_TYPE_ID_DBBL_CASHIN == $service_info['service_id']) {
                $today_usages['dbbl'] = 0;
            } else if (SERVICE_TYPE_ID_MCASH_CASHIN == $service_info['service_id']) {
                $today_usages['mcash'] = 0;
            } else if (SERVICE_TYPE_ID_UCASH_CASHIN == $service_info['service_id']) {
                $today_usages['ucash'] = 0;
            } else if (SERVICE_TYPE_ID_TOPUP_GP == $service_info['service_id'] || SERVICE_TYPE_ID_TOPUP_ROBI == $service_info['service_id'] || SERVICE_TYPE_ID_TOPUP_BANGLALINK == $service_info['service_id'] || SERVICE_TYPE_ID_TOPUP_AIRTEL == $service_info['service_id'] || SERVICE_TYPE_ID_TOPUP_TELETALK == $service_info['service_id']) {
                $today_usages['topup'] = 0;
            } else if (SERVICE_TYPE_ID_SEND_SMS == $service_info['service_id']) {
                $today_usages['sms'] = 0;
            }
        }
        $transaction_list = $this->transaction_model->where($where_transaction)->get_user_transaction_list($service_id_list, array(TRANSACTION_STATUS_ID_PENDING, TRANSACTION_STATUS_ID_PROCESSED, TRANSACTION_STATUS_ID_SUCCESSFUL))->result_array();
        foreach ($transaction_list as $transaction_info) {
            if (SERVICE_TYPE_ID_BKASH_CASHIN == $transaction_info['service_id']) {
                $today_usages['bkash'] = $today_usages['bkash'] + $transaction_info['amount'];
            } else if (SERVICE_TYPE_ID_DBBL_CASHIN == $transaction_info['service_id']) {
                $today_usages['dbbl'] = $today_usages['dbbl'] + $transaction_info['amount'];
            } else if (SERVICE_TYPE_ID_MCASH_CASHIN == $transaction_info['service_id']) {
                $today_usages['mcash'] = $today_usages['mcash'] + $transaction_info['amount'];
            } else if (SERVICE_TYPE_ID_UCASH_CASHIN == $transaction_info['service_id']) {
                $today_usages['ucash'] = $today_usages['ucash'] + $transaction_info['amount'];
            } else if (SERVICE_TYPE_ID_TOPUP_GP == $transaction_info['service_id'] || SERVICE_TYPE_ID_TOPUP_ROBI == $transaction_info['service_id'] || SERVICE_TYPE_ID_TOPUP_BANGLALINK == $transaction_info['service_id'] || SERVICE_TYPE_ID_TOPUP_AIRTEL == $transaction_info['service_id'] || SERVICE_TYPE_ID_TOPUP_TELETALK == $transaction_info['service_id']) {
                $today_usages['topup'] = $today_usages['topup'] + $transaction_info['amount'];
            }
        }
        if (in_array(SERVICE_TYPE_ID_SEND_SMS, $service_id_list)) {
            $sms_transaction_list = $this->transaction_model->where(array('user_id' => $user_id))->get_user_sms_transaction_list(array(TRANSACTION_STATUS_ID_PENDING, TRANSACTION_STATUS_ID_PROCESSED, TRANSACTION_STATUS_ID_SUCCESSFUL), $this->date_utils->server_start_unix_time_of_today(), $this->date_utils->server_end_unix_time_of_today())->result_array();
            foreach ($sms_transaction_list as $sms_transaction_info) {
                $today_usages['sms'] = $today_usages['sms'] + $sms_transaction_info['unit_price'];
            }
        }
        $data['today_usages'] = $today_usages;
        $user_message = "";
        $user_id_list[] = ADMIN_USER_ID;
        $user_id_list[] = $user_id;
        $user_message_array = $this->reseller_model->get_user_messages($user_id_list)->result_array();
        foreach ($user_message_array as $user_message_info) {
            $user_message = $user_message . "   " . $user_message_info['message'];
        }
        $data['user_message'] = $user_message;

        return $data;
    }

    /*
     * This method will return reseller list of a user
     * @param $user_id, user id
     * @author nazmul hasan on 27th february 2016
     */

    public function get_reseller_list($user_id) {
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
            $user_info['ip_address'] = "";
            $reseller_list[] = $user_info;
        }
        return $reseller_list;
    }

    /*
     * $this method will return successor user id list of a user
     * @param $user_id, user id
     * @param $include_parent include parent
     * @author nazmul hasan on 27th february 2016
     */

    public function get_successor_id_list($user_id = 0, $include_parent = FALSE) {
        $successor_id_list = array();
        if ($include_parent) {
            $successor_id_list[] = $user_id;
        }
        $flag = true;
        $parent_id_list = array($user_id);
        while ($flag) {
            $child_id_list_array = $this->reseller_model->get_child_user_id_list($parent_id_list)->result_array();
            $parent_id_list = array();
            foreach ($child_id_list_array as $child_info) {
                $child_user_id = $child_info['child_user_id'];
                if (!in_array($child_user_id, $successor_id_list)) {
                    $successor_id_list[] = $child_user_id;
                }
                if (!in_array($child_user_id, $parent_id_list)) {
                    $parent_id_list[] = $child_user_id;
                }
            }
            if (empty($parent_id_list)) {
                $flag = false;
            }
        }
        return $successor_id_list;
    }

    /*
     * This method will return parent user id of a user
     * @param $user_id user id
     * @author nazmul hasan on 29th february 2016
     */

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

    /*
     * This method will return predecessor user id list of a user
     * @param $user_id user id
     * @author nazmul hasan on 29th february 2016
     */
    /* public function get_predecessor_id_list($user_id = 0) {
      $user_id_list = array();
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
      } */




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
