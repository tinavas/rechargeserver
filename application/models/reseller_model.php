<?php

class Reseller_model extends Ion_auth_model {

    public function __construct() {
        parent::__construct();
    }

    /*
     * This method will return user title
     * @param $user_id, user id
     * @author nazmul hasan on 24th february 2016
     */

    public function get_user_title_info($user_id) {
        $this->db->where($this->tables['users'] . '.id', $user_id);
        return $this->db->select($this->tables['users'] . '.username,' . $this->tables['groups'] . '.description')
                        ->from($this->tables['users'])
                        ->join($this->tables['users_groups'], $this->tables['users_groups'] . '.user_id=' . $this->tables['users'] . '.id')
                        ->join($this->tables['groups'], $this->tables['groups'] . '.id=' . $this->tables['users_groups'] . '.group_id')
                        ->get();
    }

    /*
     * This method will return user info
     * @param $user_id, user id
     * @return user info
     * @author nazmul hasan on 27th february 2016
     */

    public function get_user_info($user_id) {
        $this->db->where($this->tables['users'] . '.id', $user_id);
        return $this->db->select($this->tables['users'] . '.id as user_id,' . $this->tables['users'] . '.*')
                        ->from($this->tables['users'])
                        ->get();
    }

    /*
     * This method will retun user group info
     * @param $user_id, user id
     * @author nazmul hasan on 27th february 2016
     */

    public function get_user_group_info($user_id) {
        $this->db->where($this->tables['users_groups'] . '.user_id', $user_id);
        return $this->db->select($this->tables['users_groups'] . '.*, ' . $this->tables['groups'] . '.*')
                        ->from($this->tables['users_groups'])
                        ->join($this->tables['groups'], $this->tables['users_groups'] . '.' . $this->join['groups'] . '=' . $this->tables['groups'] . '.id')
                        ->get();
    }

    /**
     * this method return parent user id of a user
     * @param $user_id user id
     * @author nazmul hasan on 29th february 2016
     */
    public function get_parent_user_id($user_id) {
        $this->db->where($this->tables['relations'] . '.child_user_id', $user_id);
        return $this->db->select($this->tables['relations'] . '.parent_user_id')
                        ->from($this->tables['relations'])
                        ->get();
    }

    /*
     * This method will return child user id list of a parents
     * @param $parent_user_id_array, array of parent user id
     * @author nazmul hasan on 27th february 2016
     */

    public function get_child_user_id_list($parent_user_id_array = array()) {
        $this->db->where_in($this->tables['relations'] . '.parent_user_id', $parent_user_id_array);
        return $this->db->select($this->tables['relations'] . '.child_user_id')
                        ->from($this->tables['relations'])
                        ->get();
    }

    /*
     * This method will return reseller list of a user
     * @param $user_id, user id
     * @author nazmul hasan on 27th february 2016
     */

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

    public function get_profile_info($user_id) {
        $this->db->where($this->tables['users'] . '.id', $user_id);
        return $this->db->select('id as user_id, username, first_name, last_name, email, mobile, max_user_no, note')
                        ->from($this->tables['users'])
                        ->get();
    }

    public function update_reseller_rates($new_rate_list) {
        //try to use update batch instead of loop
        foreach ($new_rate_list as $new_rate_info) {
            $this->db->where($this->tables['users_services'] . '.id', $new_rate_info['id']);
            $this->db->update($this->tables['users_services'], $new_rate_info);
        }
        return True;
    }

    public function update_reseller_services($child_id_list, $reseller_inactive_service_List = array()) {
        foreach ($child_id_list as $child_id) {
            foreach ($reseller_inactive_service_List as $service_id) {
                $this->db->where($this->tables['users_services'] . '.user_id', $child_id);
                $this->db->where($this->tables['users_services'] . '.service_id', $service_id);
                $this->db->update($this->tables['users_services'], array('status' => 0));
            }
        }
    }

    /**
     * this methord return user groups
     * @ $user_id
     * @return array
     * @author Rashida Sultana
     * */
    /* public function get_users_groups($user_id) {
      return $this->db->select($this->tables['users_groups'] . '.' . $this->join['groups'] . ' as id, ' . $this->tables['groups'] . '.name, ' . $this->tables['groups'] . '.description')
      ->where($this->tables['users_groups'] . '.user_id', $user_id)
      ->join($this->tables['groups'], $this->tables['users_groups'] . '.' . $this->join['groups'] . '=' . $this->tables['groups'] . '.id')
      ->get($this->tables['users_groups']);
      } */

    public function get_users_service_info($user_ids = array(), $service_id) {
        $this->db->order_by("user_id", "asc");
        $this->db->where_in($this->tables['users_services'] . '.user_id', $user_ids);
        $this->db->where($this->tables['users_services'] . '.service_id', $service_id);
        return $this->db->select($this->tables['users_services'] . '.*')
                        ->from($this->tables['users_services'])
                        ->get();
    }

    /**
     * this methord return user service info list
     * @ $user_id_list user id list
     * @ $service_id_list service id list
     * @author Rashida Sultana
     * */
    public function get_users_service_info_list($user_id_list = array(), $service_id_list = array()) {
        $this->db->order_by("user_id", "asc");
        $this->db->where_in($this->tables['users_services'] . '.user_id', $user_id_list);
        $this->db->where_in($this->tables['users_services'] . '.service_id', $service_id_list);
        return $this->db->select($this->tables['users_services'] . '.*')
                        ->from($this->tables['users_services'])
                        ->get();
    }

}
