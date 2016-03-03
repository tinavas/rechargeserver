<?php

class Transaction extends Role_Controller {

    public $message_codes = array();

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->library('transaction_library');
        $this->load->library('utils');
        $this->load->model('service_model');
        $this->load->config('ion_auth', TRUE);
        $this->lang->load('auth');
        $this->load->helper('language');
        $this->message_codes = $this->config->item('message_codes', 'ion_auth');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    public function index() {
        
    }

 
    /*
     * This method will process bkash transaction
     * @author nazmul hasan on 24th february 2016
     */
    public function bkash() {        
        $user_id = $this->session->userdata('user_id');
        if (file_get_contents("php://input") != null) {
            $response = array();
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "bkashInfo") != FALSE) {
                $bkashInfo = $requestInfo->bkashInfo;
                if (property_exists($bkashInfo, "number")) {
                    $cell_no = $bkashInfo->number;
                } else {
                    $response["message"] = "Cell Number is Required !!";
                    echo json_encode($response);
                    return;
                }
                if (property_exists($bkashInfo, "amount")) {
                    $amount = $bkashInfo->amount;
                } else {
                    $response["message"] = "Amount is Required !!";
                    echo json_encode($response);
                    return;
                }
            }
            if (isset($amount)) {
                if ($amount < BKASH_MINIMUM_CASH_IN_AMOUNT || $amount > BKASH_MAXIMUM_CASH_IN_AMOUNT) {
                    $response["message"] = "Please Give a Valid Amount !!";
                    echo json_encode($response);
                    return;
                }
            }
            if ($this->utils->cell_number_validation($cell_no) == FALSE) {
                $response["message"] = "Please Enter a Valid Cell Number !!";
                echo json_encode($response);
                return;
            }            
            $api_key = API_KEY_BKASH_CASHIN;
            $description = "test";
            $transaction_id = "";
            $transaction_data = array(
                'user_id' => $user_id,
                'transaction_id' => $transaction_id,
                'service_id' => SERVICE_TYPE_ID_BKASH_CASHIN,
                'amount' => $amount,
                'cell_no' => $cell_no,
                'description' => $description
            );
            if ($this->transaction_library->add_transaction($api_key, $transaction_data) !== FALSE) {
                $response['message'] = $this->transaction_library->messages_array();
            } else {
                $response['message'] = $this->transaction_library->errors_array();
            }

