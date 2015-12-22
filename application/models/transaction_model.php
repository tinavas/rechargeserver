<?php
class Transaction_model extends Ion_auth_model 
{
    public function __construct() {
        parent::__construct();    
    }

    public function add_transaction($transaction_data)
    {
        $transaction_data['created_on'] = now();
        $additional_data = $this->_filter_data($this->tables['user_transactions'], $transaction_data);
        $this->db->insert($this->tables['user_transactions'], $additional_data);
        $insert_id = $this->db->insert_id();
        return (isset($insert_id)) ? $insert_id : FALSE;
    }
    
    public function get_user_transaction_list($user_id, $limit = 0)
    {
        if($limit > 0)
        {
            $this->db->limit($limit);
        }
        $this->db->where('user_id', $user_id);
        $this->db->where('type_id', TRANSACTION_TYPE_ID_USE_SERVICE);
        $this->db->order_by('id','desc');
        return $this->db->select($this->tables['user_transactions'].'.*,'.$this->tables['user_transaction_statuses'].'.title as status')
            ->from($this->tables['user_transactions'])
            ->join($this->tables['user_transaction_statuses'], $this->tables['user_transaction_statuses'] . '.id=' . $this->tables['user_transactions'] . '.status_id')
            ->get();
    }
}

