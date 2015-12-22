<?php
class History extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('transaction_library');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }
    public function index()
    {
        
    }
    
    public function bkash()
    {
        $this->data['message'] = "";
        $transaction_list = $this->transaction_model->get_user_transaction_list($this->session->userdata('user_id'))->result_array();
        $this->data['transaction_list'] = $transaction_list;
        $this->template->load('admin/templates/admin_tmpl','history/bkash/index', $this->data);
    }
}