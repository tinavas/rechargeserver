<?php
class Service_model extends Ion_auth_model 
{
    public function __construct() {
        parent::__construct();    
    }

    public function get_all_services()
    {
        return $this->db->select($this->tables['services'].'.id as service_id,'.$this->tables['services'].'.*')
            ->from($this->tables['services'])
            ->get();
    }
}

