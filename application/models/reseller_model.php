<?php

class Reseller_model extends Ion_auth_model {

    public function __construct() {
        parent::__construct();
    }

    public function get_reseller_list($user_id) {
        $this->db->where($this->tables['relations'] . '.parent_user_id', $user_id);
        return $this->db->select($this->tables['users'] . '.id as user_id,' . $this->tables['users'] . '.*')
                        ->from($this->tables['users'])
                        ->join($this->tables['relations'], $this->tables['relations'] . '.child_user_id=' . $this->tables['users'] . '.id')
                        ->get();
    }

    /*
     * This method will return user info from users table
     * @param $user_id, user id
     * @return user info
     * @author nazmul hasan on 30th Janaury 2016
     */

    public function get_reseller_info($user_id) {
        $this->db->where($this->tables['users'] . '.id', $user_id);
        return $this->db->select($this->tables['users'] . '.id as user_id,' . $this->tables['users'] . '.*')
                        ->from($this->tables['users'])
                        ->get();
    }

    public function get_user_title_info($user_id) {
        $this->db->where($this->tables['users'] . '.id', $user_id);
        return $this->db->select($this->tables['users'] . '.username,' . $this->tables['groups'] . '.description')
                        ->from($this->tables['users'])
                        ->join($this->tables['users_groups'], $this->tables['users_groups'] . '.user_id=' . $this->tables['users'] . '.id')
                        ->join($this->tables['groups'], $this->tables['groups'] . '.id=' . $this->tables['users_groups'] . '.group_id')
                        ->get();
    }

    public function update_reseller_rates($new_rate_list) {
        //try to use update batch instead of loop
        foreach ($new_rate_list as $new_rate_info) {
            $this->db->where($this->tables['users_services'] . '.id', $new_rate_info['id']);
            $this->db->update($this->tables['users_services'], $new_rate_info);
        }
    }

    /**
     * this method return parent user id of a reseller
     * @user_id user id
      @author Rashida on 31 jan 2016
     */
    public function get_parent_user_id($user_id) {
        $this->db->where($this->tables['relations'] . '.child_user_id', $user_id);
        return $this->db->select($this->tables['relations'] . '.parent_user_id')
                        ->from($this->tables['relations'])
                        ->get();
    }

    public function get_child_user_id_list($parent_id_array = array()) {
        $this->db->where_in($this->tables['relations'] . '.parent_user_id', $parent_id_array);
        return $this->db->select($this->tables['relations'] . '.child_user_id')
                        ->from($this->tables['relations'])
                        ->get();
    }
    

    public function get_user_id_list($parent_id_array = array(), $user_list) {
        $child_list = $this->get_child_user_id_list($parent_id_array)->result_array();
        $parent_id_array = array();
        if (!empty($child_list)) {
            foreach ($child_list as $child) {
                $parent_id_array[] = $child['child_user_id'];
                if (!in_array($child['child_user_id'], $user_list)) {

                    $user_list[] = $child['child_user_id'];
                }
            }
            $this->get_user_id_list($parent_id_array, $user_list);
        }

        return $user_list;
    }

}
