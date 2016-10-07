<?php

class User_model extends Ion_auth_model {

    public function __construct() {
        parent::__construct();
    }

    /*
     * This method will return user info from users table
     * @param $user_id, user id
     * @author nazmul hasan on 7th october 2016
     */
    public function get_user_info($user_id) {
        $this->db->where($this->tables['users'] . '.id', $user_id);
        return $this->db->select($this->tables['users'] . '.id as user_id,' . $this->tables['users'] . '.*')
                        ->from($this->tables['users'])
                        ->get();
    }
}
