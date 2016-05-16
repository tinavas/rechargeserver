<?php

class History_model extends Ion_auth_model {

    public function __construct() {
        parent::__construct();
    }

    /*
     * This method will return transaction list
     */

    /*
     * this method will return service rank list, top customers list, profit rank list of services
     * @param $start_unix_time todays unix time
     * @param $end_unix_time previous days unix time
     * @author rashida on 10-5-2016  
     */

    public function get_deshbord_info($start_unix_time, $end_unix_time) {
        $this->curl->create(WEBSERVICE_GET_DESHBOARD_INFO_LIST);
        $this->curl->post(array("start_time" => $start_unix_time, "end_time" => $end_unix_time));
        return json_decode($this->curl->execute());
    }

    public function get_service_volume_rank_list($start_date, $end_date) {
        $this->curl->create(WEBSERVICE_GET_SERVICE_RANK_LIST_VOLUMN);
        $this->curl->post(array("start_date" => $start_date, "end_date" => $end_date));
        return json_decode($this->curl->execute());
    }

    public function get_service_profit_rank_list($start_date, $end_date) {
        $this->curl->create(WEBSERVICE_GET_SERVICE_RPOFIT_LIST);
        $this->curl->post(array("start_date" => $start_date, "end_date" => $end_date));
        return json_decode($this->curl->execute());
    }

    public function get_top_customer_list($start_date, $end_date, $service_id) {
        $this->curl->create(WEBSERVICE_GET_TOP_CUSTOMER_LIST);
        $this->curl->post(array("start_date" => $start_date, "end_date" => $end_date, "service_id" => $service_id));
        return json_decode($this->curl->execute());
    }



}
