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

    /*
     * This method will update transaction info, user_payments and user_profits status
     * @param $transaction_id, transaction  id list
     * @param $transction_info, transaction info
     * @author rashida on 4th September 2016
     */

    public function update_transction_info($transaction_id, $transction_info) {
        $this->db->trans_begin();
        $update_data = array(
            'status_id' => $transction_info['status_id']
        );
        $this->db->where('transaction_id', $transaction_id);
        $this->db->update('user_transactions', $transction_info);

        $this->db->where('transaction_id', $transaction_id);
        $this->db->update('user_payments', $update_data);

        $this->db->where('transaction_id', $transaction_id);
        $this->db->update('user_profits', $update_data);

        $this->db->trans_commit();
        $this->set_message('transaction_successful');
        return TRUE;
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
        $this->curl->post(array("start_date" => $start_date, "end_date" => $end_date));
        return json_decode($this->curl->execute());
    }

    /*
     * This method will return  transaction summary
     * @param $service_id_list, service id list
     * @param $status_id_list, service status id list
     * @param $process_id_list, service process type id list
     * @param $from_date, start date in unix format
     * @param $to_date, end date in unix format
     * @author rashida on 4th September 2016
     */

    function get_user_transaction_summary($service_id_list = array(), $status_id_list = array(), $process_id_list = array(), $from_date = 0, $to_date = 0) {

        if ($from_date != 0 && $to_date != 0) {
            $this->db->where($this->tables['user_transactions'] . '.created_on >=', $from_date);
            $this->db->where($this->tables['user_transactions'] . '.created_on <=', $to_date);
        }
        if (!empty($service_id_list)) {
            $this->db->where_in($this->tables['user_transactions'] . '.service_id', $service_id_list);
        }
        if (!empty($status_id_list)) {
            $this->db->where_in($this->tables['user_transactions'] . '.status_id', $status_id_list);
        }
        if (!empty($process_id_list)) {
            $this->db->where_in($this->tables['user_transactions'] . '.process_type_id', $process_id_list);
        }
        return $this->db->select('COUNT(*) as total_transactions')
                        ->from($this->tables['user_transactions'])
                        ->get();
    }

    /*
     * This method will return  trnsaction list
     * @param $service_id_list, service id list
     * @param $status_id_list, service status id list
     * @param $process_id_list, service process type id list
     * @param $from_date, start date in unix format
     * @param $to_date, end date in unix format
     * @author rashida on 4th September 2016
     */

    public function get_transaction_list($service_id_list = array(), $status_id_list = array(), $process_id_list = array(), $from_date = 0, $to_date = 0, $offset = 0, $limit = 0) {

        if ($limit > 0) {
            $this->db->limit($limit);
        }
        if ($offset > 0) {
            $this->db->offset($offset);
        }
        if ($from_date != 0 && $to_date != 0) {
            $this->db->where($this->tables['user_transactions'] . '.created_on >=', $from_date);
            $this->db->where($this->tables['user_transactions'] . '.created_on <=', $to_date);
        }
        if (!empty($service_id_list)) {
            $this->db->where_in($this->tables['user_transactions'] . '.service_id', $service_id_list);
        }
        if (!empty($status_id_list)) {
            $this->db->where_in($this->tables['user_transactions'] . '.status_id', $status_id_list);
        }
        if (!empty($process_id_list)) {
            $this->db->where_in($this->tables['user_transactions'] . '.process_type_id', $process_id_list);
        }
        $this->db->order_by($this->tables['user_transactions'] . '.id', 'desc');
        return $this->db->select($this->tables['user_transactions'] . '.*,' . $this->tables['user_transaction_statuses'] . '.title as status,' . $this->tables['services'] . '.title as service_title,' . $this->tables['service_types'] . '.title as process_type')
                        ->from($this->tables['user_transactions'])
                        ->join($this->tables['user_transaction_statuses'], $this->tables['user_transaction_statuses'] . '.id=' . $this->tables['user_transactions'] . '.status_id')
                        ->join($this->tables['services'], $this->tables['services'] . '.id=' . $this->tables['user_transactions'] . '.service_id')
                        ->join($this->tables['service_types'], $this->tables['service_types'] . '.id=' . $this->tables['user_transactions'] . '.process_type_id')
                        ->get();
    }

    /*
     * This method will return  trnsaction info
     * @param $transaction_id, transaction id list
     * @author rashida on 4th September 2016
     */

    public function get_transaction_info($transaction_id) {
        $this->db->where($this->tables['user_transactions'] . '.transaction_id', $transaction_id);
        return $this->db->select($this->tables['user_transactions'] . '.*')
                        ->from($this->tables['user_transactions'])
                        ->get();
    }

    /*
     * This method will return  trnsaction status list
     * @author rashida on 4th September 2016
     */

    public function get_transaction_status_list() {
        return $this->db->select($this->tables['user_transaction_statuses'] . '.*')
                        ->from($this->tables['user_transaction_statuses'])
                        ->get();
    }

    /**
     * this method will return transaction staus types
     * return transaction statuses 
     * @author Rashida on 22 Sep 2016
     */
    public function get_user_transaction_statuses() {
        return $this->db->select($this->tables['user_transaction_statuses'] . '.*')
                        ->from($this->tables['user_transaction_statuses'])
                        ->get();
    }

}
