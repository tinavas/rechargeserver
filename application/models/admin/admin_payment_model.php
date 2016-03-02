<?php

class Admin_payment_model extends Ion_auth_model {

    public function __construct() {
        parent::__construct();
    }

    /*
     * This method will raise balance
     * @param $payment_data payment data
     * @author nazmul hasan on 2nd March 2016
     */
    public function load_balance($payment_data) {
        $current_time = now();
        $payment_data['created_on'] = $current_time;
        $payment_data['modified_on'] = $current_time;
        
        $data = $this->_filter_data($this->tables['user_payments'], $payment_data);
        $this->db->insert($this->tables['user_payments'], $data);
        $id = $this->db->insert_id();
        
        return (isset($id)) ? $id : FALSE;
    }    
}
