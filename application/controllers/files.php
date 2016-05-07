<?php

class Files extends Role_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->library('excel');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    public function index() {
        
    }

    function sms_readme_file_dowload() {
        $this->load->helper('download');
        force_download(SMS_README_FILE_NAME, file_get_contents(SMS_FILE_DOWNLOAD_DIRECTORY . SMS_README_FILE_NAME));
    }

    function topup_readme_file_dowload() {
        $this->load->helper('download');
        force_download(TOPUP_README_FILE_NAME, file_get_contents(TOPUP_FILE_DOWNLOAD_DIRECTORY . TOPUP_README_FILE_NAME));
    }

}
