<?php

class Role_Controller extends CI_Controller{
    function __construct() {
        parent::__construct();
        $this->load->library('reseller_library');
        $this->data['current_balance'] = $this->reseller_library->get_user_current_balance();        
        $this->data['user_title'] = $this->reseller_library->get_user_title();
        $user_id = $this->session->userdata('user_id');
        $topup_service_allow_flag = FALSE;
        $this->load->model('service_model');
        $service_list = $this->service_model->get_user_assigned_services($user_id)->result_array();
        if (!empty($service_list)) {
            foreach ($service_list as $service) {
                if ($service['service_id'] == SERVICE_TYPE_ID_TOPUP_GP || $service['service_id'] == SERVICE_TYPE_ID_TOPUP_ROBI || $service['service_id'] == SERVICE_TYPE_ID_TOPUP_BANGLALINK || $service['service_id'] == SERVICE_TYPE_ID_TOPUP_AIRTEL || $service['service_id'] == SERVICE_TYPE_ID_TOPUP_TELETALK) {
                    $topup_service_allow_flag = TRUE;
                }
            }
        }
        $this->data['service_list'] = $service_list;
        $this->data['topup_service_allow_flag'] = $topup_service_allow_flag;
        $this->load->model('superadmin/org/company_info_configuration_model');
        $company_info = array();
        $company_info_array = $this->company_info_configuration_model->get_company_info()->result_array();
        if(!empty($company_info_array)){
         $company_info =  $company_info_array[0];
        }
         $this->data['company_title'] = $company_info['title'];
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
