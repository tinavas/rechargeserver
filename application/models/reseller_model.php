<?php
class Reseller_model extends Ion_auth_model 
{
    public function __construct() {
        parent::__construct();    
    }

    public function get_reseller_list($user_id)
    {
        $this->db->where($this->tables['relations'].'.parent_user_id', $user_id);
        return $this->db->select($this->tables['users'].'.id as user_id,'.$this->tables['users'].'.*')
            ->from($this->tables['users'])
            ->join($this->tables['relations'], $this->tables['relations'] . '.child_user_id=' . $this->tables['users'] . '.id')
            ->get();
    }
    
    public function get_reseller_info($user_id)
    {
        $this->db->where($this->tables['users'].'.id', $user_id);
        return $this->db->select($this->tables['users'].'.id as user_id,'.$this->tables['users'].'.*')
            ->from($this->tables['users'])
            ->get();
    }
    
    public function get_user_title_info($user_id)
    {
        $this->db->where($this->tables['users'].'.id', $user_id);
        return $this->db->select($this->tables['users'].'.username,'.$this->tables['groups'].'.description')
            ->from($this->tables['users'])
            ->join($this->tables['users_groups'], $this->tables['users_groups'] . '.user_id=' . $this->tables['users'] . '.id')
            ->join($this->tables['groups'], $this->tables['groups'] . '.id=' . $this->tables['users_groups'] . '.group_id')
            ->get();
    }
    
    public function get_reseller_services($user_id)
    {
        $this->db->where($this->tables['users_services'].'.user_id', $user_id);
        return $this->db->select($this->tables['services'].'.id as service_id,'.$this->tables['services'].'.*,'.$this->tables['users_services'].'.*,'.$this->tables['users_services'].'.id as user_service_id')
            ->from($this->tables['users_services'])
            ->join($this->tables['services'], $this->tables['services'] . '.id=' . $this->tables['users_services'] . '.service_id')
            ->get();
    }
    
    public function update_reseller_rates($new_rate_list)
    {
        //try to use update batch instead of loop
        foreach($new_rate_list as $new_rate_info)
        {
            $this->db->where($this->tables['users_services'].'.id', $new_rate_info['id']);
            $this->db->update($this->tables['users_services'], $new_rate_info);
        }        
    }
}

