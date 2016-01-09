<?php
class History extends Role_Controller {
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
    
    public function all()
    {
        $this->data['message'] = "";
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_list = $this->transaction_model->where($where)->get_user_transaction_list(array())->result_array();
        $this->data['transaction_list'] = $transaction_list;
        $this->template->load('admin/templates/admin_tmpl','history/ucash/index', $this->data);
    }
    
    public function topup()
    {
        $this->data['message'] = "";
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_list = $this->transaction_model->where($where)->get_user_transaction_list(array(SERVICE_TYPE_ID_TOPUP_GP, SERVICE_TYPE_ID_TOPUP_ROBI, SERVICE_TYPE_ID_TOPUP_BANGLALINK, SERVICE_TYPE_ID_TOPUP_AIRTEL, SERVICE_TYPE_ID_TOPUP_TELETALK))->result_array();
        $this->data['transaction_list'] = $transaction_list;
        $this->template->load('admin/templates/admin_tmpl','history/topup/index', $this->data);
    }
    
    public function bkash()
    {
        $this->data['message'] = "";
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_list = $this->transaction_model->where($where)->get_user_transaction_list(array(SERVICE_TYPE_ID_BKASH_CASHIN))->result_array();
        $this->data['transaction_list'] = $transaction_list;
        $this->template->load('admin/templates/admin_tmpl','history/bkash/index', $this->data);
    }
    public function dbbl()
    {
        $this->data['message'] = "";
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_list = $this->transaction_model->where($where)->get_user_transaction_list(array(SERVICE_TYPE_ID_DBBL_CASHIN))->result_array();
        $this->data['transaction_list'] = $transaction_list;
        $this->template->load('admin/templates/admin_tmpl','history/dbbl/index', $this->data);
    }
    public function mcash()
    {
        $this->data['message'] = "";
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_list = $this->transaction_model->where($where)->get_user_transaction_list(array(SERVICE_TYPE_ID_MCASH_CASHIN))->result_array();
        $this->data['transaction_list'] = $transaction_list;
        $this->template->load('admin/templates/admin_tmpl','history/mcash/index', $this->data);
    }
    public function ucash()
    {
        $this->data['message'] = "";
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_list = $this->transaction_model->where($where)->get_user_transaction_list(array(SERVICE_TYPE_ID_UCASH_CASHIN))->result_array();
        $this->data['transaction_list'] = $transaction_list;
        $this->template->load('admin/templates/admin_tmpl','history/ucash/index', $this->data);
    }
}