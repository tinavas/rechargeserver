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

    function sms_read_me_file_dowload() {
        $this->load->helper('download');
        $data = file_get_contents(SMS_FILE_DOWNLOAD_DIRECTORY . SMS_README_FILE_NAME); // Read the file's contents
        force_download(SMS_README_FILE_NAME, $data);
    }

    function topup_read_me_file_dowload() {
        $this->load->helper('download');
        $file_path = TOPUP_FILE_DOWNLOAD_DIRECTORY;
        $file_name = TOPUP_README_FILE_NAME;
        $file_full_path = $file_path . $file_name;
        $data = file_get_contents($file_full_path); // Read the file's contents
        force_download($file_name, $data);
    }

}
