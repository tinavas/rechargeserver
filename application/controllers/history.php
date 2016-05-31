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

    public function index() {
        redirect('history/all_transactions', 'refresh');
    }

    /*
     * This method will return entire transaction history
     * @author nazmul hasan on 27th February 2016
     */

    public function all_transactions() {
        $this->data['message'] = "";
        $where = array(
            'user_id' => $this->session->userdata('user_id')
        );
        if (file_get_contents("php://input") != null) {
            $response = array();
            $from_date = 0;
            $to_date = 0;
            $offset = TRANSACTION_PAGE_DEFAULT_OFFSET;
            $limit = TRANSACTION_PAGE_DEFAULT_LIMIT;
            $status_id_list = array(TRANSACTION_STATUS_ID_SUCCESSFUL);
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "searchParam") != FALSE) {
                $search_param = $requestInfo->searchParam;
                if (property_exists($search_param, "fromDate") != FALSE) {
                    $from_date = $search_param->fromDate;
                }
                if (property_exists($search_param, "toDate") != FALSE) {
                    $to_date = $search_param->toDate;
                }
                if (property_exists($search_param, "offset") != FALSE) {
                    $offset = $search_param->offset;
                }
                if (property_exists($search_param, "limit") != FALSE) {
                    $limit_status = $search_param->limit;
                    if ($limit_status != FALSE) {
                        $limit = 0;
                    }
                }
                if (property_exists($search_param, "statusId") != FALSE) {
                    $status_id = $search_param->statusId;
                    $status_id_list = array($status_id);
                }
            }
            $transction_information_array = $this->transaction_library->get_user_transaction_list(array(), $status_id_list, $from_date, $to_date, $limit, $offset, $where);
            if (!empty($transction_information_array)) {
                $response['total_transactions'] = $transction_information_array['total_transactions'];
                $response['total_amount'] = $transction_information_array['total_amount'];
                $response['transaction_list'] = $transction_information_array['transaction_list'];
            }
            echo json_encode($response);
            return;
        }
        $total_transactions = 0;
        $total_amount = 0;
        $transaction_list = array();
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array(), array(TRANSACTION_STATUS_ID_SUCCESSFUL), 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, TRANSACTION_PAGE_DEFAULT_OFFSET, $where);
        if (!empty($transaction_list_array)) {
            $total_transactions = $transaction_list_array['total_transactions'];
            $total_amount = $transaction_list_array['total_amount'];
            $transaction_list = $transaction_list_array['transaction_list'];
        }
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->data['total_transactions'] = json_encode($total_transactions);
        $this->data['total_amount'] = json_encode($total_amount);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'history/transaction/index', $this->data);
    }

    /*
     * This method will return topup transaction history
     * @author nazmul hasan on 27th February 2016
     */

    public function topup_transactions($user_id = 0) {
        $where = array();
        $current_user_id = $this->session->userdata('user_id');
        if ($user_id == 0 || $user_id == $current_user_id) {
            $where['user_id'] = $current_user_id;
            $this->data['user_id'] = $current_user_id;
        } else if ($user_id != 0) {
            $this->load->library('reseller_library');
            $successor_id_list = $this->reseller_library->get_successor_id_list($current_user_id);
            if (!in_array($user_id, $successor_id_list)) {
                //you don't have permission to update this reseller
                $this->data['app'] = RESELLER_APP;
                $this->data['error_message'] = "Sorry !! You don't have permission to show this user.";
                $this->template->load(null, 'common/error_message', $this->data);
                return;
            }
            $where['user_id'] = $user_id;
            $this->data['user_id'] = $user_id;
        }
        $offset = TRANSACTION_PAGE_DEFAULT_OFFSET;
        if (file_get_contents("php://input") != null) {
            $limit = TRANSACTION_PAGE_DEFAULT_LIMIT;
            $status_id_list = array(TRANSACTION_STATUS_ID_SUCCESSFUL);
            $response = array();
            $from_date = 0;
            $to_date = 0;
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "searchParam") != FALSE) {
                $search_param = $requestInfo->searchParam;
                if (property_exists($search_param, "fromDate") != FALSE) {
                    $from_date = $search_param->fromDate;
                }
                if (property_exists($search_param, "toDate") != FALSE) {
                    $to_date = $search_param->toDate;
                }
                if (property_exists($search_param, "offset") != FALSE) {
                    $offset = $search_param->offset;
                }
                if (property_exists($search_param, "userId") != FALSE) {
                    $where['user_id'] = $search_param->userId;
                }
                if (property_exists($search_param, "limit") != FALSE) {
                    $limit_status = $search_param->limit;
                    if ($limit_status != FALSE) {
                        $limit = 0;
                    }
                }
                if (property_exists($search_param, "statusId") != FALSE) {
                    $status_id = $search_param->statusId;
                    $status_id_list = array($status_id);
                }
            }
            $transction_information_array = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_TOPUP_GP, SERVICE_TYPE_ID_TOPUP_ROBI, SERVICE_TYPE_ID_TOPUP_BANGLALINK, SERVICE_TYPE_ID_TOPUP_AIRTEL, SERVICE_TYPE_ID_TOPUP_TELETALK), $status_id_list, $from_date, $to_date, $limit, $offset, $where);
            if (!empty($transction_information_array)) {
                $response['total_transactions'] = $transction_information_array['total_transactions'];
                $response['total_amount'] = $transction_information_array['total_amount'];
                $response['transaction_list'] = $transction_information_array['transaction_list'];
            }
            echo json_encode($response);
            return;
        }

        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_TOPUP_GP, SERVICE_TYPE_ID_TOPUP_ROBI, SERVICE_TYPE_ID_TOPUP_BANGLALINK, SERVICE_TYPE_ID_TOPUP_AIRTEL, SERVICE_TYPE_ID_TOPUP_TELETALK), array(TRANSACTION_STATUS_ID_SUCCESSFUL), 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, $offset, $where);
        $total_transactions = 0;
        $total_amount = 0;
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            $total_transactions = $transaction_list_array['total_transactions'];
            $total_amount = $transaction_list_array['total_amount'];
            $transaction_list = $transaction_list_array['transaction_list'];
        }
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->data['total_transactions'] = json_encode($total_transactions);
        $this->data['total_amount'] = json_encode($total_amount);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'history/transaction/topup/index', $this->data);
    }

    /*
     * This method will return bkash transaction history
     * @author nazmul hasan on 27th February 2016
     */

    public function bkash_transactions($user_id = 0) {
        $where = array();
        $current_user_id = $this->session->userdata('user_id');
        if ($user_id == 0 || $user_id == $current_user_id) {
            $where['user_id'] = $current_user_id;
            $this->data['user_id'] = $current_user_id;
        } else if ($user_id != 0) {
            $this->load->library('reseller_library');
            $successor_id_list = $this->reseller_library->get_successor_id_list($current_user_id);
            if (!in_array($user_id, $successor_id_list)) {
                //you don't have permission to update this reseller
                $this->data['app'] = RESELLER_APP;
                $this->data['error_message'] = "Sorry !! You don't have permission to show this transcations.";
                $this->template->load(null, 'common/error_message', $this->data);
                return;
            }
            $where['user_id'] = $user_id;
            $this->data['user_id'] = $user_id;
        }
        $offset = TRANSACTION_PAGE_DEFAULT_OFFSET;
        if (file_get_contents("php://input") != null) {
            $limit = TRANSACTION_PAGE_DEFAULT_LIMIT;
            $status_id_list = array(TRANSACTION_STATUS_ID_SUCCESSFUL);
            $response = array();
            $from_date = 0;
            $to_date = 0;
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "searchParam") != FALSE) {
                $search_param = $requestInfo->searchParam;
                if (property_exists($search_param, "fromDate") != FALSE) {
                    $from_date = $search_param->fromDate;
                }
                if (property_exists($search_param, "toDate") != FALSE) {
                    $to_date = $search_param->toDate;
                }
                if (property_exists($search_param, "offset") != FALSE) {
                    $offset = $search_param->offset;
                }
                if (property_exists($search_param, "userId") != FALSE) {
                    $where['user_id'] = $search_param->userId;
                }
                if (property_exists($search_param, "limit") != FALSE) {
                    $limit_status = $search_param->limit;
                    if ($limit_status != FALSE) {
                        $limit = 0;
                    }
                }
                if (property_exists($search_param, "statusId") != FALSE) {
                    $status_id = $search_param->statusId;
                    $status_id_list = array($status_id);
                }
            }
            $transction_information_array = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_BKASH_CASHIN), $status_id_list, $from_date, $to_date, $limit, $offset, $where);
            if (!empty($transction_information_array)) {
                $response['total_transactions'] = $transction_information_array['total_transactions'];
                $response['total_amount'] = $transction_information_array['total_amount'];
                $response['transaction_list'] = $transction_information_array['transaction_list'];
            }
            echo json_encode($response);
            return;
        }
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_BKASH_CASHIN), array(TRANSACTION_STATUS_ID_SUCCESSFUL), 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, $offset, $where);
        $total_transactions = 0;
        $total_amount = 0;
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            $total_transactions = $transaction_list_array['total_transactions'];
            $total_amount = $transaction_list_array['total_amount'];
            $transaction_list = $transaction_list_array['transaction_list'];
        }
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->data['total_transactions'] = json_encode($total_transactions);
        $this->data['total_amount'] = json_encode($total_amount);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'history/transaction/bkash/index', $this->data);
    }

    /*
     * This method will return dbbl transaction history
     * @author nazmul hasan on 27th February 2016
     */

    public function dbbl_transactions($user_id = 0) {
        $where = array();
        $current_user_id = $this->session->userdata('user_id');
        if ($user_id == 0 || $user_id == $current_user_id) {
            $where['user_id'] = $current_user_id;
            $this->data['user_id'] = $current_user_id;
        } else if ($user_id != 0) {
            $this->load->library('reseller_library');
            $successor_id_list = $this->reseller_library->get_successor_id_list($current_user_id);
            if (!in_array($user_id, $successor_id_list)) {
                //you don't have permission to update this reseller
                $this->data['app'] = RESELLER_APP;
                $this->data['error_message'] = "Sorry !! You don't have permission to show this user.";
                $this->template->load(null, 'common/error_message', $this->data);
                return;
            }
            $where['user_id'] = $user_id;
            $this->data['user_id'] = $user_id;
        }
        $offset = TRANSACTION_PAGE_DEFAULT_OFFSET;
        if (file_get_contents("php://input") != null) {
            $limit = TRANSACTION_PAGE_DEFAULT_LIMIT;
            $status_id_list = array(TRANSACTION_STATUS_ID_SUCCESSFUL);
            $response = array();
            $from_date = 0;
            $to_date = 0;
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "searchParam") != FALSE) {
                $search_param = $requestInfo->searchParam;
                if (property_exists($search_param, "fromDate") != FALSE) {
                    $from_date = $search_param->fromDate;
                }
                if (property_exists($search_param, "toDate") != FALSE) {
                    $to_date = $search_param->toDate;
                }
                if (property_exists($search_param, "offset") != FALSE) {
                    $offset = $search_param->offset;
                }
                if (property_exists($search_param, "userId") != FALSE) {
                    $where['user_id'] = $search_param->userId;
                }
                if (property_exists($search_param, "limit") != FALSE) {
                    $limit_status = $search_param->limit;
                    if ($limit_status != FALSE) {
                        $limit = 0;
                    }
                }
                if (property_exists($search_param, "statusId") != FALSE) {
                    $status_id = $search_param->statusId;
                    $status_id_list = array($status_id);
                }
            }
            $transction_information_array = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_DBBL_CASHIN), $status_id_list, $from_date, $to_date, $limit, $offset, $where);
            if (!empty($transction_information_array)) {
                $response['total_transactions'] = $transction_information_array['total_transactions'];
                $response['total_amount'] = $transction_information_array['total_amount'];
                $response['transaction_list'] = $transction_information_array['transaction_list'];
            }
            echo json_encode($response);
            return;
        }
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_DBBL_CASHIN), array(TRANSACTION_STATUS_ID_SUCCESSFUL), 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, $offset, $where);
        $total_transactions = 0;
        $total_amount = 0;
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            $total_transactions = $transaction_list_array['total_transactions'];
            $total_amount = $transaction_list_array['total_amount'];
            $transaction_list = $transaction_list_array['transaction_list'];
        }
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->data['total_transactions'] = json_encode($total_transactions);
        $this->data['total_amount'] = json_encode($total_amount);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'history/transaction/dbbl/index', $this->data);
    }

    /*
     * This method will return mcash transaction history
     * @author nazmul hasan on 27th February 2016
     */

    public function mcash_transactions($user_id = 0) {
        $where = array();
        $current_user_id = $this->session->userdata('user_id');
        if ($user_id == 0 || $user_id == $current_user_id) {
            $where['user_id'] = $current_user_id;
            $this->data['user_id'] = $current_user_id;
        } else if ($user_id != 0) {
            $this->load->library('reseller_library');
            $successor_id_list = $this->reseller_library->get_successor_id_list($current_user_id);
            if (!in_array($user_id, $successor_id_list)) {
                //you don't have permission to update this reseller
                $this->data['app'] = RESELLER_APP;
                $this->data['error_message'] = "Sorry !! You don't have permission to show this user.";
                $this->template->load(null, 'common/error_message', $this->data);
                return;
            }
            $where['user_id'] = $user_id;
            $this->data['user_id'] = $user_id;
        }
        $offset = TRANSACTION_PAGE_DEFAULT_OFFSET;
        if (file_get_contents("php://input") != null) {
            $limit = TRANSACTION_PAGE_DEFAULT_LIMIT;
            $status_id_list = array(TRANSACTION_STATUS_ID_SUCCESSFUL);
            $response = array();
            $from_date = 0;
            $to_date = 0;
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "searchParam") != FALSE) {
                $search_param = $requestInfo->searchParam;
                if (property_exists($search_param, "fromDate") != FALSE) {
                    $from_date = $search_param->fromDate;
                }
                if (property_exists($search_param, "toDate") != FALSE) {
                    $to_date = $search_param->toDate;
                }
                if (property_exists($search_param, "offset") != FALSE) {
                    $offset = $search_param->offset;
                }
                if (property_exists($search_param, "userId") != FALSE) {
                    $where['user_id'] = $search_param->userId;
                }
                if (property_exists($search_param, "limit") != FALSE) {
                    $limit_status = $search_param->limit;
                    if ($limit_status != FALSE) {
                        $limit = 0;
                    }
                }
                if (property_exists($search_param, "statusId") != FALSE) {
                    $status_id = $search_param->statusId;
                    $status_id_list = array($status_id);
                }
            }
            $transction_information_array = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_DBBL_CASHIN), $status_id_list, $from_date, $to_date, $limit, $offset, $where);
            if (!empty($transction_information_array)) {
                $response['total_transactions'] = $transction_information_array['total_transactions'];
                $response['total_amount'] = $transction_information_array['total_amount'];
                $response['transaction_list'] = $transction_information_array['transaction_list'];
            }
            echo json_encode($response);
            return;
        }
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_MCASH_CASHIN), array(TRANSACTION_STATUS_ID_SUCCESSFUL), 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, $offset, $where);
        $total_transactions = 0;
        $total_amount = 0;
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            $total_transactions = $transaction_list_array['total_transactions'];
            $total_amount = $transaction_list_array['total_amount'];
            $transaction_list = $transaction_list_array['transaction_list'];
        }
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->data['total_transactions'] = json_encode($total_transactions);
        $this->data['total_amount'] = json_encode($total_amount);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'history/transaction/mcash/index', $this->data);
    }

    /*
     * This method will return ucash transaction history
     * @author nazmul hasan on 27th February 2016
     */

    public function ucash_transactions($user_id = 0) {
        $where = array();
        $current_user_id = $this->session->userdata('user_id');
        if ($user_id == 0 || $user_id == $current_user_id) {
            $where['user_id'] = $current_user_id;
            $this->data['user_id'] = $current_user_id;
        } else if ($user_id != 0) {
            $this->load->library('reseller_library');
            $successor_id_list = $this->reseller_library->get_successor_id_list($current_user_id);
            if (!in_array($user_id, $successor_id_list)) {
                //you don't have permission to update this reseller
                $this->data['app'] = RESELLER_APP;
                $this->data['error_message'] = "Sorry !! You don't have permission to show this user.";
                $this->template->load(null, 'common/error_message', $this->data);
                return;
            }
            $where['user_id'] = $user_id;
            $this->data['user_id'] = $user_id;
        }
        $offset = TRANSACTION_PAGE_DEFAULT_OFFSET;
        $limit = TRANSACTION_PAGE_DEFAULT_LIMIT;
        if (file_get_contents("php://input") != null) {
            $response = array();
            $from_date = 0;
            $to_date = 0;
            $status_id_list = array(TRANSACTION_STATUS_ID_SUCCESSFUL);
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "searchParam") != FALSE) {
                $search_param = $requestInfo->searchParam;
                if (property_exists($search_param, "fromDate") != FALSE) {
                    $from_date = $search_param->fromDate;
                }
                if (property_exists($search_param, "toDate") != FALSE) {
                    $to_date = $search_param->toDate;
                }
                if (property_exists($search_param, "offset") != FALSE) {
                    $offset = $search_param->offset;
                }
                if (property_exists($search_param, "userId") != FALSE) {
                    $where['user_id'] = $search_param->userId;
                }
                if (property_exists($search_param, "limit") != FALSE) {
                    $limit_status = $search_param->limit;
                    if ($limit_status != FALSE) {
                        $limit = 0;
                    }
                }
                if (property_exists($search_param, "statusId") != FALSE) {
                    $status_id = $search_param->statusId;
                    $status_id_list = array($status_id);
                }
            }
            $transction_information_array = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_DBBL_CASHIN), $status_id_list, $from_date, $to_date, $limit, $offset, $where);
            if (!empty($transction_information_array)) {
                $response['total_transactions'] = $transction_information_array['total_transactions'];
                $response['total_amount'] = $transction_information_array['total_amount'];
                $response['transaction_list'] = $transction_information_array['transaction_list'];
            }
            echo json_encode($response);
            return;
        }
        $transaction_list_array = $this->transaction_library->get_user_transaction_list(array(SERVICE_TYPE_ID_UCASH_CASHIN), array(TRANSACTION_STATUS_ID_SUCCESSFUL), 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, $offset, $where);
        $total_transactions = 0;
        $total_amount = 0;
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            $total_transactions = $transaction_list_array['total_transactions'];
            $total_amount = $transaction_list_array['total_amount'];
            $transaction_list = $transaction_list_array['transaction_list'];
        }
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->data['total_transactions'] = json_encode($total_transactions);
        $this->data['total_amount'] = json_encode($total_amount);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'history/transaction/ucash/index', $this->data);
    }

    /*
     * This method will show sms history
     * @author nazmul hasan on 30th March 2016
     */

    public function sms_transactions($user_id = 0) {
        $current_user_id = $this->session->userdata('user_id');
        if ($user_id == 0 || $current_user_id == $user_id) {
            $user_id = $current_user_id;
        } else {
            $this->load->library('reseller_library');
            $successor_id_list = $this->reseller_library->get_successor_id_list($current_user_id);
            if (!in_array($user_id, $successor_id_list)) {
                //you don't have permission to update this reseller
                $this->data['app'] = RESELLER_APP;
                $this->data['error_message'] = "Sorry !! You don't have permission to show this user.";
                $this->template->load(null, 'common/error_message', $this->data);
                return;
            }
        }
        if (file_get_contents("php://input") != null) {
            $offset = TRANSACTION_PAGE_DEFAULT_OFFSET;
            $limit = TRANSACTION_PAGE_DEFAULT_LIMIT;
            $status_id_list = array(TRANSACTION_STATUS_ID_SUCCESSFUL);
            $response = array();
            $from_date = 0;
            $to_date = 0;
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "searchParam") != FALSE) {
                $search_param = $requestInfo->searchParam;
                if (property_exists($search_param, "fromDate") != FALSE) {
                    $from_date = $search_param->fromDate;
                }
                if (property_exists($search_param, "toDate") != FALSE) {
                    $to_date = $search_param->toDate;
                }
                if (property_exists($search_param, "offset") != FALSE) {
                    $offset = $search_param->offset;
                }
                if (property_exists($search_param, "userId") != FALSE) {
                    $user_id = $search_param->userId;
                }
                if (property_exists($search_param, "limit") != FALSE) {
                    $limit_status = $search_param->limit;
                    if ($limit_status != FALSE) {
                        $limit = 0;
                    }
                }
                if (property_exists($search_param, "statusId") != FALSE) {
                    $status_id = $search_param->statusId;
                    $status_id_list = array($status_id);
                }
            }
            $transction_information_array = $this->transaction_library->get_user_sms_transaction_list($status_id_list, $from_date, $to_date, $limit, $offset, array('user_id' => $user_id));
            if (!empty($transction_information_array)) {
                $response['total_transactions'] = $transction_information_array['total_transactions'];
                $response['total_amount'] = $transction_information_array['total_amount'];
                $response['transaction_list'] = $transction_information_array['transaction_list'];
            }
            echo json_encode($response);
            return;
        }
        $transaction_list_array = $this->transaction_library->get_user_sms_transaction_list(array(TRANSACTION_STATUS_ID_SUCCESSFUL), 0, 0, TRANSACTION_PAGE_DEFAULT_LIMIT, TRANSACTION_PAGE_DEFAULT_OFFSET, array('user_id' => $user_id));
        $total_transactions = 0;
        $total_amount = 0;
        $transaction_list = array();
        if (!empty($transaction_list_array)) {
            $total_transactions = $transaction_list_array['total_transactions'];
            $total_amount = $transaction_list_array['total_amount'];
            $transaction_list = $transaction_list_array['transaction_list'];
        }
        $this->data['transaction_list'] = json_encode($transaction_list);
        $this->data['total_transactions'] = json_encode($total_transactions);
        $this->data['total_amount'] = json_encode($total_amount);
        $this->data['user_id'] = $user_id;
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'history/transaction/sms/index', $this->data);
    }

    /*
     * This method will display payment history
     * @author nazmul hasan on 3rd march 
     */

    public function get_payment_history() {
        $this->load->library('payment_library');
        $user_id = $this->session->userdata('user_id');
        $where = array(
            'user_id' => $user_id
        );
        $offset = PAYMENT_LIST_DEAFULT_OFFSET;
        if (file_get_contents("php://input") != null) {
            $limit = PAYMENT_LIST_DEAFULT_LIMIT;
            $status_id_list = array(TRANSACTION_STATUS_ID_SUCCESSFUL);
            $response = array();
            $start_date = 0;
            $end_date = 0;
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            $payment_type_id_list = array();
            if (property_exists($requestInfo, "searchParam") != FALSE) {
                $search_param = $requestInfo->searchParam;
                if (property_exists($search_param, "fromDate") != FALSE) {
                    $start_date = $search_param->fromDate;
                }
                if (property_exists($search_param, "toDate") != FALSE) {
                    $end_date = $search_param->toDate;
                }
                if (property_exists($search_param, "offset") != FALSE) {
                    $offset = $search_param->offset;
                }
                if (property_exists($search_param, "limit") != FALSE) {
                    $limit_status = $search_param->limit;
                    if ($limit_status != FALSE) {
                        $limit = 0;
                    }
                }
                if (property_exists($search_param, "statusId") != FALSE) {
                    $status_id = $search_param->statusId;
                    $status_id_list = array($status_id);
                }
                if (property_exists($search_param, "paymentTypeId") != FALSE && $search_param->paymentTypeId != '0') {

                    $payment_type_id_list = $search_param->paymentTypeId;
                } else {
                    $payment_type_id_list = array(
                        PAYMENT_TYPE_ID_SEND_CREDIT,
                        PAYMENT_TYPE_ID_RETURN_CREDIT
                    );
                }
            }
            $payment_info_list = $this->payment_library->get_payment_history($payment_type_id_list, $status_id_list, $start_date, $end_date, $limit, $offset, 'desc', $where);
            if (!empty($payment_info_list)) {
                $response['total_transactions'] = $payment_info_list['total_transactions'];
                $response['total_amount'] = $payment_info_list['total_amount_out'];
                $response['payment_info_list'] = $payment_info_list['payment_list'];
            }
            echo json_encode($response);
            return;
        }
        $payment_type_id_list = array(
            PAYMENT_TYPE_ID_SEND_CREDIT,
            PAYMENT_TYPE_ID_RETURN_CREDIT
        );
        $payment_list_array = $this->payment_library->get_payment_history($payment_type_id_list, array(TRANSACTION_STATUS_ID_SUCCESSFUL), 0, 0, PAYMENT_LIST_DEAFULT_LIMIT, $offset, 'desc', $where);
        $total_transactions = 0;
        $total_amount = 0;
        if (!empty($payment_list_array)) {
            $total_transactions = $payment_list_array['total_transactions'];
            $total_amount = $payment_list_array['total_amount_out'];
            $payment_list = $payment_list_array['payment_list'];
        }
        $payment_types = array(
            PAYMENT_TYPE_ID_SEND_CREDIT => 'Send credit',
            PAYMENT_TYPE_ID_RETURN_CREDIT => 'Return Credit',
            '0' => 'All'
        );
        $this->data['payment_type_ids'] = $payment_types;
        $this->data['total_transactions'] = json_encode($total_transactions);
        $this->data['total_amount'] = json_encode($total_amount);
        $this->data['payment_info_list'] = json_encode($payment_list);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'history/payment_history', $this->data);
    }

    /*
     * This method will display receive history
     * @author nazmul hasan on 3rd march 
     */

    public function get_receive_history() {
        $groups = $this->ion_auth->get_current_user_types();
        $group = "";
        foreach ($groups as $group_info) {
            if ($group_info == GROUP_ADMIN) {
                $group = $group_info;
                break;
                $payment_type_ids[] = PAYMENT_TYPE_ID_LOAD_BALANCE;
                $payment_types[PAYMENT_TYPE_ID_LOAD_BALANCE] = 'Load Balance';
            }
        }
        $this->load->library('payment_library');
        $user_id = $this->session->userdata('user_id');
        $where = array(
            'user_id' => $user_id
        );
        $offset = PAYMENT_LIST_DEAFULT_OFFSET;
        $limit = PAYMENT_LIST_DEAFULT_LIMIT;
        if (file_get_contents("php://input") != null) {
            $status_id_list = array(TRANSACTION_STATUS_ID_SUCCESSFUL);
            $response = array();
            $start_date = 0;
            $end_date = 0;
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            $payment_type_id_list = array();
            if (property_exists($requestInfo, "searchParam") != FALSE) {
                $search_param = $requestInfo->searchParam;
                if (property_exists($search_param, "fromDate") != FALSE) {
                    $start_date = $search_param->fromDate;
                }
                if (property_exists($search_param, "toDate") != FALSE) {
                    $end_date = $search_param->toDate;
                }
                if (property_exists($search_param, "offset") != FALSE) {
                    $offset = $search_param->offset;
                }
                if (property_exists($search_param, "limit") != FALSE) {
                    $limit_status = $search_param->limit;
                    if ($limit_status != FALSE) {
                        $limit = 0;
                    }
                }
                if (property_exists($search_param, "statusId") != FALSE) {
                    $status_id = $search_param->statusId;
                    $status_id_list = array($status_id);
                }
                if (property_exists($search_param, "paymentTypeId") != FALSE && $search_param->paymentTypeId != '0') {
                    $payment_type_id_list = $search_param->paymentTypeId;
                } else {
                    $payment_type_id_list = array(
                        PAYMENT_TYPE_ID_RECEIVE_CREDIT,
                        PAYMENT_TYPE_ID_RETURN_RECEIVE_CREDIT
                    );
                    if ($group == GROUP_ADMIN) {
                        $payment_type_id_list[] = PAYMENT_TYPE_ID_LOAD_BALANCE;
                    }
                }
            }
            $payment_info_list = $this->payment_library->get_payment_history($payment_type_id_list, $status_id_list, $start_date, $end_date, $limit, $offset, 'desc', $where);
            if (!empty($payment_info_list)) {
                $response['total_transactions'] = $payment_info_list['total_transactions'];
                $response['total_amount'] = $payment_info_list['total_amount_in'];
                $response['payment_info_list'] = $payment_info_list['payment_list'];
            }
            echo json_encode($response);
            return;
        }
        $payment_types = array(
            PAYMENT_TYPE_ID_RECEIVE_CREDIT => 'Receive Credit',
            PAYMENT_TYPE_ID_RETURN_RECEIVE_CREDIT => 'Return Receive Credit',
            '0' => 'All'
        );
        $payment_type_id_list = array(
            PAYMENT_TYPE_ID_RECEIVE_CREDIT,
            PAYMENT_TYPE_ID_RETURN_RECEIVE_CREDIT
        );
        if ($group == GROUP_ADMIN) {
            $payment_type_id_list[] = PAYMENT_TYPE_ID_LOAD_BALANCE;
            $payment_types[PAYMENT_TYPE_ID_LOAD_BALANCE] = 'Load Balance';
        }
        $payment_list_array = $this->payment_library->get_payment_history($payment_type_id_list, array(TRANSACTION_STATUS_ID_SUCCESSFUL), 0, 0, PAYMENT_LIST_DEAFULT_LIMIT, $offset, 'desc', $where);
        $total_transactions = 0;
        $total_amount = 0;
        if (!empty($payment_list_array)) {
            $total_transactions = $payment_list_array['total_transactions'];
            $total_amount = $payment_list_array['total_amount_in'];
            $payment_list = $payment_list_array['payment_list'];
        }
        $this->data['total_transactions'] = json_encode($total_transactions);
        $this->data['total_amount'] = json_encode($total_amount);
        $this->data['payment_type_ids'] = $payment_types;
        $this->data['payment_info_list'] = json_encode($payment_list);
        $this->data['app'] = TRANSCATION_APP;
        $this->template->load(null, 'history/receive_history', $this->data);
    }

    /*
     * This method will load pagination template
     * @author rashida on 26th April 2016 
     */

    function pagination_tmpl_load() {
        $this->load->view('dir_pagination');
    }

}
