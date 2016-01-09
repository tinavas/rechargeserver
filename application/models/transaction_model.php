<?php
class Transaction_model extends Ion_auth_model 
{
    public function __construct() {
        parent::__construct();    
    }

    public function add_transaction($transaction_data)
    {
        $this->db->trans_begin();
        $current_time = now();
        $transaction_data['created_on'] = $current_time;
        $transaction_data['modified_on'] = $current_time;
        $additional_data = $this->_filter_data($this->tables['user_transactions'], $transaction_data);
        $this->db->insert($this->tables['user_transactions'], $additional_data);
        $insert_id = $this->db->insert_id();
        
        if(isset($insert_id))
        {
            $data = array(
                'user_id' => $transaction_data['user_id'],
                'transaction_id' => $transaction_data['transaction_id'],
                'balance_in' => 0,
                'balance_out' => $transaction_data['amount'],
                'type_id' => PAYMENT_TYPE_ID_USE_SERVICE,
                'created_on' => $current_time,
                'modified_on' => $current_time
            );
            $payment_data = $this->_filter_data($this->tables['user_payments'], $data);
            $this->db->insert($this->tables['user_payments'], $payment_data);
            $insert_id = $this->db->insert_id();
            if(isset($insert_id))
            {
                $this->db->trans_commit();
                return TRUE;
            }
        }
        $this->db->trans_rollback();
        return FALSE;
    }
    
    public function get_user_transaction_list($service_id_list = array(), $limit = 0)
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
        if(!empty($service_id_list))
        {
            $this->db->where_in($this->tables['user_transactions'].'.service_id', $service_id_list);
        }
        $this->db->order_by('id','desc');
        return $this->db->select($this->tables['user_transactions'].'.*,'.$this->tables['user_transaction_statuses'].'.title as status')
            ->from($this->tables['user_transactions'])
            ->join($this->tables['user_transaction_statuses'], $this->tables['user_transaction_statuses'] . '.id=' . $this->tables['user_transactions'] . '.status_id')
            ->get();
    }
    
    public function get_users_current_balance($user_id_list = array())
    {
        $this->db->where_in($this->tables['user_transactions'].'.user_id', $user_id_list);
        $this->db->group_by('user_id');
        return $this->db->select('user_id, sum(balance_in) - sum(balance_out) as current_balance')
            ->from($this->tables['user_transactions'])
            ->get();
    }
}

