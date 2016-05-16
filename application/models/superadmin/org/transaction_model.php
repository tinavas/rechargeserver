<?php

class Transaction_model extends Ion_auth_model {

    public function __construct() {
        parent::__construct();
    }

    /*
     * This method will return transaction list
     */

    public function get_all_transactions($user_id, $offset, $limit) {
        $this->curl->create(WEBSERVICE_GET_ALL_TRANSACTION_PATH);
        $this->curl->post(array("user_id" => $user_id, "offset" => $offset, "limit" => $limit));

        return json_decode($this->curl->execute());
    }

    public function get_transction_info($transction_id) {
        $this->curl->create(WEBSERVICE_GET_TRANSACTION_INFO_PATH);
        $this->curl->post(array("transction_id" => $transction_id));
        return json_decode($this->curl->execute());
    }

    public function update_transction_info($transction_info) {
        $this->curl->create(WEBSERVICE_UPDATE_TRANSACTION_INFO_PATH);
        $this->curl->post(array("transction_info" => json_encode($transction_info)));
        return json_decode($this->curl->execute());
    }

    public function delete_transaction($transction_id) {
        $this->curl->create(WEBSERVICE_DELETE_TRANSACTION_INFO_PATH);
        $this->curl->post(array("transction_id" => $transction_id));
        return json_decode($this->curl->execute());
    }

    public function get_sim_list() {
        $this->curl->create(WEBSERVICE_ADD_SIM_INFORMATION);
        return json_decode($this->curl->execute());
    }

    public function add_sim($sim_info) {
        $this->curl->create(WEBSERVICE_ADD_SIM_INFORMATION);
        $this->curl->post(array("sim_info" => json_encode($sim_info)));
        return json_decode($this->curl->execute());
    }

    public function edit_sim($sim_info) {
        $this->curl->create(WEBSERVICE_EDIT_SIM_INFORMATION);
        $this->curl->post(array("sim_info" => json_encode($sim_info)));
        return json_decode($this->curl->execute());
    }

    public function add_service_balance($service_balance_info) {
        $this->curl->create(WEBSERVICE_ADD_SIM_INFORMATION);
        $this->curl->post(array("service_balance_info" => json_encode($service_balance_info)));
        return json_decode($this->curl->execute());
    }

    public function get_sim_service_list($service_balance_info) {
        $this->curl->create(WEBSERVICE_ADD_SIM_INFORMATION);
        $this->curl->post(array("service_balance_info" => json_encode($service_balance_info)));
        return json_decode($this->curl->execute());
    }

    public function get_sim_transactions($start_date = 0, $end_date = 0) {
        $this->curl->create(WEBSERVICE_GET_SIM_TRANSACTION_LIST);
        $this->curl->post(array("start_date" => $start_date,"end_date" => $end_date ));
        return json_decode($this->curl->execute());
    }

}
