<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reseller extends Role_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }
    public function index()
    {
        $this->data['message'] = "";
        $user_id = $this->session->userdata('user_id');
        $this->load->library('reseller_library');
        $this->data['reseller_list'] = $this->reseller_library->get_reseller_list($user_id);
        
        $group = $this->session->userdata('group');
        $successor_group_title = $this->config->item('successor_group_title', 'ion_auth');
        $title = $successor_group_title[$group];
        $this->data['title'] = $title;
        $this->data['group'] = $this->session->userdata('group');
        $this->template->load('admin/templates/admin_tmpl','reseller/index', $this->data);
    }
    
    /*
     * This method will create a new reseller
     */
    public function create_reseller()
    {
        $this->data['message'] = "";
        $this->form_validation->set_error_delimiters("<div style='color:red'>", '</div>');
        $this->form_validation->set_rules('username', 'Username', 'xss_clean|required');
        $this->form_validation->set_rules('password', 'Password', 'xss_clean|required');
        $this->load->model('service_model');
        $service_list = $this->service_model->get_all_services()->result_array();
        if ($this->input->post('submit_create_reseller')) {
            if($this->form_validation->run() == true)
            {
                $selected_service_id_list = $this->input->post('per');
                $user_service_list = array();
                foreach($service_list as $service_info)
                {
                    $user_service_info = array(
                        'service_id' => $service_info['service_id']
                    );
                    if(in_array($service_info['service_id'], $selected_service_id_list))
                    {
                        $user_service_info['status'] = 1;
                    }
                    else
                    {
                        $user_service_info['status'] = 0;
                    }
                    $user_service_list[] = $user_service_info;
                }
                $username = $this->input->post('username');
                $password = $this->input->post('password');
                $email = $this->input->post('email');
                $additional_data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'mobile' => $this->input->post('mobile'),
                    'note' => $this->input->post('note'),
                    'parent_user_id' => $this->session->userdata('user_id'),
                    'user_service_list' => $user_service_list
                );
                
                $group = $this->session->userdata('group');
                $group_successor_config = $this->config->item('successor_group_id', 'ion_auth');
                $group_ids = array(
                    $group_successor_config[$group]
                );
                $user_id = $this->ion_auth->register($username, $password, $email, $additional_data, $group_ids);
                if($user_id !== FALSE)
                {
                    $this->data['message'] = 'User is created successfully.';
                }
                else
                {
                    $this->data['message'] = 'Error while creating the user. Please try again later.';
                }
            }
            else
            {
                $this->data['message'] = validation_errors();
            }            
        }
        $group = $this->session->userdata('group');
        $successor_group_title = $this->config->item('successor_group_title', 'ion_auth');
        $title = $successor_group_title[$group];
        $this->data['title'] = $title;
        $this->data['service_list'] = $this->service_model->get_all_services()->result_array();
        $this->template->load('admin/templates/admin_tmpl','reseller/create_reseller', $this->data);
    }  
    
    public function update_reseller($user_id)
    {
        $this->data['message'] = "";
        
        $this->form_validation->set_error_delimiters("<div style='color:red'>", '</div>');
        $this->form_validation->set_rules('username', 'Username', 'xss_clean|required');
        $this->load->model('service_model');
        $service_list = $this->service_model->get_all_services()->result_array();
        if ($this->input->post('submit_update_reseller')) {
            if($this->form_validation->run() == true)
            {
                $selected_service_id_list = $this->input->post('per');
                $user_service_list = array();
                foreach($service_list as $service_info)
                {
                    $user_service_info = array(
                        'service_id' => $service_info['service_id']
                    );
                    if(in_array($service_info['service_id'], $selected_service_id_list))
                    {
                        $user_service_info['status'] = 1;
                    }
                    else
                    {
                        $user_service_info['status'] = 0;
                    }
                    $user_service_list[] = $user_service_info;
                }
                $additional_data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'mobile' => $this->input->post('mobile'),
                    'note' => $this->input->post('note'),
                    'user_service_list' => $user_service_list
                );
                if($this->ion_auth->update($user_id, $additional_data) !== FALSE)
                {
                    $this->data['message'] = 'User is updated successfully.';
                }
                else
                {
                    $this->data['message'] = 'Error while updating the user. Please try again later.';
                }
            }
            else
            {
                $this->data['message'] = validation_errors();
            }            
        }
        
        $this->load->model('reseller_model');
        $reseller_info = $this->reseller_model->get_reseller_info($user_id)->result_array();
        if(!empty($reseller_info))
        {
            $this->data['reseller_info'] = $reseller_info[0];
        }
        $this->data['service_list']  = $this->reseller_model->get_reseller_services($user_id)->result_array();
        $group = $this->session->userdata('group');
        $successor_group_title = $this->config->item('successor_group_title', 'ion_auth');
        $title = $successor_group_title[$group];
        $this->data['title'] = $title;
        $this->template->load('admin/templates/admin_tmpl','reseller/update_reseller', $this->data);
    }
    
    public function update_rate($user_id)
    {
        $this->data['message'] = "";
        $this->load->model('reseller_model');
        if ($this->input->post('submit_update_rate')) 
        {
            $new_rate_list = array();
            foreach($this->input->post('update') as $rate_info)
            {
                if(array_key_exists("enable", $rate_info))
                {
                    $new_rate_info = array(
                        'id' => $rate_info['key'],
                        'rate' => $rate_info['rate'],
                        'commission' => $rate_info['commission'],
                        'charge' => $rate_info['charge']
                    );
                    $new_rate_list[] = $new_rate_info;
                }                
            }
            if(!empty($new_rate_list))
            {
                $this->reseller_model->update_reseller_rates($new_rate_list);
            }            
        }
        
        $this->data['rate_list']  = $this->reseller_model->get_reseller_services($user_id)->result_array();
        $this->data['user_id'] = $user_id;
        $this->template->load('admin/templates/admin_tmpl','reseller/update_rate', $this->data);
    }
    
    
}