<?php
class Payment_model extends Ion_auth_model 
{
    public function __construct() {
        parent::__construct();    
    }
    
    public function transfer_user_payment($sender_data, $receiver_data)
    {
        $this->db->trans_begin();
        $current_time = now();
        $sender_data['created_on'] = $current_time;
        $sender_data['modified_on'] = $current_time;
        $receiver_data['created_on'] = $current_time;
        $receiver_data['modified_on'] = $current_time;
        
        $s_payment_data = $this->_filter_data($this->tables['user_payments'], $sender_data);
        $this->db->insert($this->tables['user_payments'], $s_payment_data);
        $s_id = $this->db->insert_id();
        if(isset($s_id))
        {
            $r_payment_data = $this->_filter_data($this->tables['user_payments'], $receiver_data);
            $this->db->insert($this->tables['user_payments'], $r_payment_data);
            $r_id = $this->db->insert_id();            
            if(isset($r_id))
            {
                $this->db->trans_commit();
                return TRUE;
            }
        }
        $this->db->trans_rollback();
        return FALSE;
    }
    
    public function get_users_current_balance($user_id_list = array())
    {
        $this->db->where_in($this->tables['user_payments'].'.user_id', $user_id_list);
        $this->db->group_by('user_id');
        return $this->db->select('user_id, sum(balance_in) - sum(balance_out) as current_balance')
            ->from($this->tables['user_payments'])
            ->get();
    }
    
    public function get_payment_history($type_id_list = array(), $limit = 0)
    {
        //run each where that was passed
        if (isset($this->_ion_where) && !empty($this->_ion_where)) {
            foreach ($this->_ion_where as $where) {
                $this->db->where($where);
            }

            $this->_ion_where = array();
        }
        if($limit > 0)
        {
            $this->db->limit($limit);
        }
        if(!empty($type_id_list))
        {
            $this->db->where_in($this->tables['user_payments'].'.type_id', $type_id_list);
        }
        $this->db->order_by($this->tables['user_payments'].'.id','desc');
        return $this->db->select($this->tables['user_payments'].'.*,'.$this->tables['user_payment_types'].'.title')
            ->from($this->tables['user_payments'])
            ->join($this->tables['user_payment_types'], $this->tables['user_payment_types'] . '.id=' . $this->tables['user_payments'] . '.type_id')
            ->get();
    }
    
    public function get_receive_history($type_id_list = array(), $limit = 0)
    {
        //run each where that was passed
        if (isset($this->_ion_where) && !empty($this->_ion_where)) {
            foreach ($this->_ion_where as $where) {
                $this->db->where($where);
            }

            $this->_ion_where = array();
        }
        if($limit > 0)
        {
            $this->db->limit($limit);
        }
        if(!empty($type_id_list))
        {
            $this->db->where_in($this->tables['user_payments'].'.type_id', $type_id_list);
        }
        $this->db->order_by($this->tables['user_payments'].'.id','desc');
        return $this->db->select($this->tables['user_payments'].'.*,'.$this->tables['user_payment_types'].'.title')
            ->from($this->tables['user_payments'])
            ->join($this->tables['user_payment_types'], $this->tables['user_payment_types'] . '.id=' . $this->tables['user_payments'] . '.type_id')
            ->get();
    }
}

