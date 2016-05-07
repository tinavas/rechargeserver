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

    /* public function multipule_topups() {
      $user_id = $this->session->userdata('user_id');
      if (file_get_contents("php://input") != null) {
      $response = array();
      $postdata = file_get_contents("php://input");
      $requestInfo = json_decode($postdata);
      if (property_exists($requestInfo, "transactionDataList")) {
      $user_assigned_service_id_list = [];
      $user_topup_operator_id_list = $this->service_model->get_user_assigned_services($user_id)->result_array();
      if (!empty($user_topup_operator_id_list)) {
      // generate user assign service id list and declare specific transction data list
      foreach ($user_topup_operator_id_list as $operator_id_info) {
      $user_assigned_service_id_list[] = $operator_id_info['service_id'];
      }
      }

      $transaction_data_list = $requestInfo->transactionDataList;
      $transction_list = [];
      $total_amount = 0;
      $this->load->library('utils');
      foreach ($transaction_data_list as $key => $transaction_data) {
      $mapping_id = $this->utils->get_random_mapping_id();
      $description = "test";
      $transaction_id = "";
      $topup_data_info = array(
      'user_id' => $user_id,
      'transaction_id' => $transaction_id,
      'description' => $description,
      'mapping_id' => $mapping_id
      );

      if (property_exists($transaction_data, "number")) {
      $cell_no = $transaction_data->number;
      if ($this->utils->cell_number_validation($cell_no) == FALSE) {
      $response["message"] = "Please Enter a Valid Cell Number at row number " . ($key + 1);
      echo json_encode($response);
      return;
      }
      $topup_data_info['cell_no'] = $cell_no;
      } else {
      $response["message"] = "Cell Number is Required at row number " . ($key + 1);
      echo json_encode($response);
      return;
      }
      if (property_exists($transaction_data, "amount")) {
      $amount = $transaction_data->amount;
      $total_amount = $total_amount + $amount;
      if (isset($amount)) {
      if ($amount < TOPUP_MINIMUM_CASH_IN_AMOUNT || $amount > TOPUP_MAXIMUM_CASH_IN_AMOUNT) {
      $response["message"] = "Please Give a Valid Amount at row number " . ($key + 1);
      echo json_encode($response);
      return;
      }
      }
      $topup_data_info['amount'] = $amount;
      } else {
      $response["message"] = "Amount is Required  at row number " . ($key + 1);
      echo json_encode($response);
      return;
      }
      if (property_exists($transaction_data, "topupOperatorId")) {
      $service_id = $transaction_data->topupOperatorId;
      if (!in_array($service_id, $user_assigned_service_id_list)) {
      $response["message"] = "The Operator Id  is not assigned to you at row number " . ($key + 1);
      echo json_encode($response);
      return;
      }
      $topup_data_info['service_id'] = $service_id;
      } else {
      $response["message"] = "Operator Id  is Required at row number " . ($key + 1);
      echo json_encode($response);
      return;
      }
      if (property_exists($transaction_data, "topupType")) {
      $topup_type_id = $transaction_data->topupType;
      if ($topup_type_id != OPERATOR_TYPE_ID_PREPAID && $topup_type_id != OPERATOR_TYPE_ID_POSTPAID) {
      $response["message"] = "Please give valid Operator Type Id at row number " . ($key + 1);
      echo json_encode($response);
      return;
      }
      $topup_data_info['operator_type_id'] = $topup_type_id;
      } else {
      $response["message"] = "Operator Type Id  is Required at row number " . ($key + 1);
      echo json_encode($response);
      return;
      }
      $transction_list[] = $topup_data_info;
      }
      if ($this->transaction_library->add_multipule_transactions($transction_list, $user_assigned_service_id_list, $total_amount, $user_id) !== FALSE) {
      $response['message'] = $this->transaction_library->messages_array();
      } else {
      $response['message'] = $this->transaction_library->errors_array();
      }
      echo json_encode($response);
      return;
      } else {
      $response['message'] = "Sorry!! Please give a transaction Info";
      echo json_encode($response);
      return;
      }
      }
      } */

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
        foreach ($service_list as $service_info) {
            //if in future there is new service under bkash then update the logic here
            if ($service_info['service_id'] == SERVICE_TYPE_ID_BKASH_CASHIN) {
                $permission_exists = TRUE;
            }
        }
        if (!$permission_exists) {
            //you are not allowed to use bkash transaction
            $this->data['app'] = TRANSCATION_APP;
            $this->data['error_message'] = "Sorry !! You are not allowed to use bkash service.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_BKASH_CASHIN), array(), 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, 0, $where);
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            $transaction_list = $transaction_list_array['transaction_list'];
        }
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
        foreach ($service_list as $service_info) {
            //if in future there is new service under dbbl then update the logic here
            if ($service_info['service_id'] == SERVICE_TYPE_ID_DBBL_CASHIN) {
                $permission_exists = TRUE;
            }
        }
        if (!$permission_exists) {
            //you are not allowed to use dbbl transaction
            $this->data['app'] = TRANSCATION_APP;
            $this->data['error_message'] = "Sorry !! You are not allowed to use dbbl service.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_DBBL_CASHIN), array(), 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, 0, $where);
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            $transaction_list = $transaction_list_array['transaction_list'];
        }
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
        foreach ($service_list as $service_info) {
            //if in future there is new service under mcash then update the logic here
            if ($service_info['service_id'] == SERVICE_TYPE_ID_MCASH_CASHIN) {
                $permission_exists = TRUE;
            }
        }
        if (!$permission_exists) {
            //you are not allowed to use mcash transaction
            $this->data['app'] = TRANSCATION_APP;
            $this->data['error_message'] = "Sorry !! You are not allowed to use mcash service.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_MCASH_CASHIN), array(), 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, 0, $where);
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            $transaction_list = $transaction_list_array['transaction_list'];
        }
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
        foreach ($service_list as $service_info) {
            //if in future there is new service under ucash then update the logic here
            if ($service_info['service_id'] == SERVICE_TYPE_ID_UCASH_CASHIN) {
                $permission_exists = TRUE;
            }
        }
        if (!$permission_exists) {
            //you are not allowed to use ucash transaction
            $this->data['app'] = TRANSCATION_APP;
            $this->data['error_message'] = "Sorry !! You are not allowed to use ucash service.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_UCASH_CASHIN), array(), 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, 0, $where);
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            $transaction_list = $transaction_list_array['transaction_list'];
        }
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
        /* if (file_get_contents("php://input") != null) {
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
          } */
        if (file_get_contents("php://input") != null) {
            $response = array();
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "transactionDataList")) {
                $user_assigned_service_id_list = [];
                $user_topup_operator_id_list = $this->service_model->get_user_assigned_services($user_id)->result_array();
                if (!empty($user_topup_operator_id_list)) {
                    // generate user assign service id list and declare specific transction data list
                    foreach ($user_topup_operator_id_list as $operator_id_info) {
                        $user_assigned_service_id_list[] = $operator_id_info['service_id'];
                    }
                }

                $transaction_data_list = $requestInfo->transactionDataList;
                $transction_list = [];
                $total_amount = 0;
                $this->load->library('utils');
                foreach ($transaction_data_list as $key => $transaction_data) {
                    $mapping_id = $this->utils->get_random_mapping_id();
                    $description = "test";
                    $transaction_id = "";
                    $topup_data_info = array(
                        'user_id' => $user_id,
                        'transaction_id' => $transaction_id,
                        'description' => $description,
                        'mapping_id' => $mapping_id
                    );

                    if (property_exists($transaction_data, "number")) {
                        $cell_no = $transaction_data->number;
                        if ($this->utils->cell_number_validation($cell_no) == FALSE) {
                            $response["message"] = "Please Enter a Valid Cell Number at row number " . ($key + 1);
                            echo json_encode($response);
                            return;
                        }
                        $topup_data_info['cell_no'] = $cell_no;
                    } else {
                        $response["message"] = "Cell Number is Required at row number " . ($key + 1);
                        echo json_encode($response);
                        return;
                    }
                    if (property_exists($transaction_data, "amount")) {
                        $amount = $transaction_data->amount;
                        $total_amount = $total_amount + $amount;
                        if (isset($amount)) {
                            if ($amount < TOPUP_MINIMUM_CASH_IN_AMOUNT || $amount > TOPUP_MAXIMUM_CASH_IN_AMOUNT) {
                                $response["message"] = "Please Give a Valid Amount at row number " . ($key + 1);
                                echo json_encode($response);
                                return;
                            }
                        }
                        $topup_data_info['amount'] = $amount;
                    } else {
                        $response["message"] = "Amount is Required  at row number " . ($key + 1);
                        echo json_encode($response);
                        return;
                    }
                    if (property_exists($transaction_data, "topupOperatorId")) {
                        $service_id = $transaction_data->topupOperatorId;
                        if (!in_array($service_id, $user_assigned_service_id_list)) {
                            $response["message"] = "The Operator Id  is not assigned to you at row number " . ($key + 1);
                            echo json_encode($response);
                            return;
                        }
                        $topup_data_info['service_id'] = $service_id;
                    } else {
                        $response["message"] = "Operator Id  is Required at row number " . ($key + 1);
                        echo json_encode($response);
                        return;
                    }
                    if (property_exists($transaction_data, "topupType")) {
                        $topup_type_id = $transaction_data->topupType;
                        if ($topup_type_id != OPERATOR_TYPE_ID_PREPAID && $topup_type_id != OPERATOR_TYPE_ID_POSTPAID) {
                            $response["message"] = "Please give valid Operator Type Id at row number " . ($key + 1);
                            echo json_encode($response);
                            return;
                        }
                        $topup_data_info['operator_type_id'] = $topup_type_id;
                    } else {
                        $response["message"] = "Operator Type Id  is Required at row number " . ($key + 1);
                        echo json_encode($response);
                        return;
                    }
                    $transction_list[] = $topup_data_info;
                }
                if ($this->transaction_library->add_multipule_transactions($transction_list, $user_assigned_service_id_list, $total_amount, $user_id) !== FALSE) {
                    $response['message'] = $this->transaction_library->messages_array();
                } else {
                    $response['message'] = $this->transaction_library->errors_array();
                }
                echo json_encode($response);
                return;
            } else {
                $response['message'] = "Sorry!! Please give a transaction Info";
                echo json_encode($response);
                return;
            }
        } else if ($this->input->post('submit_btn')) {
            $error_messages = array();
            $config['upload_path'] = TOPUP_FILE_UPLOAD_DIRECTORY;
            $config['allowed_types'] = 'xlsx';
            $this->load->library('utils');
            $random_string = $this->utils->get_random_string();
            $file_name = $user_id . "_" . $random_string . ".xlsx";
            $config['file_name'] = $file_name;
            $config['overwrite'] = FALSE;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload()) {
                $this->data['error_message'] = $this->upload->display_errors();
            } else {
                $this->load->library('excel');
                $file = TOPUP_FILE_UPLOAD_DIRECTORY . $file_name;

                //read file from path
                $objPHPExcel = PHPExcel_IOFactory::load($file);

                //get only the Cell Collection
                $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

                //extract to a PHP readable array format
                $header = array();
                $arr_data = array();
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
                $transction_list = array();
                foreach ($arr_data as $result_data) {
                    if (sizeof($result_data) != $header_len) {
                        $error_messages[] = 'Row no ' . $row_counter . ' is not a valid row';
                        break;
                    }
                    if (strip_tags($result_data['A']) == '' || strip_tags($result_data['B']) == '' || strip_tags($result_data['C']) == '' || strip_tags($result_data['D']) == '') {
                        $row_counter++;
                        continue;
                    }
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
        //checking whether user has permission for topup transaction
        $permission_exists = FALSE;
        $service_list = $this->service_model->get_user_assigned_services($user_id)->result_array();
        foreach ($service_list as $service_info) {
            //if in future there is new service under topup then update the logic here
            $service_id = $service_info['service_id'];
            if ($service_id == SERVICE_TYPE_ID_TOPUP_GP || $service_id == SERVICE_TYPE_ID_TOPUP_ROBI || $service_id == SERVICE_TYPE_ID_TOPUP_BANGLALINK || $service_id == SERVICE_TYPE_ID_TOPUP_AIRTEL || $service_id == SERVICE_TYPE_ID_TOPUP_TELETALK) {
                $permission_exists = TRUE;
            }
        }
        if (!$permission_exists) {
            //you are not allowed to use topup transaction
            $this->data['app'] = TRANSCATION_APP;
            $this->data['error_message'] = "Sorry !! You are not allowed to use topup service.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }
        $where = array(
            'user_id' => $user_id
        );
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_TOPUP_GP, SERVICE_TYPE_ID_TOPUP_ROBI, SERVICE_TYPE_ID_TOPUP_BANGLALINK, SERVICE_TYPE_ID_TOPUP_AIRTEL, SERVICE_TYPE_ID_TOPUP_TELETALK), array(), 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, 0, $where);
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

    /*
     * This method will send bulk sms
     * @author nazmul hasan on 17th april 2016
     */

    public function sms() {
        $user_id = $this->session->userdata('user_id');
        $transction_list = array();
        if (file_get_contents("php://input") != null) {
            $response = array();
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "transactionDataList")) {
                $transaction_data_list = $requestInfo->transactionDataList;
                $transction_list = [];
                foreach ($transaction_data_list as $key => $transaction_data) {
                    $sms_data_info = array();
                    if (property_exists($transaction_data, "number")) {
                        $cell_no = $transaction_data->number;
                        if ($this->utils->cell_number_validation($cell_no) == FALSE) {
                            $response["message"] = "Please Enter a Valid Cell Number at row number " . $key + 1;
                            echo json_encode($response);
                            return;
                        }
                        $sms_data_info['cell_no'] = $cell_no;
                    } else {
                        $response["message"] = "Cell Number is Required at row number " . $key + 1;
                        echo json_encode($response);
                        return;
                    }
                    $sms_data_info['user_id'] = $user_id;
                    $sms_data_info['service_id'] = SERVICE_TYPE_ID_SEND_SMS;
                    $transction_list[] = $sms_data_info;
                    $sms = "";
                }
                if (property_exists($requestInfo, "smsInfo")) {
                    $smsInfo = $requestInfo->smsInfo;
                    if (property_exists($smsInfo, "sms")) {
                        $sms = $smsInfo->sms;
                    } else {
                        $response["message"] = "Please write sms text.";
                        echo json_encode($response);
                        return;
                    }
                }
                if ($this->transaction_library->add_sms_transactions($transction_list, $sms, $user_id) !== FALSE) {
                    $response['message'] = $this->transaction_library->messages_array();
                } else {
                    $response['message'] = $this->transaction_library->errors_array();
                }
                //$response['message'] = "Processing";
                echo json_encode($response);
                return;
            } else {
                $response['message'] = "Sorry!! Please provide cell number to send sms";
                echo json_encode($response);
                return;
            }
        } else if ($this->input->post('submit_btn')) {
            $config = array();
            $config['upload_path'] = SMS_FILE_UPLOAD_DIRECTORY;
            $config['allowed_types'] = 'xlsx';
            $this->load->library('utils');
            $random_string = $this->utils->get_random_string();
            $file_name = $user_id . "_" . $random_string . ".xlsx";
            $config['file_name'] = $file_name;
            $config['overwrite'] = FALSE;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload()) {
                $this->data['error_message'] = $this->upload->display_errors();
            } else {
                $this->load->library('excel');
                $file = SMS_FILE_UPLOAD_DIRECTORY . $file_name;
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
                foreach ($arr_data as $result_data) {
                    if (sizeof($result_data) != $header_len) {
                        $error_messages[] = 'Row no ' . $row_counter . ' is not a valid row';
                        break;
                    }
                    //ignoring empty row
                    if (array_key_exists('A', $result_data) && strip_tags($result_data['A']) == '') {
                        $row_counter++;
                        continue;
                    }
                    if ((array_key_exists('A', $result_data) && strip_tags($result_data['A']) == '' && $this->utils->cell_number_validation($result_data['A']) == FALSE)) {
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
                    $this->data['transactions_data'] = json_encode($transction_list);
                    $this->data['error_message'] = $error_messages[0];
                }
            }
        }
        //checking whether user has permission for bkash transaction
        $permission_exists = FALSE;
        $service_list = $this->service_model->get_user_assigned_services($user_id)->result_array();
        foreach ($service_list as $service_info) {
            //if in future there is new service under bkash then update the logic here
            if ($service_info['service_id'] == SERVICE_TYPE_ID_SEND_SMS) {
                $permission_exists = TRUE;
            }
        }
        if (!$permission_exists) {
            //you are not allowed to use bkash transaction
            $this->data['app'] = TRANSCATION_APP;
            $this->data['error_message'] = "Sorry !! You are not allowed to use sms service.";
            $this->template->load(null, 'common/error_message', $this->data);
            return;
        }
        $this->data['app'] = TRANSCATION_APP;
        $this->data['transaction_list'] = json_encode($transction_list);
        $this->template->load(null, 'transaction/sms/index', $this->data);
    }

}
