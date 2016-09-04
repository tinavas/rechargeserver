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
        $this->db->where($this->tables['services'] . '.id', $service_id);
        return $this->db->select($this->tables['services'] . '.id as service_id,' . $this->tables['services'] . '.*')
                        ->from($this->tables['services'])
                        ->get();
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
     * @param $service_info, service data
     */

    public function update_service($service_id, $service_info) {
        $this->db->where('id', $service_id);
       return $this->db->update($this->tables['services'], $service_info);
    }

    public function delete_service() {
        
    }

    public function get_all_service_list() {
        return $this->db->select($this->tables['services'] . '.id as service_id,' . $this->tables['services'] . '.*,' . $this->tables['service_types'] . '.title as process_type')
                        ->from($this->tables['services'])
                        ->join($this->tables['service_types'], $this->tables['service_types'] . '.id=' . $this->tables['services'] . '.type_id')
                        ->get();
    }

    public function get_service_type_list() {
        return $this->db->select($this->tables['service_types'] . '.*')
                        ->from($this->tables['service_types'])
                        ->get();
    }

}
