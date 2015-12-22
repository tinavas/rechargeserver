<?php
class Transaction extends CI_Controller {
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
                $this->curl->create(WEBSERVICE_URL_CREATE_TRANSACTION);
                $this->curl->post(array("APIKey" => $api_key, "amount" => $amount, "cell_no" => $cell_no, "description" => $description ));
                $result_event = json_decode($this->curl->execute());
                if (!empty($result_event)) {
                    $response_code = '';
                    if (property_exists($result_event, "responseCode") != FALSE) {
                        $response_code = $result_event->responseCode;
                    }
                    if($response_code == RESPONSE_CODE_SUCCESS)
                    {
                        if (property_exists($result_event, "result") != FALSE) {
                            $transaction_info = $result_event->result;
                            $transaction_id = $transaction_info->transactionId;
                            if(empty($transaction_id) || $transaction_id == "")
                            {
                                //Handle a message if there is no transaction id
                                $this->data['message'] = $this->message_codes[$response_code];
                            }
                            else
                            {
                                $transaction_data = array(
                                    'user_id' => $this->session->userdata('user_id'),
                                    'transaction_id' => $transaction_id,
                                    'service_id' => SERVICE_TYPE_ID_BKASH_CASHIN,
                                    'balance_in' => 0,
                                    'balance_out' => $this->input->post('amount'),
                                    'cell_no' => $this->input->post('number'),
                                    'description' => $description,
                                    'type_id' => TRANSACTION_TYPE_ID_USE_SERVICE,
                                    'status_id' => TRANSACTION_STATUS_ID_SUCCESSFUL
                                );
                                $t_id = $this->transaction_library->add_user_transaction($transaction_data);
                                if($t_id !== FALSE)
                                {
                                    $this->data['message'] = "Transaction is created successfully.";
                                }
                                else
                                {
                                    $this->data['message'] = 'Error while processing the transaction.';
                                }
                            }
                        }
                        else
                        {
                            //Handle a message if there is no result even though response code is success
                            $this->data['message'] = $this->message_codes[$response_code];
                        }
                    }
                    else
                    {
                        //Add a message for this response code
                        $this->data['message'] = $this->message_codes[$response_code];
                    }
                } 
                else
                {
                    $this->data['message'] = 'Server is unavailable. Please try later.';
                }
            }
            else
            {
                $this->data['message'] = validation_errors();
            }            
        }
        $transaction_list = $this->transaction_model->get_user_transaction_list($this->session->userdata('user_id'), 10)->result_array();
        $this->data['transaction_list'] = $transaction_list;
        $this->data['number'] = array(
            'id' => 'number',
            'name' => 'number',
            'type' => 'text'
        );
        $this->data['amount'] = array(
            'id' => 'amount',
            'name' => 'amount',
            'type' => 'text'
        );
        $this->data['submit_create_transaction'] = array(
            'id' => 'submit_create_transaction',
            'name' => 'submit_create_transaction',
            'type' => 'submit',
            'value' => 'Send',
        );
        $this->template->load('admin/templates/admin_tmpl','transaction/bkash/index', $this->data);
    }
}