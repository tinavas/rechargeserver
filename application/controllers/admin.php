<?php
class Admin extends Role_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        //$this->load->library('transaction_library');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }
    public function index()
    {
        
    }
    
    public function load_balance()
    {
        $this->data['message'] = "";
        $this->template->load('admin/templates/admin_tmpl', 'admin/payment/load_balance', $this->data);
    }
    
    
}