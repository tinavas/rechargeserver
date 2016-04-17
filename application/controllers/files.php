<?php

class Files extends Role_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    public function index() {
        
    }
    function sms_csv_file_dowload() {
        $this->load->helper('download');
        $data = file_get_contents(SMS_FILE_DOWNLOAD_DIRECTORY . SMS_CSV_FILE_NAME); // Read the file's contents
        force_download(SMS_CSV_FILE_NAME, $data);
    }

    function sms_read_me_file_dowload() {
        $this->load->helper('download');
        $data = file_get_contents(SMS_FILE_DOWNLOAD_DIRECTORY . SMS_README_FILE_NAME); // Read the file's contents
        force_download(SMS_README_FILE_NAME, $data);
    }
    
    
    


    function topup_csv_file_dowload() {
        $this->load->helper('download');
        $file_path = FILES_PATH;
        $file_name = TOPUP_CSV_FILE_NAME;
        $file_full_path = $file_path . $file_name;
        $data = file_get_contents($file_full_path); // Read the file's contents
        force_download($file_name, $data);
    }

    function topup_read_me_file_dowload() {
        $this->load->helper('download');
        $file_path = FILES_PATH;
        $file_name = TOPUP_README_FILE_NAME;
        $file_full_path = $file_path . $file_name;
        $data = file_get_contents($file_full_path); // Read the file's contents
        force_download($file_name, $data);
    }

    function upload() {
        if (!empty($_FILES)) {
            $tempPath = $_FILES['file']['tmp_name'];
            $uploadPath = FILES_PATH . $_FILES['file']['name'];
            move_uploaded_file($tempPath, $uploadPath);
            $answer = array('answer' => 'File transfer completed');
            $json = json_encode($answer);
            echo $json;
        } else {
            echo 'No files';
        }
    }
    
    function smsFileUpload() {
        if (!empty($_FILES)) {
            $tempPath = $_FILES['file']['tmp_name'];
            $uploadPath = SMS_FILE_UPLOAD_DIRECTORY . $_FILES['file']['name'];
            move_uploaded_file($tempPath, $uploadPath);
            $answer = array('answer' => 'File transfer completed');
            $json = json_encode($answer);
            echo $json;
        } else {
            echo 'No files';
        }
    }

}
