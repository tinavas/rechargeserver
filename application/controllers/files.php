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

    public function import_topup_xlsx() {
        $this->data['message'] = '';
        if ($this->input->post('submit_btn')) {
            $config['upload_path'] = FILES_PATH;
            $config['allowed_types'] = 'xlsx';
            $this->load->library('utils');
            $random_string = $this->utils->get_random_string();
            $file_name = TOPUP_XLSX_FILE_NAME . "_" . $random_string . ".xlsx";
            $config['file_name'] = $file_name;
            $config['overwrite'] = FALSE;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload()) {
                $this->data['message'] = $this->upload->display_errors();
            } else {
                $file = FILES_PATH . $file_name;

                //read file from path
                $objPHPExcel = PHPExcel_IOFactory::load($file);

                //get only the Cell Collection
                $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

                //extract to a PHP readable array format
                $header = array();
                $arr_data = array();
                //task_tanvir validate each row before extracting information
                foreach ($cell_collection as $cell) {

                    $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();

                    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();

                    $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();

                    //header will/should be in row 1 only. of course this can be modified to suit your need.
                    if ($row == 1) {
                        $header[$row][$column] = $data_value;
                    } else {
                        $arr_data[$row][$column] = $data_value;
                    }
                }

                $header_len = sizeof($header[1]);
                $row_counter = 1;
                $error_messages = array();
                $i = 0;
                $transction_list = array();
                foreach ($arr_data as $result_data) {

                    if (sizeof($result_data) != $header_len) {

                        $error_messages[] = 'Row no ' . $row_counter . ' is not a valid row';
                        break;
                    }
                    if (strip_tags($result_data['A']) == '' || strip_tags($result_data['B']) == '' || strip_tags($result_data['C']) == '' || strip_tags($result_data['D']) == '') {
                        $error_messages[] = 'Row no ' . $row_counter . ' is contains empty cell';
                        break;
                    }
//                    var_dump($result_data['A']);
                    if ((array_key_exists('A', $result_data) && $this->utils->cell_number_validation($result_data['A']) == FALSE)) {
                        $error_messages[] = 'Please Enter a Valid Cell Number at row number ' . $row_counter;
                        break;
                    }

                    if ((array_key_exists('B', $result_data)) && $result_data['B'] < TOPUP_MINIMUM_CASH_IN_AMOUNT || $result_data['B'] > TOPUP_MAXIMUM_CASH_IN_AMOUNT) {

                        $error_messages[] = 'Please Give a Valid Amount at row number ' . $row_counter;
                        break;
                    }
                    $row_counter++;
                    $transction_data = array(
                        'number' => strip_tags($result_data['A']),
                        'amount' => strip_tags($result_data['B']),
                        'topupOperatorId' => strip_tags($result_data['C']),
                        'topupType' => strip_tags($result_data['D'])
                    );
                    $transction_list[] = $transction_data;
                }
                if (empty($error_messages)) {
                    $this->data['transactions_data'] = json_encode($transction_list);
                } else {
                    $this->data['transactions_data'] = json_encode(array());
                    $this->data['error_message'] = $error_messages[0];
                }
            }
        }
        $user_id = $this->session->userdata('user_id');
        $where = array(
            'user_id' => $user_id
        );
        $this->load->library('transaction_library');
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_TOPUP_GP, SERVICE_TYPE_ID_TOPUP_ROBI, SERVICE_TYPE_ID_TOPUP_AIRTEL, SERVICE_TYPE_ID_TOPUP_TELETALK), array(), 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, 0, $where);
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            $transaction_list = $transaction_list_array['transaction_list'];
        }
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->load->model('service_model');
        $topup_type_list = $this->service_model->get_all_operator_types()->result_array();
        $topup_operator_list = $this->service_model->get_user_topup_services($user_id)->result_array();
        $this->data['app'] = TRANSCATION_APP;
        $this->data['topup_type_list'] = json_encode($topup_type_list);
        $this->data['topup_operator_list'] = json_encode($topup_operator_list);
        $this->template->load(null, 'transaction/topup/index', $this->data);
    }

    public function import_sms_xlsx() {
        $this->data['message'] = '';
        if ($this->input->post('submit_btn')) {
            $config['upload_path'] = FILES_PATH;
            $config['allowed_types'] = 'xlsx';
            $this->load->library('utils');
            $random_string = $this->utils->get_random_string();
            $file_name = SMS_XLSX_FILE_NAME . "_" . $random_string . ".xlsx";
            $config['file_name'] = $file_name;
            $config['overwrite'] = FALSE;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload()) {
                $this->data['message'] = $this->upload->display_errors();
            } else {
                $file = FILES_PATH . $file_name;

                //read file from path
                $objPHPExcel = PHPExcel_IOFactory::load($file);

                //get only the Cell Collection
                $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

                //extract to a PHP readable array format
                $header = array();
                $arr_data = array();
                //task_tanvir validate each row before extracting information
                foreach ($cell_collection as $cell) {

                    $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();

                    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();

                    $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();

                    //header will/should be in row 1 only. of course this can be modified to suit your need.
                    if ($row == 1) {
                        $header[$row][$column] = $data_value;
                    } else {
                        $arr_data[$row][$column] = $data_value;
                    }
                }

                $header_len = sizeof($header[1]);
                $row_counter = 1;
                $error_messages = array();
                $i = 0;
                $transction_list = array();
                foreach ($arr_data as $result_data) {

                    if (sizeof($result_data) != $header_len) {

                        $error_messages[] = 'Row no ' . $row_counter . ' is not a valid row';
                        break;
                    }
                    if (strip_tags($result_data['A']) == '') {
                        $error_messages[] = 'Row no ' . $row_counter . ' is contains empty cell';
                        break;
                    }
                    if ((array_key_exists('A', $result_data) && $this->utils->cell_number_validation($result_data['A']) == FALSE)) {
                        $error_messages[] = 'Please Enter a Valid Cell Number at row number ' . $row_counter;
                        break;
                    }


                    $row_counter++;
                    $transction_data = array(
                        'number' => strip_tags($result_data['A'])
                    );
                    $transction_list[] = $transction_data;
                }
                if (empty($error_messages)) {
                    $this->data['transactions_data'] = json_encode($transction_list);
                } else {
                    $this->data['transactions_data'] = json_encode(array());
                    $this->data['error_message'] = $error_messages[0];
                }
            }
        }
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'transaction/sms/index', $this->data);
    }

}
