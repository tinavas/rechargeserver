<?php

class Payment_model extends Ion_auth_model {

    public function __construct() {
        parent::__construct();
    }

    /*
     * This method will return current balance of users
     * @param $user_id_list, user id list
     * @author nazmul hasan on 24th february 2016
     */
    public function get_users_current_balance($user_id_list = array()) {
        $this->db->where_in('status_id', array(TRANSACTION_STATUS_ID_PENDING, TRANSACTION_STATUS_ID_SUCCESSFUL));
        $this->db->where_in('user_id', $user_id_list);
        $this->db->group_by('user_id');
        return $this->db->select('user_id, sum(balance_in) - sum(balance_out) as current_balance')
                        ->from($this->tables['user_payments'])
                        ->get();
    }
    
    /*
     * This method will return payment history
     * @param $type_id_list, payment types
     * @param $status_id_list, status id list
     * @param $start_time start time in unix
     * @param $end_time end time in unix
     * @param $limit limit
     * @param $offset offset
     * @param $order order
     * @author nazmul hasan on 24th february 2016
     */
    public function get_payment_history($type_id_list = array(), $status_id_list = array(), $start_time = 0, $end_time = 0, $limit = 0, $offset = 0, $order = 'desc') {
        //run each where that was passed
        if (isset($this->_ion_where) && !empty($this->_ion_where)) {
            foreach ($this->_ion_where as $where) {
                $this->db->where($where);
            }
            $this->_ion_where = array();
        }
        if ($limit > 0) {
            $this->db->limit($limit);
        }
        if ($offset > 0) {
            $this->db->offset($offset);
        }
        if ($start_time != 0 && $end_time != 0) {
            $this->db->where($this->tables['user_payments'] . '.created_on >=', $start_time);
            $this->db->where($this->tables['user_payments'] . '.created_on <=', $end_time);
        }
        if (!empty($type_id_list)) {
            $this->db->where_in($this->tables['user_payments'] . '.type_id', $type_id_list);
        }
        if (!empty($status_id_list)) {
            $this->db->where_in($this->tables['user_payments'] . '.status_id', $status_id_list);
        }
        $this->db->order_by($this->tables['user_payments'] . '.id', $order);
        return $this->db->select($this->tables['user_payments'] . '.*,' . $this->tables['user_payment_types'] . '.title,' . $this->tables['users'] . '.id as user_id,' . $this->tables['users'] . '.first_name,' . $this->tables['users'] . '.last_name')
                        ->from($this->tables['user_payments'])
                        ->join($this->tables['user_payment_types'], $this->tables['user_payment_types'] . '.id=' . $this->tables['user_payments'] . '.type_id')
                        ->join($this->tables['users'], $this->tables['users'] . '.id=' . $this->tables['user_payments'] . '.reference_id', 'left')
                        ->get();
    }
    
        /*
     * This method will return payment history summery
     * @param $type_id_list, payment types
     * @param $status_id_list, status id list
     * @param $start_time start time in unix
     * @param $end_time end time in unix
     * @author rashida on 26th April 2016
     */
    
    function get_payment_history_summary($type_id_list = array(), $status_id_list = array(), $start_time = 0, $end_time = 0){
        //run each where that was passed
         if (isset($this->_ion_where) && !empty($this->_ion_where)) {
            foreach ($this->_ion_where as $where) {
                $this->db->where($where);
            }
            $this->_ion_where = array();
        }
        if ($start_time != 0 && $end_time != 0) {
            $this->db->where($this->tables['user_payments'] . '.created_on >=', $start_time);
            $this->db->where($this->tables['user_payments'] . '.created_on <=', $end_time);
        }
        if (!empty($type_id_list)) {
            $this->db->where_in($this->tables['user_payments'] . '.type_id', $type_id_list);
        }
        if (!empty($status_id_list)) {
            $this->db->where_in($this->tables['user_payments'] . '.status_id', $status_id_list);
        }
        return $this->db->select('COUNT(*) as total_transactions, sum(balance_out) as total_amount_out, sum(balance_in) as total_amount_in')
                        ->from($this->tables['user_payments'])
                        ->get();
   }
    
    public function get_receive_history($type_id_list = array(), $limit = 0) {
        //run each where that was passed
        if (isset($this->_ion_where) && !empty($this->_ion_where)) {
            foreach ($this->_ion_where as $where) {
                $this->db->where($where);
            }

            $this->_ion_where = array();
        }
        if ($limit > 0) {
            $this->db->limit($limit);
        }
        if (!empty($type_id_list)) {
            $this->db->where_in($this->tables['user_payments'] . '.type_id', $type_id_list);
        }
        $this->db->order_by($this->tables['user_payments'] . '.id', 'desc');
        return $this->db->select($this->tables['user_payments'] . '.*,' . $this->tables['user_payment_types'] . '.title')
                        ->from($this->tables['user_payments'])
                        ->join($this->tables['user_payment_types'], $this->tables['user_payment_types'] . '.id=' . $this->tables['user_payments'] . '.type_id')
                        ->get();
    }
    /*
     * This method will transfer payment from one user to another user
     * @param $sender_data, sender payment information
     * @param $receiver_data, receiver payment information
     * @author nazmul hasan on 24th february 2016
     */
    public function transfer_user_payment($sender_data, $receiver_data) {
        $this->db->trans_begin();
        $current_time = now();
        $sender_data['created_on'] = $current_time;
        $sender_data['modified_on'] = $current_time;
        $sender_data['status_id'] = TRANSACTION_STATUS_ID_SUCCESSFUL;

        $receiver_data['created_on'] = $current_time;
        $receiver_data['modified_on'] = $current_time;
        $receiver_data['status_id'] = TRANSACTION_STATUS_ID_SUCCESSFUL;

        $s_payment_data = $this->_filter_data($this->tables['user_payments'], $sender_data);
        $this->db->insert($this->tables['user_payments'], $s_payment_data);
        $s_id = $this->db->insert_id();
        if (isset($s_id)) {
            $r_payment_data = $this->_filter_data($this->tables['user_payments'], $receiver_data);
            $this->db->insert($this->tables['user_payments'], $r_payment_data);
            $r_id = $this->db->insert_id();
            if (isset($r_id)) {
                $this->db->trans_commit();
                return TRUE;
            }
        }
        $this->db->trans_rollback();
        return FALSE;
    }
    
    
    
    public function get_user_current_balance($user_id) {
        $this->db->where_in($this->tables['user_payments'] . '.status_id', array(TRANSACTION_STATUS_ID_PENDING, TRANSACTION_STATUS_ID_SUCCESSFUL));
        $this->db->where($this->tables['user_payments'] . '.user_id', $user_id);
        return $this->db->select('user_id, sum(balance_in) - sum(balance_out) as current_balance')
                        ->from($this->tables['user_payments'])
                        ->get();
    }
}
