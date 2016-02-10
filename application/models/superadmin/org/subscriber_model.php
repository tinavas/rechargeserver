<?php

class Subscriber_model extends Ion_auth_model {

    public function __construct() {
        parent::__construct();
    }

    /*
     * This method will return subscriber list
     */

    public function get_all_subscribers() {
        $this->curl->create(WEBSERVICE_GET_ALL_SUBSCRIBERS_PATH);
        return json_decode($this->curl->execute());
    }

    /*
     * This method will create a new subscriber
     * @param $subscriber_data, subscriber data
     */

    public function create_subscriber($subscriber_info) {
        $this->curl->create(WEBSERVICE_CREATE_SUBSCRIBER_PATH);
        $this->curl->post(array("subscriber_info" => json_encode($subscriber_info)));
        return json_decode($this->curl->execute());
    }

    /*
     * This method will return subscriber info
     */

    public function get_subscriber_info($subscriber_id) {
        $this->curl->create(WEBSERVICE_GET_SUBSCRIBER_INFO_PATH);
        $this->curl->post(array("subscriber_id" => $subscriber_id));
        return json_decode($this->curl->execute());
    }

    /*
     * This method will return subscriber info
     */

    public function update_subscriber($subscriber_info) {
        $this->curl->create(WEBSERVICE_UPDATE_SUBSCRIBER_PATH);
        $this->curl->post(array("subscriber_info" => json_encode($subscriber_info)));
        return json_decode($this->curl->execute());
    }

}
