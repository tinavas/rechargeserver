<?php

class Report extends Role_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    public function index() {
        
    }

    /*
     * This method will display profit of services
     * @author nazmul hasan on 3rd march 2016
     */
    public function get_cost_and_profit() {
        $user_id = $this->session->userdata('user_id');
        $this->load->model('cost_profit_model');
        $user_profits = $this->cost_profit_model->get_user_service_profits($user_id)->result_array();
        $this->data['user_profits'] = $user_profits;
        $this->data['app'] = REPORT_APP;
        $this->template->load(null, 'report/cost_and_profit', $this->data);
    }

    public function get_balance_report() {

        $this->data['app'] = REPORT_APP;
        $this->template->load(null, 'report/balance_report', $this->data);
    }

    public function get_total_report() {

        $this->load->library('cost_profit_library');
        $user_id = $this->session->userdata('user_id');
        $where = array(
            'user_id' => $user_id
        );
        $profit_list = $this->cost_profit_library->get_profit_history(array(), 0, 0, 0, 0, $where);
        //print_r($profit_list);
        //exit();
        $this->data['profit_list'] = json_encode($profit_list);
        $this->data['app'] = REPORT_APP;
        $this->template->load(null, 'report/total_report', $this->data);
    }

}

?>