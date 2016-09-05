<?php

class Company_info_configuration_model extends Ion_auth_model {

    public function __construct() {
        parent::__construct();
    }

    /*
     * This method will create a new service
     * @param $service_data, service data
     */

    public function add_company_info($additional_data) {
        $data = $this->_filter_data($this->tables['basic_configuration'], $additional_data);
        $this->db->insert($this->tables['basic_configuration'], $additional_data);
        $id = $this->db->insert_id();
        return isset($id) ? $id : False;
    }

    public function get_company_info() {
        return $this->db->select($this->tables['basic_configuration'] . '.*')
                        ->from($this->tables['basic_configuration'])
                        ->get();
    }
    /*
     * This method will update a service info
     * @param $service_id, service id
     * @param $service_data, service data
     */
   

}
