<?php

class Company_model extends Ion_auth_model {

    public function __construct() {
        parent::__construct();
    }

    /*
     * This method will return basic configuration of the site.
     * @author nazmul hasan on 16th september 2016
     */
    public function get_basic_configuration_info() {
        return $this->db->select($this->tables['basic_configuration'] . '.*')
            ->from($this->tables['basic_configuration'])
            ->get();
    }
}
