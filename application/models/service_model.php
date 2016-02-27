<?php

class Service_model extends Ion_auth_model {

    public function __construct() {
        parent::__construct();
    }
    
    /*
     * this method will return assigned services of a user
     * @param  $user_id  user id
     * @author nazmul hasan on 27th february 2016
     */
    public function get_user_assigned_services($user_id) {
        $this->db->where($this->tables['users_services'] . '.user_id', $user_id);
        $this->db->where($this->tables['users_services'] . '.status', 1);
        return $this->db->select($this->tables['services'] . '.id as service_id,' . $this->tables['services'] . '.*,' . $this->tables['users_services'] . '.*,' . $this->tables['users_services'] . '.id as user_service_id')
                        ->from($this->tables['users_services'])
                        ->join($this->tables['services'], $this->tables['services'] . '.id=' . $this->tables['users_services'] . '.service_id')
                        ->get();
    }

    public function get_all_services() {
        return $this->db->select($this->tables['services'] . '.id as service_id,' . $this->tables['services'] . '.*')
                        ->from($this->tables['services'])
                        ->get();
    }

    /**
     * this method will retun a user services
     * @param  $user_id  user id
     * @author Rashida on 31 jan 2016
     * 
     */
    public function get_user_all_services($user_id) {
        $this->db->where($this->tables['users_services'] . '.user_id', $user_id);
        return $this->db->select($this->tables['services'] . '.id as service_id,' . $this->tables['services'] . '.*,' . $this->tables['users_services'] . '.*,' . $this->tables['users_services'] . '.id as user_service_id')
                        ->from($this->tables['users_services'])
                        ->join($this->tables['services'], $this->tables['services'] . '.id=' . $this->tables['users_services'] . '.service_id')
                        ->get();
    }

    /**
     * this method will retun a user services
     * @param  $user_id  user id
     * @author Rashida on 31 jan 2016
     * 
     */
    public function get_user_services($user_id) {
        $this->db->where($this->tables['users_services'] . '.user_id', $user_id);
        $this->db->where($this->tables['users_services'] . '.status', 1);
        return $this->db->select($this->tables['services'] . '.id as service_id,' . $this->tables['services'] . '.*,' . $this->tables['users_services'] . '.*,' . $this->tables['users_services'] . '.id as user_service_id')
                        ->from($this->tables['users_services'])
                        ->join($this->tables['services'], $this->tables['services'] . '.id=' . $this->tables['users_services'] . '.service_id')
                        ->get();
    }

    /**
     * this method will retun a user  operator services
     * @param  $user_id  user id
     * @author Rashida on 31 jan 2016
     * 
     */
    public function get_user_topup_services($user_id) {
        $this->db->where($this->tables['users_services'] . '.user_id', $user_id);
        $this->db->where($this->tables['users_services'] . '.status', 1);
        return $this->db->select($this->tables['operators'] . '.*')
                        ->from($this->tables['users_services'])
                        ->join($this->tables['operators'], $this->tables['operators'] . '.id=' . $this->tables['users_services'] . '.service_id')
                        ->get();
    }

    /**
     * this method will retun a operator types
     * @param  $user_id  user id
     * @author Rashida on 31 jan 2016
     * 
     */
    public function get_all_operator_types() {
        return $this->db->select($this->tables['operator_types'] . '.*')
                        ->from($this->tables['operator_types'])
                        ->get();
    }


}
