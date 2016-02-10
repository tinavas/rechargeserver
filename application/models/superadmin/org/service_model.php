<?php

class Service_model extends Ion_auth_model {

    public function __construct() {
        parent::__construct();
    }

    /*
     * This method will return service info
     * @param $service_id, service id
     */

    public function get_service_info($service_id) {
        $this->curl->create(WEBSERVICE_SERVICE_PATH . "getserviceinfo");
        $this->curl->post(array("service_id" => $service_id));
        return json_decode($this->curl->execute());

//        $service_info = array(
//            'service_id' => 1,
//            'title' => 'Bkash Send Money'
//        );
//        return $service_info;
    }

    /*
     * This method will return service list
     */

    public function get_all_services() {
        $this->curl->create(WEBSERVICE_SERVICE_PATH . "getallservices");
        return json_decode($this->curl->execute());
    }

    /*
     * This method will create a new service
     * @param $service_data, service data
     */

    public function create_service($title) {
        $this->curl->create(WEBSERVICE_SERVICE_PATH . "createservice");
        $this->curl->post(array("title" => $title));
        return json_decode($this->curl->execute());
    }

    /*
     * This method will update a service info
     * @param $service_id, service id
     * @param $service_data, service data
     */

    public function update_service($service_id, $title) {
        $this->curl->create(WEBSERVICE_SERVICE_PATH . "updateserviceinfo");
        $this->curl->post(array("service_id" => $service_id, "title" => $title));
        return json_decode($this->curl->execute());

//        return true;
    }

    public function delete_service() {
        
    }

}
