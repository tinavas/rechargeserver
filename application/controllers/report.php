<?php

class Report extends Role_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('transaction_library');
        $this->load->library('pagination');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    public function index() {
        
    }

//    public function test($offset = 0) {
//
//        $offset = ($this->uri->segment(3) != '' ? $this->uri->segment(3) : 0);
//        $config['total_rows'] = 22;
//        $config['per_page'] = 4;
//        $config['first_link'] = 'First';
//        $config['last_link'] = 'Last';
//        $config['uri_segment'] = 3;
//        $config['cur_tag_open'] = '&nbsp;<a class="current">';
//        $config['cur_tag_close'] = '</a>';
//        $config['base_url'] = base_url() . 'report/test';
//        $config['suffix'] = '?' . http_build_query($_GET, '', "&");
//        $this->pagination->initialize($config);
//        $str_links = $this->pagination->create_links();
//        $this->data['paginglinks'] = explode('&nbsp;', $str_links);
////        $this->data['paginglinks'] = $this->pagination->create_links();
//        // Showing total rows count 
//
//        $this->data["result"] = $this->transaction_library->test_pagination($config["per_page"], $offset);
//        $this->load->view('welcome_message_1', $this->data);
//    }



    public function get_cost_and_profit() {
        $this->load->model('service_model');
        $user_id = $this->session->userdata('user_id');
        $user_services = $this->service_model->get_user_all_services($user_id)->result_array();
        foreach ($user_services as $user_service) {
            $service_ids[] = $user_service['service_id'];
        }
        $user_profits = $this->transaction_library->get_user_profit($user_id, $service_ids)->result_array();
        $this->data['user_profits'] = $user_profits;
        $this->data['app'] = REPORT_APP;
        $this->template->load(null, 'report/cost_and_profit', $this->data);
    }

    public function get_balance_report() {

        $this->data['app'] = REPORT_APP;
        $this->template->load(null, 'report/balance_report', $this->data);
    }

    public function get_total_report() {

        $this->data['app'] = REPORT_APP;
        $this->template->load(null, 'report/total_report', $this->data);
    }

}

?>