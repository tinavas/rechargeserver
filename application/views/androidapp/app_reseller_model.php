<?php

class App_reseller_model extends Ion_auth_model {

    public function __construct() {
        parent::__construct();
    }


  
    /*
     * This method will return user info
     * @param $user_id, user id
     * @param $app_session_id, session id
     * @return user info
     * @author rashida on 9th Aug 2016
     */

    public function get_user_information() {
        //run each where that was passed
        if (isset($this->_ion_where) && !empty($this->_ion_where)) {
            foreach ($this->_ion_where as $where) {
                $this->db->where($where);
            }
            $this->_ion_where = array();
        }
        return $this->db->select($this->tables['users'] . '.id as user_id,' . $this->tables['users'] . '.*')
                        ->from($this->tables['users'])
                        ->get();
    }

    /*
     * This method will update session id 
     * @param $user_id, user id
     * @param $app_session_id, session id
     * @author rashida on 9th Aug 2016
     */

    public function add_app_session_id($user_id, $session_id) {
        $this->db->where($this->tables['users'] . '.id', $user_id);
       return $this->db->update($this->tables['users'], array('app_session_id' => $session_id));
    }
    /*
     * This method will get app session id
     * @param $user_id, user id
     * @author rashida on 9th Aug 2016
     */

    public function get_app_session_id($user_id) {
      $this->db->where($this->tables['users'] . '.id', $user_id);
        return $this->db->select($this->tables['users'] . '.id as user_id,' . $this->tables['users'] . '.app_session_id')
                        ->from($this->tables['users'])
                        ->get();
    }

}