            echo json_encode($response);
            return;
        }
        //checking whether user has permission for bkash transaction
        $permission_exists = FALSE;
        $service_list = $this->service_model->get_user_assigned_services($user_id)->result_array();
        foreach($service_list as $service_info)
        {
            //if in future there is new service under bkash then update the logic here
            if($service_info['service_id'] == SERVICE_TYPE_ID_BKASH_CASHIN)
            {
                $permission_exists = TRUE;
            }
        }
        if(!$permission_exists)
        {
            //you are not allowed to use bkash transaction
            $this->data['app'] = TRANSCATION_APP;
            $this->data['error_message'] = "Sorry !! You are not allowed to use bkash service.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_list = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_BKASH_CASHIN),array(),0 ,0, TRANSACTION_PAGE_DEFAULT_LIMIT, 0, $where);
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'transaction/bkash/index', $this->data);
    }
    
    /*
     * This method will process dbbl transaction
     * @author nazmul hasan on 2nd March 2016
     */
    public function dbbl() {        
        if (file_get_contents("php://input") != null) {
            $response = array();
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "dbblInfo")) {
                $dbblInfo = $requestInfo->dbblInfo;
                if (property_exists($dbblInfo, "number")) {
                    $cell_no = $dbblInfo->number;
                } else {
                    $response["message"] = "Cell Number is Required !!";
                    echo json_encode($response);
                    return;
                }
                if (property_exists($dbblInfo, "amount")) {
                    $amount = $dbblInfo->amount;
                } else {
                    $response["message"] = "Amount is Required !!";
                    echo json_encode($response);
                    return;
                }
            }
            if (isset($amount)) {
                if ($amount < DBBL_MINIMUM_CASH_IN_AMOUNT || $amount > DBBL_MAXIMUM_CASH_IN_AMOUNT) {
                    $response["message"] = "Please Give a Valid Amount !!";
                    echo json_encode($response);
                    return;
                }
            }
            if ($this->utils->cell_number_validation($cell_no) == FALSE) {
                $response["message"] = "Please Enter a Valid Cell Number !!";
                echo json_encode($response);
                return;
            }
            $user_id = $this->session->userdata('user_id');
            $api_key = API_KEY_DBBL_CASHIN;
            $description = "test";
            $transaction_id = "";
            $transaction_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'transaction_id' => $transaction_id,
                'service_id' => SERVICE_TYPE_ID_DBBL_CASHIN,
                'amount' => $amount,
                'cell_no' => $cell_no,
                'description' => $description
            );
            if ($this->transaction_library->add_transaction($api_key, $transaction_data) !== FALSE) {
                $response['message'] = "Transaction is created successfully.";
            } else {
                $response['message'] = $this->ion_auth->messages_array();
            }

            echo json_encode($response);
            return;
        }
        //checking whether user has permission for dbbl transaction
        $user_id = $this->session->userdata('user_id');
        $permission_exists = FALSE;
        $service_list = $this->service_model->get_user_assigned_services($user_id)->result_array();
        foreach($service_list as $service_info)
        {
            //if in future there is new service under dbbl then update the logic here
            if($service_info['service_id'] == SERVICE_TYPE_ID_DBBL_CASHIN)
            {
                $permission_exists = TRUE;
            }
        }
        if(!$permission_exists)
        {
            //you are not allowed to use dbbl transaction
            $this->data['app'] = TRANSCATION_APP;
            $this->data['error_message'] = "Sorry !! You are not allowed to use dbbl service.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_list = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_DBBL_CASHIN), TRANSACTION_PAGE_DEFAULT_LIMIT, 0, 0, 0, $where);
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'transaction/dbbl/index', $this->data);
    }

    /*
     * This method will process mcash transaction
     * @author nazmul hasan on 2nd March 2016
     */
    public function mcash() {
        $user_id = $this->session->userdata('user_id');
        if (file_get_contents("php://input") != null) {
            $response = array();
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "mCashInfo")) {
                $mCashInfo = $requestInfo->mCashInfo;
                if (property_exists($mCashInfo, "number")) {
                    $cell_no = $mCashInfo->number;
                } else {
                    $response["message"] = "Cell Number is Required !!";
                    echo json_encode($response);
                    return;
                }
                if (property_exists($mCashInfo, "amount")) {
                    $amount = $mCashInfo->amount;
                } else {
                    $response["message"] = "Amount is Required !!";
                    echo json_encode($response);
                    return;
                }
            }
            if (isset($amount)) {
                if ($amount < MCASH_MINIMUM_CASH_IN_AMOUNT || $amount > MCASH_MAXIMUM_CASH_IN_AMOUNT) {
                    $response["message"] = "Please Give a Valid Amount !!";
                    echo json_encode($response);
                    return;
                }
            }
            if ($this->utils->cell_number_validation($cell_no) == FALSE) {
                $response["message"] = "Please Enter a Valid Cell Number !!";
                echo json_encode($response);
                return;
            }            
            $api_key = API_KEY_MKASH_CASHIN;
            $description = "test";
            $transaction_id = "";
            $transaction_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'transaction_id' => $transaction_id,
                'service_id' => SERVICE_TYPE_ID_MCASH_CASHIN,
                'amount' => $amount,
                'cell_no' => $cell_no,
                'description' => $description
            );
            if ($this->transaction_library->add_transaction($api_key, $transaction_data) !== FALSE) {
                $response['message'] = "Transaction is created successfully.";
            } else {
                $response['message'] = $this->ion_auth->messages_array();
            }

            echo json_encode($response);
            return;
        }
        //checking whether user has permission for mcash transaction
        $permission_exists = FALSE;
        $service_list = $this->service_model->get_user_assigned_services($user_id)->result_array();
        foreach($service_list as $service_info)
        {
            //if in future there is new service under mcash then update the logic here
            if($service_info['service_id'] == SERVICE_TYPE_ID_MCASH_CASHIN)
            {
                $permission_exists = TRUE;
            }
        }
        if(!$permission_exists)
        {
            //you are not allowed to use mcash transaction
            $this->data['app'] = TRANSCATION_APP;
            $this->data['error_message'] = "Sorry !! You are not allowed to use mcash service.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
         $transaction_list = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_MCASH_CASHIN), TRANSACTION_PAGE_DEFAULT_LIMIT, 0, 0, 0, $where);
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'transaction/mcash/index', $this->data);
    }

    /*
     * This method will process ucash transaction
     * @author nazmul hasan on 2nd March 2016
     */
    public function ucash() {
        $user_id = $this->session->userdata('user_id');
        if (file_get_contents("php://input") != null) {
            $response = array();
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "uCashInfo")) {
                $uCashInfo = $requestInfo->uCashInfo;
                if (property_exists($uCashInfo, "number")) {
                    $cell_no = $uCashInfo->number;
                } else {
                    $response["message"] = "Cell Number is Required !!";
                    echo json_encode($response);
                    return;
                }
                if (property_exists($uCashInfo, "amount")) {
                    $amount = $uCashInfo->amount;
                } else {
                    $response["message"] = "Amount is Required !!";
                    echo json_encode($response);
                    return;
                }
            }
            if (isset($amount)) {
                if ($amount < UCASH_MINIMUM_CASH_IN_AMOUNT || $amount > UCASH_MAXIMUM_CASH_IN_AMOUNT) {
                    $response["message"] = "Please Give a Valid Amount !!";
                    echo json_encode($response);
                    return;
                }
            }
            if ($this->utils->cell_number_validation($cell_no) == FALSE) {
                $response["message"] = "Please Enter a Valid Cell Number !!";
                echo json_encode($response);
                return;
            }            
            $api_key = API_KEY_UKASH_CASHIN;
            $description = "test";
            $transaction_id = "";
            $transaction_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'transaction_id' => $transaction_id,
                'service_id' => SERVICE_TYPE_ID_UCASH_CASHIN,
                'amount' => $amount,
                'cell_no' => $cell_no,
                'description' => $description
            );
            if ($this->transaction_library->add_transaction($api_key, $transaction_data) !== FALSE) {
                $response['message'] = "Transaction is created successfully.";
                ;
            } else {
                $response['message'] = $this->ion_auth->messages_array();
            }

            echo json_encode($response);
            return;
        }
        //checking whether user has permission for ucash transaction
        $permission_exists = FALSE;
        $service_list = $this->service_model->get_user_assigned_services($user_id)->result_array();
        foreach($service_list as $service_info)
        {
            //if in future there is new service under ucash then update the logic here
            if($service_info['service_id'] == SERVICE_TYPE_ID_UCASH_CASHIN)
            {
                $permission_exists = TRUE;
            }
        }
        if(!$permission_exists)
        {
            //you are not allowed to use ucash transaction
            $this->data['app'] = TRANSCATION_APP;
            $this->data['error_message'] = "Sorry !! You are not allowed to use ucash service.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
         $transaction_list = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_UCASH_CASHIN), TRANSACTION_PAGE_DEFAULT_LIMIT, 0, 0, 0, $where);
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'transaction/ucash/index', $this->data);
    }

    /*
     * This method will process topup transaction
     * @author nazmul hasan on 2nd March 2016
     */
    public function topup() {
        $user_id = $this->session->userdata('user_id');
        if (file_get_contents("php://input") != null) {
            $response = array();
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "topUpInfo")) {
                $topUpInfo = $requestInfo->topUpInfo;
                if (property_exists($topUpInfo, "number")) {
                    $cell_no = $topUpInfo->number;
                } else {
                    $response["message"] = "Cell Number is Required !!";
                    echo json_encode($response);
                    return;
                }
                if (property_exists($topUpInfo, "amount")) {
                    $amount = $topUpInfo->amount;
                } else {
                    $response["message"] = "Amount is Required !!";
                    echo json_encode($response);
                    return;
                }
            }
            if (isset($amount)) {
                if ($amount < TOPUP_MINIMUM_CASH_IN_AMOUNT || $amount > TOPUP_MAXIMUM_CASH_IN_AMOUNT) {
                    $response["message"] = "Please Give a Valid Amount !!";
                    echo json_encode($response);
                    return;
                }
            }
            if ($this->utils->cell_number_validation($cell_no) == FALSE) {
                $response["message"] = "Please Enter a Valid Cell Number !!";
                echo json_encode($response);
                return;
            }
            if (property_exists($topUpInfo, "topupOperatorId")) {
                $service_id = $topUpInfo->topupOperatorId;
            }
            if ($service_id == SERVICE_TYPE_ID_TOPUP_GP) {
                $api_key = API_KEY_CASHIN_GP;
            } else if ($service_id == SERVICE_TYPE_ID_TOPUP_ROBI) {
                $api_key = API_KEY_CASHIN_ROBI;
            } else if ($service_id == SERVICE_TYPE_ID_TOPUP_BANGLALINK) {
                $api_key = API_KEY_CASHIN_BANGLALINK;
            } else if ($service_id == SERVICE_TYPE_ID_TOPUP_AIRTEL) {
                $api_key = API_KEY_CASHIN_AIRTEL;
            } else if ($service_id == SERVICE_TYPE_ID_TOPUP_TELETALK) {
                $api_key = API_KEY_CASHIN_TELETALK;
            }
            $description = "test";
            $transaction_id = "";
            $transaction_data = array(
                'user_id' => $user_id,
                'transaction_id' => $transaction_id,
                'service_id' => $service_id,
                'operator_type_id' => $topUpInfo->topupType,
                'amount' => $amount,
                'cell_no' => $cell_no,
                'description' => $description
            );
            if ($this->transaction_library->add_transaction($api_key, $transaction_data) !== FALSE) {
                $response['message'] = "Transaction is created successfully.";
            } else {
                $response['message'] = $this->ion_auth->messages_array();
            }
            echo json_encode($response);
            return;
        }
        //checking whether user has permission for topup transaction
        $permission_exists = FALSE;
        $service_list = $this->service_model->get_user_assigned_services($user_id)->result_array();
        foreach($service_list as $service_info)
        {
            //if in future there is new service under topup then update the logic here
            $service_id = $service_info['service_id']; 
            if($service_id == SERVICE_TYPE_ID_TOPUP_GP || $service_id == SERVICE_TYPE_ID_TOPUP_ROBI || $service_id == SERVICE_TYPE_ID_TOPUP_BANGLALINK || $service_id == SERVICE_TYPE_ID_TOPUP_AIRTEL || $service_id == SERVICE_TYPE_ID_TOPUP_TELETALK)
            {
                $permission_exists = TRUE;
            }
        }
        if(!$permission_exists)
        {
            //you are not allowed to use topup transaction
            $this->data['app'] = TRANSCATION_APP;
            $this->data['error_message'] = "Sorry !! You are not allowed to use topup service.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }
        $where = array(
            'user_id' => $user_id
        );
         $transaction_list = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_TOPUP_GP,SERVICE_TYPE_ID_TOPUP_ROBI,SERVICE_TYPE_ID_TOPUP_AIRTEL,SERVICE_TYPE_ID_TOPUP_TELETALK), TRANSACTION_PAGE_DEFAULT_LIMIT, 0, 0, 0, $where);
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->load->model('service_model');
        $topup_type_list = $this->service_model->get_all_operator_types()->result_array();
        $topup_operator_list = $this->service_model->get_user_topup_services($user_id)->result_array();
        $this->data['app'] = TRANSCATION_APP;
        $this->data['topup_type_list'] = json_encode($topup_type_list);
        $this->data['topup_operator_list'] = json_encode($topup_operator_list);

        $this->template->load(null, 'transaction/topup/index', $this->data);
    }

}
