<?php

class Cost_profit_model extends Ion_auth_model {

    public function __construct() {
        parent::__construct();
    }

    /*
     * This method will return total profits of services
     * @param $user_id user id
     * @param $service_id_list service id list
     * @author nazmul hasan
     */
    public function get_user_service_profits($user_id, $service_id_list = array()) {
        $this->db->where($this->tables['user_profits'] . '.user_id', $user_id);
        if(!empty($service_id_list))
        {
            $this->db->where_in($this->tables['user_profits'] . '.service_id', $service_id_list);
        }        
        $this->db->where_in($this->tables['user_profits'] . '.status_id', array(TRANSACTION_STATUS_ID_PENDING, TRANSACTION_STATUS_ID_SUCCESSFUL));
        $this->db->group_by('service_id');
        return $this->db->select($this->tables['user_profits'] . '.service_id, sum(rate) as total_used_amount, sum(amount) as total_profit,' . $this->tables['services'] . '.title')
                        ->from($this->tables['user_profits'])
                        ->join($this->tables['services'], $this->tables['user_profits'] . '.service_id=' . $this->tables['services'] . '.id')
                        ->get();
    }
    
    public function get_profit_history($service_id_list, $start_time, $end_time, $limit, $offset)
    {
        //run each where that was passed
        if (isset($this->_ion_where) && !empty($this->_ion_where)) {
            foreach ($this->_ion_where as $where) {
                $this->db->where($where);
            }
            $this->_ion_where = array();
        }
        $this->db->where_in($this->tables['user_profits'] . '.status_id', array(TRANSACTION_STATUS_ID_PENDING, TRANSACTION_STATUS_ID_SUCCESSFUL));
        if ($limit > 0) {
            $this->db->limit($limit);
        }
        if ($offset > 0) {
            $this->db->offset($offset);
        }
        if ($start_time != 0 && $end_time != 0) {
            $this->db->where($this->tables['user_profits'] . '.created_on >=', $start_time);
            $this->db->where($this->tables['user_profits'] . '.created_on <=', $end_time);
        }
        if (!empty($service_id_list)) {
            $this->db->where_in($this->tables['user_profits'] . '.service_id', $service_id_list);
        }
        $this->db->order_by($this->tables['user_profits'].'.id','desc');
        return $this->db->select($this->tables['user_profits'] . '.*,' . $this->tables['users'] . '.id as user_id,' . $this->tables['users'] . '.first_name,' . $this->tables['users'] . '.last_name')
                    ->from($this->tables['user_profits'])
                    ->join($this->tables['users'], $this->tables['users'] . '.id=' . $this->tables['user_profits'] . '.reference_id')
                    ->get();
    }
}
