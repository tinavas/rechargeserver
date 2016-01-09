<?php
class Transaction extends Role_Controller {
    public $message_codes = array();
    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->library('transaction_library');
        $this->load->config('ion_auth', TRUE);
        $this->message_codes = $this->config->item('message_codes', 'ion_auth');
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
        $this->form_validation->set_error_delimiters("<div style='color:red'>", '</div>');
        $this->form_validation->set_rules('number', 'Number', 'xss_clean|required');
        $this->form_validation->set_rules('amount', 'Amount', 'xss_clean|required');
        if ($this->input->post('submit_create_transaction')) {
            if($this->form_validation->run() == true)
            {
                $amount = $this->input->post('amount');  
                $api_key = API_KEY_BKASH_CASHIN;
                $cell_no = $this->input->post('number');
                $description = "test";

                $transaction_id = "";
                //calling the webservice for the transaction
//                $this->curl->create(WEBSERVICE_URL_CREATE_TRANSACTION);
//                $this->curl->post(array("APIKey" => $api_key, "amount" => $amount, "cell_no" => $cell_no, "description" => $description ));
//                $result_event = json_decode($this->curl->execute());
//                if (!empty($result_event)) {
//                    $response_code = '';
//                    if (property_exists($result_event, "responseCode") != FALSE) {
//                        $response_code = $result_event->responseCode;
//                    }
//                    if($response_code == RESPONSE_CODE_SUCCESS)
//                    {
//                        if (property_exists($result_event, "result") != FALSE) {
//                            $transaction_info = $result_event->result;
//                            $transaction_id = $transaction_info->transactionId;
//                            if(empty($transaction_id) || $transaction_id == "")
//                            {
//                                //Handle a message if there is no transaction id
//                                $this->data['message'] = $this->message_codes[$response_code];
//                            }
//                            else
//                            {
                                $transaction_data = array(
                                    'user_id' => $this->session->userdata('user_id'),
                                    'transaction_id' => $transaction_id,
                                    'service_id' => SERVICE_TYPE_ID_BKASH_CASHIN,
                                    'amount' => $this->input->post('amount'),
                                    'cell_no' => $this->input->post('number'),
                                    'description' => $description,
                                    'status_id' => TRANSACTION_STATUS_ID_SUCCESSFUL
                                );
                                if($this->transaction_model->add_transaction($transaction_data) !== FALSE)
                                {
                                    $this->data['message'] = "Transaction is created successfully.";
                                }
                                else
                                {
                                    $this->data['message'] = 'Error while processing the transaction.';
                                }
//                            }
//                        }
//                        else
//                        {
//                            //Handle a message if there is no result even though response code is success
//                            $this->data['message'] = $this->message_codes[$response_code];
//                        }
//                    }
//                    else
//                    {
//                        //Add a message for this response code
//                        $this->data['message'] = $this->message_codes[$response_code];
//                    }
//                } 
//                else
//                {
//                    $this->data['message'] = 'Server is unavailable. Please try later.';
//                }
            }
            else
            {
                $this->data['message'] = validation_errors();
            }            
        }
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_list = $this->transaction_model->where($where)->get_user_transaction_list(array(SERVICE_TYPE_ID_BKASH_CASHIN),10)->result_array();
        $this->data['transaction_list'] = $transaction_list;
        $this->data['number'] = array(
            'id' => 'number',
            'name' => 'number',
            'type' => 'text',
            'placeholder' => 'eg: 0171XXXXXXX'
        );
        $this->data['amount'] = array(
            'id' => 'amount',
            'name' => 'amount',
            'type' => 'text',
            'placeholder' => 'eg: 100'
        );
        $this->data['submit_create_transaction'] = array(
            'id' => 'submit_create_transaction',
            'name' => 'submit_create_transaction',
            'type' => 'submit',
            'value' => 'Send',
        );
        $this->template->load('admin/templates/admin_tmpl','transaction/bkash/index', $this->data);
    }
    public function dbbl()
    {
        $this->data['message'] = "";
        $this->form_validation->set_error_delimiters("<div style='color:red'>", '</div>');
        $this->form_validation->set_rules('number', 'Number', 'xss_clean|required');
        $this->form_validation->set_rules('amount', 'Amount', 'xss_clean|required');
        if ($this->input->post('submit_create_transaction')) {
            if($this->form_validation->run() == true)
            {
                $amount = $this->input->post('amount');  
                $api_key = API_KEY_DBBL_CASHIN;
                $cell_no = $this->input->post('number');
                $description = "test";

                $transaction_id = "";
                //calling the webservice for the transaction
//                $this->curl->create(WEBSERVICE_URL_CREATE_TRANSACTION);
//                $this->curl->post(array("APIKey" => $api_key, "amount" => $amount, "cell_no" => $cell_no, "description" => $description ));
//                $result_event = json_decode($this->curl->execute());
//                if (!empty($result_event)) {
//                    $response_code = '';
//                    if (property_exists($result_event, "responseCode") != FALSE) {
//                        $response_code = $result_event->responseCode;
//                    }
//                    if($response_code == RESPONSE_CODE_SUCCESS)
//                    {
//                        if (property_exists($result_event, "result") != FALSE) {
//                            $transaction_info = $result_event->result;
//                            $transaction_id = $transaction_info->transactionId;
//                            if(empty($transaction_id) || $transaction_id == "")
//                            {
//                                //Handle a message if there is no transaction id
//                                $this->data['message'] = $this->message_codes[$response_code];
//                            }
//                            else
//                            {
                                $transaction_data = array(
                                    'user_id' => $this->session->userdata('user_id'),
                                    'transaction_id' => $transaction_id,
                                    'service_id' => SERVICE_TYPE_ID_DBBL_CASHIN,
                                    'amount' => $this->input->post('amount'),
                                    'cell_no' => $this->input->post('number'),
                                    'description' => $description,
                                    'status_id' => TRANSACTION_STATUS_ID_SUCCESSFUL
                                );
                                if($this->transaction_model->add_transaction($transaction_data) !== FALSE)
                                {
                                    $this->data['message'] = "Transaction is created successfully.";
                                }
                                else
                                {
                                    $this->data['message'] = 'Error while processing the transaction.';
                                }
//                            }
//                        }
//                        else
//                        {
//                            //Handle a message if there is no result even though response code is success
//                            $this->data['message'] = $this->message_codes[$response_code];
//                        }
//                    }
//                    else
//                    {
//                        //Add a message for this response code
//                        $this->data['message'] = $this->message_codes[$response_code];
//                    }
//                } 
//                else
//                {
//                    $this->data['message'] = 'Server is unavailable. Please try later.';
//                }
            }
            else
            {
                $this->data['message'] = validation_errors();
            }            
        }
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_list = $this->transaction_model->where($where)->get_user_transaction_list(array(SERVICE_TYPE_ID_DBBL_CASHIN),10)->result_array();
        $this->data['transaction_list'] = $transaction_list;
        $this->data['number'] = array(
            'id' => 'number',
            'name' => 'number',
            'type' => 'text',
            'placeholder' => 'eg: 0171XXXXXXX'
        );
        $this->data['amount'] = array(
            'id' => 'amount',
            'name' => 'amount',
            'type' => 'text',
            'placeholder' => 'eg: 100'
        );
        $this->data['submit_create_transaction'] = array(
            'id' => 'submit_create_transaction',
            'name' => 'submit_create_transaction',
            'type' => 'submit',
            'value' => 'Send',
        );
        $this->template->load('admin/templates/admin_tmpl','transaction/dbbl/index', $this->data);
    }
    public function mcash()
    {
        $this->data['message'] = "";
        $this->form_validation->set_error_delimiters("<div style='color:red'>", '</div>');
        $this->form_validation->set_rules('number', 'Number', 'xss_clean|required');
        $this->form_validation->set_rules('amount', 'Amount', 'xss_clean|required');
        if ($this->input->post('submit_create_transaction')) {
            if($this->form_validation->run() == true)
            {
                $amount = $this->input->post('amount');  
                $api_key = API_KEY_MKASH_CASHIN;
                $cell_no = $this->input->post('number');
                $description = "test";

                $transaction_id = "";
                //calling the webservice for the transaction
//                $this->curl->create(WEBSERVICE_URL_CREATE_TRANSACTION);
//                $this->curl->post(array("APIKey" => $api_key, "amount" => $amount, "cell_no" => $cell_no, "description" => $description ));
//                $result_event = json_decode($this->curl->execute());
//                if (!empty($result_event)) {
//                    $response_code = '';
//                    if (property_exists($result_event, "responseCode") != FALSE) {
//                        $response_code = $result_event->responseCode;
//                    }
//                    if($response_code == RESPONSE_CODE_SUCCESS)
//                    {
//                        if (property_exists($result_event, "result") != FALSE) {
//                            $transaction_info = $result_event->result;
//                            $transaction_id = $transaction_info->transactionId;
//                            if(empty($transaction_id) || $transaction_id == "")
//                            {
//                                //Handle a message if there is no transaction id
//                                $this->data['message'] = $this->message_codes[$response_code];
//                            }
//                            else
//                            {
                                $transaction_data = array(
                                    'user_id' => $this->session->userdata('user_id'),
                                    'transaction_id' => $transaction_id,
                                    'service_id' => SERVICE_TYPE_ID_MCASH_CASHIN,
                                    'amount' => $this->input->post('amount'),
                                    'cell_no' => $this->input->post('number'),
                                    'description' => $description,
                                    'status_id' => TRANSACTION_STATUS_ID_SUCCESSFUL
                                );
                                if($this->transaction_model->add_transaction($transaction_data) !== FALSE)
                                {
                                    $this->data['message'] = "Transaction is created successfully.";
                                }
                                else
                                {
                                    $this->data['message'] = 'Error while processing the transaction.';
                                }
//                            }
//                        }
//                        else
//                        {
//                            //Handle a message if there is no result even though response code is success
//                            $this->data['message'] = $this->message_codes[$response_code];
//                        }
//                    }
//                    else
//                    {
//                        //Add a message for this response code
//                        $this->data['message'] = $this->message_codes[$response_code];
//                    }
//                } 
//                else
//                {
//                    $this->data['message'] = 'Server is unavailable. Please try later.';
//                }
            }
            else
            {
                $this->data['message'] = validation_errors();
            }            
        }
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_list = $this->transaction_model->where($where)->get_user_transaction_list(array(SERVICE_TYPE_ID_MCASH_CASHIN),10)->result_array();
        $this->data['transaction_list'] = $transaction_list;
        $this->data['number'] = array(
            'id' => 'number',
            'name' => 'number',
            'type' => 'text',
            'placeholder' => 'eg: 0171XXXXXXX'
        );
        $this->data['amount'] = array(
            'id' => 'amount',
            'name' => 'amount',
            'type' => 'text',
            'placeholder' => 'eg: 100'
        );
        $this->data['submit_create_transaction'] = array(
            'id' => 'submit_create_transaction',
            'name' => 'submit_create_transaction',
            'type' => 'submit',
            'value' => 'Send',
        );
        $this->template->load('admin/templates/admin_tmpl','transaction/mcash/index', $this->data);
    }
    public function ucash()
    {
        $this->data['message'] = "";
        $this->form_validation->set_error_delimiters("<div style='color:red'>", '</div>');
        $this->form_validation->set_rules('number', 'Number', 'xss_clean|required');
        $this->form_validation->set_rules('amount', 'Amount', 'xss_clean|required');
        if ($this->input->post('submit_create_transaction')) {
            if($this->form_validation->run() == true)
            {
                $amount = $this->input->post('amount');  
                $api_key = API_KEY_UKASH_CASHIN;
                $cell_no = $this->input->post('number');
                $description = "test";

                $transaction_id = "";
                //calling the webservice for the transaction
//                $this->curl->create(WEBSERVICE_URL_CREATE_TRANSACTION);
//                $this->curl->post(array("APIKey" => $api_key, "amount" => $amount, "cell_no" => $cell_no, "description" => $description ));
//                $result_event = json_decode($this->curl->execute());
//                if (!empty($result_event)) {
//                    $response_code = '';
//                    if (property_exists($result_event, "responseCode") != FALSE) {
//                        $response_code = $result_event->responseCode;
//                    }
//                    if($response_code == RESPONSE_CODE_SUCCESS)
//                    {
//                        if (property_exists($result_event, "result") != FALSE) {
//                            $transaction_info = $result_event->result;
//                            $transaction_id = $transaction_info->transactionId;
//                            if(empty($transaction_id) || $transaction_id == "")
//                            {
//                                //Handle a message if there is no transaction id
//                                $this->data['message'] = $this->message_codes[$response_code];
//                            }
//                            else
//                            {
                                $transaction_data = array(
                                    'user_id' => $this->session->userdata('user_id'),
                                    'transaction_id' => $transaction_id,
                                    'service_id' => SERVICE_TYPE_ID_UCASH_CASHIN,
                                    'amount' => $this->input->post('amount'),
                                    'cell_no' => $this->input->post('number'),
                                    'description' => $description,
                                    'status_id' => TRANSACTION_STATUS_ID_SUCCESSFUL
                                );
                                if($this->transaction_model->add_transaction($transaction_data) !== FALSE)
                                {
                                    $this->data['message'] = "Transaction is created successfully.";
                                }
                                else
                                {
                                    $this->data['message'] = 'Error while processing the transaction.';
                                }
//                            }
//                        }
//                        else
//                        {
//                            //Handle a message if there is no result even though response code is success
//                            $this->data['message'] = $this->message_codes[$response_code];
//                        }
//                    }
//                    else
//                    {
//                        //Add a message for this response code
//                        $this->data['message'] = $this->message_codes[$response_code];
//                    }
//                } 
//                else
//                {
//                    $this->data['message'] = 'Server is unavailable. Please try later.';
//                }
            }
            else
            {
                $this->data['message'] = validation_errors();
            }            
        }
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_list = $this->transaction_model->where($where)->get_user_transaction_list(array(SERVICE_TYPE_ID_UCASH_CASHIN),10)->result_array();
        $this->data['transaction_list'] = $transaction_list;
        $this->data['number'] = array(
            'id' => 'number',
            'name' => 'number',
            'type' => 'text',
            'placeholder' => 'eg: 0171XXXXXXX'
        );
        $this->data['amount'] = array(
            'id' => 'amount',
            'name' => 'amount',
            'type' => 'text',
            'placeholder' => 'eg: 100'
        );
        $this->data['submit_create_transaction'] = array(
            'id' => 'submit_create_transaction',
            'name' => 'submit_create_transaction',
            'type' => 'submit',
            'value' => 'Send',
        );
        $this->template->load('admin/templates/admin_tmpl','transaction/ucash/index', $this->data);
    }
    
    public function topup()
    {
        $this->data['message'] = "";
        $this->form_validation->set_error_delimiters("<div style='color:red'>", '</div>');
        $this->form_validation->set_rules('number', 'Number', 'xss_clean|required');
        $this->form_validation->set_rules('amount', 'Amount', 'xss_clean|required');
        if ($this->input->post('submit_create_transaction')) {
            if($this->form_validation->run() == true)
            {
                $amount = $this->input->post('amount');  
                $api_key = API_KEY_UKASH_CASHIN;
                $cell_no = $this->input->post('number');
                $description = "test";

                $transaction_id = "";
                //calling the webservice for the transaction
//                $this->curl->create(WEBSERVICE_URL_CREATE_TRANSACTION);
//                $this->curl->post(array("APIKey" => $api_key, "amount" => $amount, "cell_no" => $cell_no, "description" => $description ));
//                $result_event = json_decode($this->curl->execute());
//                if (!empty($result_event)) {
//                    $response_code = '';
//                    if (property_exists($result_event, "responseCode") != FALSE) {
//                        $response_code = $result_event->responseCode;
//                    }
//                    if($response_code == RESPONSE_CODE_SUCCESS)
//                    {
//                        if (property_exists($result_event, "result") != FALSE) {
//                            $transaction_info = $result_event->result;
//                            $transaction_id = $transaction_info->transactionId;
//                            if(empty($transaction_id) || $transaction_id == "")
//                            {
//                                //Handle a message if there is no transaction id
//                                $this->data['message'] = $this->message_codes[$response_code];
//                            }
//                            else
//                            {
                                $transaction_data = array(
                                    'user_id' => $this->session->userdata('user_id'),
                                    'transaction_id' => $transaction_id,
                                    'service_id' => $this->input->post('topup_operator_list'),
                                    'amount' => $this->input->post('amount'),
                                    'cell_no' => $this->input->post('number'),
                                    'description' => $description,
                                    'status_id' => TRANSACTION_STATUS_ID_SUCCESSFUL
                                );
                                if($this->transaction_model->add_transaction($transaction_data) !== FALSE)
                                {
                                    $this->data['message'] = "Transaction is created successfully.";
                                }
                                else
                                {
                                    $this->data['message'] = 'Error while processing the transaction.';
                                }
//                            }
//                        }
//                        else
//                        {
//                            //Handle a message if there is no result even though response code is success
//                            $this->data['message'] = $this->message_codes[$response_code];
//                        }
//                    }
//                    else
//                    {
//                        //Add a message for this response code
//                        $this->data['message'] = $this->message_codes[$response_code];
//                    }
//                } 
//                else
//                {
//                    $this->data['message'] = 'Server is unavailable. Please try later.';
//                }
            }
            else
            {
                $this->data['message'] = validation_errors();
            }            
        }
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_list = $this->transaction_model->where($where)->get_user_transaction_list(array(SERVICE_TYPE_ID_TOPUP_GP, SERVICE_TYPE_ID_TOPUP_ROBI, SERVICE_TYPE_ID_TOPUP_BANGLALINK, SERVICE_TYPE_ID_TOPUP_AIRTEL, SERVICE_TYPE_ID_TOPUP_TELETALK),10)->result_array();
        $this->data['transaction_list'] = $transaction_list;
        $this->data['topup_type_list'] = array(
            '1' => 'Prepaid',
            '2' => 'Postpaid'
        );
        //this list will be dynamic based on api subscription of the user
        $this->data['topup_operator_list'] = array(
            '101' => 'GP',
            '102' => 'Robi',
            '103' => 'BanglaLink',
            '104' => 'Airtel',
            '105' => 'TeleTalk'
        );
        $this->data['number'] = array(
            'id' => 'number',
            'name' => 'number',
            'type' => 'text',
            'placeholder' => 'eg: 0171XXXXXXX'
        );
        $this->data['amount'] = array(
            'id' => 'amount',
            'name' => 'amount',
            'type' => 'text',
            'placeholder' => 'eg: 100'
        );
        $this->data['submit_create_transaction'] = array(
            'id' => 'submit_create_transaction',
            'name' => 'submit_create_transaction',
            'type' => 'submit',
            'value' => 'Send',
        );
        $this->template->load('admin/templates/admin_tmpl','transaction/topup/index', $this->data);
    }
    
}