<?php

class Role_Controller extends CI_Controller{
    function __construct() {
        parent::__construct();
        $this->load->library('reseller_library');
        $this->data['current_balance'] = $this->reseller_library->get_user_current_balance();        
        $this->data['user_title'] = $this->reseller_library->get_user_title();
//        $this->load->library("org/profile/business/business_profile_library"); 
//        $this->load->library('notification');
//        $user_id = $this->session->userdata('user_id');
//        $this->data['user_id'] = $user_id;
//        $business_profile_info = $this->business_profile_library->get_profile_info();
//        $this->data['business_profile_info'] = $business_profile_info;
//        $this->data['total_unread_followers'] = 0;
//        $this->data['total_unread_notifications'] = 0;
//        $this->data['notification_list'] = array();        
    }
}
?>
