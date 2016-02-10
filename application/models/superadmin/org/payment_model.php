<?php

class Payment_model extends Ion_auth_model {

    public function __construct() {
        parent::__construct();
    }

    /*
     * This method will return payment list
     */

    public function get_all_payments() {
        $this->curl->create(WEBSERVICE_GET_SUBSCRIBER_PAYMENT_LIST);
        return json_decode($this->curl->execute());
    }

    /*
     * This method will create a new payment
     * @param $payment_data, payment data
     */

    public function add_payment($payment_data) {
        
    }

}
