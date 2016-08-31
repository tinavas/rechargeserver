<?php

class Transaction_model extends Ion_auth_model {

    public function __construct() {
        parent::__construct();
        $this->load->config('ion_auth', TRUE);
        $this->lang->load('ion_auth');
    }

    /*
     * This method will udpate transaction as call back function from the authentication server
     * @param $transaction_id, transaction id
     * @param $status_id, status id
     * @param $sender_cell_number, sender cell number
     * @author nazmul hasan on 24th february 2016
     */

    public function update_transaction_callbackws($transaction_id, $status_id, $sender_cell_number) {
        $this->db->trans_begin();
        $transaction_data = array(
            'status_id' => $status_id,
            'sender_cell_no' => $sender_cell_number
        );
        $this->db->where('transaction_id', $transaction_id);
        $this->db->update('user_transactions', $transaction_data);

        $payment_data = array(
            'status_id' => $status_id
        );
        $this->db->where('transaction_id', $transaction_id);
        $this->db->update('user_payments', $payment_data);

        $profit_data = array(
            'status_id' => $status_id
        );
        $this->db->where('transaction_id', $transaction_id);
        $this->db->update('user_profits', $profit_data);
        $this->db->trans_commit();
        return true;
    }

    public function callbackws_update_transaction_editable_status($transaction_id, $editable) {
        $transaction_data = array(
            'editable' => $editable
        );
        $this->db->where('transaction_id', $transaction_id);
        $this->db->update('user_transactions', $transaction_data);
    }

    /*
     * This method will call the webservice and add a new transaction
     * @param $api_key, service API key of the transaction
     * @param $transaction_data, transaction data
     * @param $user_profit_data, user profit data
     * @author nazmul hasan on 24th February 2016
     */

    public function add_transaction($api_key, $transaction_data, $users_profit_data) {
        $amount = $transaction_data['amount'];
        $cell_no = $transaction_data['cell_no'];
        $description = $transaction_data['description'];
        $user_id = $transaction_data['user_id'];

        //checking whether user has enough balance before the transaction
        $this->load->model('payment_model');
        $user_current_balance_array = $this->payment_model->get_users_current_balance(array($user_id))->result_array();
        if (!empty($user_current_balance_array)) {
            $user_current_balance = $user_current_balance_array[0]['current_balance'];
            if ($amount > $user_current_balance) {
                $this->set_error('error_insufficient_balance');
                return FALSE;
            }
        } else {
            $this->set_error('error_insufficient_balance');
            return FALSE;
        }

        //create a default transaction and based on the response update the transaction.
        $this->load->library('Utils');
        $trx_id = $this->utils->get_transaction_id();
        $this->db->trans_begin();
        $current_time = now();
        $transaction_data['created_on'] = $current_time;
        $transaction_data['modified_on'] = $current_time;
        $transaction_data['transaction_id'] = $trx_id;
        $transaction_data['status_id'] = TRANSACTION_STATUS_ID_PENDING;
        $additional_data = $this->_filter_data($this->tables['user_transactions'], $transaction_data);
        $this->db->insert($this->tables['user_transactions'], $additional_data);
        $insert_id = $this->db->insert_id();
        if (isset($insert_id)) {
            $data = array(
                'user_id' => $user_id,
                'reference_id' => $user_id,
                'transaction_id' => $trx_id,
                'status_id' => TRANSACTION_STATUS_ID_PENDING,
                'balance_in' => 0,
                'balance_out' => $transaction_data['amount'],
                'type_id' => PAYMENT_TYPE_ID_USE_SERVICE,
                'created_on' => $current_time,
                'modified_on' => $current_time
            );
            $payment_data = $this->_filter_data($this->tables['user_payments'], $data);
            $this->db->insert($this->tables['user_payments'], $payment_data);
            $insert_id = $this->db->insert_id();
            if (isset($insert_id)) {
                $user_profit_list = array();
                foreach ($users_profit_data as $user_profit_info) {
                    $user_profit_info['transaction_id'] = $trx_id;
                    $user_profit_list[] = $user_profit_info;
                }
                $this->db->insert_batch($this->tables['user_profits'], $user_profit_list);
            }
        } else {
            $this->db->trans_rollback();
            $this->set_error('transaction_unsuccessful');
            return FALSE;
        }
        $package_id = OPERATOR_TYPE_ID_PREPAID;
        if (array_key_exists("operator_type_id", $transaction_data)) {
            $package_id = $transaction_data['operator_type_id'];
        }
        if ($transaction_data['type_id'] == SERVICE_STATUS_TYPE_ALLOW_TO_USE_LOCAL_SERVER) {
            $this->curl->create(WEBSERVICE_URL_CREATE_TRANSACTION);
            $this->curl->post(array("livetestflag" => TRANSACTION_FLAG_LIVE, "APIKey" => $api_key, "amount" => $amount, "cell_no" => $cell_no, "package_id" => $package_id, "description" => $description));
            $result_event = json_decode($this->curl->execute());
            if (!empty($result_event)) {
                $response_code = '';
                if (property_exists($result_event, "responseCode") != FALSE) {
                    $response_code = $result_event->responseCode;
                }
                if ($response_code == RESPONSE_CODE_SUCCESS) {
                    if (property_exists($result_event, "result") != FALSE) {
                        $transaction_info = $result_event->result;
                        $transaction_id = $transaction_info->transactionId;
                        if (empty($transaction_id) || $transaction_id == "") {
                            $this->db->trans_rollback();
                            $this->set_message('error_no_transaction_id');
                            return FALSE;
                        } else {
                            //update transaction id for transaction, payment and profit tables                        
                            $update_data = array(
                                'transaction_id' => $transaction_id
                            );
                            $this->db->where('transaction_id', $trx_id);
                            $this->db->update('user_transactions', $update_data);

                            $this->db->where('transaction_id', $trx_id);
                            $this->db->update('user_payments', $update_data);

                            $this->db->where('transaction_id', $trx_id);
                            $this->db->update('user_profits', $update_data);

                            $this->db->trans_commit();
                            $this->set_message('transaction_successful');
                            return TRUE;
                        }
                    } else {
                        $this->db->trans_rollback();
                        $this->set_error('error_no_result_event');
                        return FALSE;
                    }
                } else {
                    //set message based on response code
                    $this->db->trans_rollback();
                    $this->set_error('error_code_' . $response_code);
                    return FALSE;
                }
            }
        } else {
            $this->db->trans_commit();
            $this->set_message('transaction_successful');
            return TRUE;
        }
        $this->db->trans_rollback();
        $this->set_error('error_webservice_unavailable');
        return FALSE;
    }

    /*
     * This method will add multiple transactions in single step
     * @param $transaction_list, transaction list
     * @param $user_profit_list, profit list
     * @author nazmul hasan on 9th May 2016
     */

    public function add_transactions($transction_list, $user_profit_list) {
        $transaction_list_for_webservice = array();
        $user_transaction_list = array();
        $payment_list = array();
        $user_profits = array();
        $current_time = now();
        foreach ($transction_list as $transaction_info) {
            if ($transaction_info['type_id'] == SERVICE_STATUS_TYPE_ALLOW_TO_USE_LOCAL_SERVER) {
                $service_id = $transaction_info['service_id'];
                $transaction_info_for_webservice = array();
                if ($service_id == SERVICE_TYPE_ID_BKASH_CASHIN) {
                    $transaction_info_for_webservice['APIKey'] = API_KEY_BKASH_CASHIN;
                } else if ($service_id == SERVICE_TYPE_ID_DBBL_CASHIN) {
                    $transaction_info_for_webservice['APIKey'] = API_KEY_DBBL_CASHIN;
                } else if ($service_id == SERVICE_TYPE_ID_MCASH_CASHIN) {
                    $transaction_info_for_webservice['APIKey'] = API_KEY_MKASH_CASHIN;
                } else if ($service_id == SERVICE_TYPE_ID_UCASH_CASHIN) {
                    $transaction_info_for_webservice['APIKey'] = API_KEY_UKASH_CASHIN;
                } else if ($service_id == SERVICE_TYPE_ID_TOPUP_GP) {
                    $transaction_info_for_webservice['APIKey'] = API_KEY_CASHIN_GP;
                    $transaction_info_for_webservice['operator_type_id'] = $transaction_info['operator_type_id'];
                } else if ($service_id == SERVICE_TYPE_ID_TOPUP_ROBI) {
                    $transaction_info_for_webservice['APIKey'] = API_KEY_CASHIN_ROBI;
                    $transaction_info_for_webservice['operator_type_id'] = $transaction_info['operator_type_id'];
                } else if ($service_id == SERVICE_TYPE_ID_TOPUP_BANGLALINK) {
                    $transaction_info_for_webservice['APIKey'] = API_KEY_CASHIN_BANGLALINK;
                    $transaction_info_for_webservice['operator_type_id'] = $transaction_info['operator_type_id'];
                } else if ($service_id == SERVICE_TYPE_ID_TOPUP_AIRTEL) {
                    $transaction_info_for_webservice['APIKey'] = API_KEY_CASHIN_AIRTEL;
                    $transaction_info_for_webservice['operator_type_id'] = $transaction_info['operator_type_id'];
                } else if ($service_id == SERVICE_TYPE_ID_TOPUP_TELETALK) {
                    $transaction_info_for_webservice['APIKey'] = API_KEY_CASHIN_TELETALK;
                    $transaction_info_for_webservice['operator_type_id'] = $transaction_info['operator_type_id'];
                }
                $transaction_info_for_webservice['id'] = $transaction_info['mapping_id'];
                $transaction_info_for_webservice['service_id'] = $service_id;
                $transaction_info_for_webservice['amount'] = $transaction_info['amount'];
                $transaction_info_for_webservice['cell_no'] = $transaction_info['cell_no'];
                $transaction_info_for_webservice['description'] = $transaction_info['description'];
                $transaction_list_for_webservice[] = $transaction_info_for_webservice;
            }
//                'id' => $transaction_info['mapping_id'],
//                'APIKey' => $api_key,
//                'amount' => $transaction_info['amount'],
//                'cell_no' => $transaction_info['cell_no'],
//                'description' => $transaction_info['description']
//            );
            $transaction_info['created_on'] = $current_time;
            $transaction_info['modified_on'] = $current_time;
            $transaction_info['status_id'] = TRANSACTION_STATUS_ID_PENDING;
            $payment_info = array(
                'user_id' => $transaction_info['user_id'],
                'reference_id' => $transaction_info['user_id'],
                'status_id' => TRANSACTION_STATUS_ID_PENDING,
                'balance_in' => 0,
                'balance_out' => $transaction_info['amount'],
                'type_id' => PAYMENT_TYPE_ID_USE_SERVICE,
                'created_on' => $current_time,
                'modified_on' => $current_time
            );
            if ($transaction_info['type_id'] == SERVICE_STATUS_TYPE_ALLOW_TO_USE_WEBSERVER) {
                $trx_id = $this->utils->get_transaction_id();
                $transaction_info['transaction_id'] = $trx_id;
                $payment_info['transaction_id'] = $trx_id;
                foreach ($user_profit_list as $user_profit_info) {
                    $user_profit_info['transaction_id'] = $trx_id;
                    $user_profits_for_webserver[] = $this->_filter_data($this->tables['user_profits'], $user_profit_info);
                }
            }
            $user_transaction_list[$transaction_info['mapping_id']] = $this->_filter_data($this->tables['user_transactions'], $transaction_info);
            $payment_list[$transaction_info['mapping_id']] = $this->_filter_data($this->tables['user_payments'], $payment_info);
        }
        if ($transction_list[0]['type_id'] == SERVICE_STATUS_TYPE_ALLOW_TO_USE_LOCAL_SERVER) {
            $this->curl->create(WEBSERVICE_URL_CREATE_MULTIPULE_TRANSACTIONS);
            $this->curl->post(array("livetestflag" => TRANSACTION_FLAG_LIVE, "transction_list" => json_encode($transaction_list_for_webservice)));
            $result_event = json_decode($this->curl->execute());
            if (!empty($result_event)) {
                $response_code = '';
                if (property_exists($result_event, "responseCode") != FALSE) {
                    $response_code = $result_event->responseCode;
                }
                if ($response_code == RESPONSE_CODE_SUCCESS) {
                    if (property_exists($result_event, "result") != FALSE) {
                        $mapping_info_list = $result_event->result;
                        if (empty($mapping_info_list)) {
                            $this->set_message('error_no_transaction_id');
                            return FALSE;
                        } else {
                            foreach ($mapping_info_list as $mapping_info) {
                                $mapping_id = $mapping_info->referenceId;
                                $user_transaction_list[$mapping_id]['transaction_id'] = $mapping_info->transactionId;
                                $payment_list[$mapping_id]['transaction_id'] = $mapping_info->transactionId;
                                foreach ($user_profit_list as $user_profit_info) {
                                    if ($user_profit_info['mapping_id'] == $mapping_info->referenceId) {
                                        $user_profit_info['transaction_id'] = $mapping_info->transactionId;
                                        $user_profits[] = $this->_filter_data($this->tables['user_profits'], $user_profit_info);
                                    }
                                }
                            }

                            $this->db->trans_begin();
                            $this->db->insert_batch($this->tables['user_transactions'], $user_transaction_list);
                            $this->db->insert_batch($this->tables['user_payments'], $payment_list);
                            $this->db->insert_batch($this->tables['user_profits'], $user_profits);
                            $this->db->trans_commit();
                            $this->set_message('transaction_successful');
                            return TRUE;
                        }
                    } else {
                        $this->set_error('error_no_result_event');
                        return FALSE;
                    }
                } else {
                    //set message based on response code
                    $this->set_error('error_code_' . $response_code);
                    return FALSE;
                }
            } else {
                $this->set_error('error_webservice_unavailable');
            }
        } else if ($transction_list[0]['type_id'] == SERVICE_STATUS_TYPE_ALLOW_TO_USE_WEBSERVER) {
            $this->db->trans_begin();
            $this->db->insert_batch($this->tables['user_transactions'], $user_transaction_list);
            $this->db->insert_batch($this->tables['user_payments'], $payment_list);
            $this->db->insert_batch($this->tables['user_profits'], $user_profits_for_webserver);
            $this->db->trans_commit();
            $this->set_message('transaction_successful');
            return TRUE;
        }
        return FALSE;
    }

    /*
     * This method will add sms transaction
     * @param $api_key, APIKey
     * @param $transaction_list, transaction list
     * @param $sms_info, sms info
     * @param $payment_info, payment info
     * @author nazmul hasan on 17th april 2016
     */

    public function add_sms_transactions($api_key, $transaction_list, $sms_info, $payment_info) {
        $current_time = now();
        $sms_info['created_on'] = $current_time;
        $sms_info['modified_on'] = $current_time;
        $payment_info['created_on'] = $current_time;
        $payment_info['modified_on'] = $current_time;
        $this->load->library('Utils');
        $trx_id = $this->utils->get_transaction_id();
        $sms_info['transaction_id'] = $trx_id;
        $payment_info['transaction_id'] = $trx_id;
        $sms_transaction_list = array();
        $cell_number_list = array();
        foreach ($transaction_list as $transaction_info) {
            $transaction_info['transaction_id'] = $trx_id;
            $transaction_info['created_on'] = $current_time;
            $transaction_info['modified_on'] = $current_time;
            $transaction_info['status_id'] = TRANSACTION_STATUS_ID_PENDING;
            $transaction_data = $this->_filter_data($this->tables['user_sms_transactions'], $transaction_info);
            $sms_transaction_list[] = $transaction_data;
            $cell_info = array(
                'cell_no' => $transaction_info['cell_no']
            );
            $cell_number_list[] = $cell_info;
        }
        $this->db->trans_begin();

        $this->db->insert_batch($this->tables['user_sms_transactions'], $sms_transaction_list);

        $sms_data = $this->_filter_data($this->tables['sms_details'], $sms_info);
        $this->db->insert($this->tables['sms_details'], $sms_data);

        $payment_data = $this->_filter_data($this->tables['user_payments'], $payment_info);
        $this->db->insert($this->tables['user_payments'], $payment_data);
        //right now we are not assigning profit for sms transaction

        $this->curl->create(WEBSERVICE_URL_SEND_SMS);
        $this->curl->post(array("livetestflag" => TRANSACTION_FLAG_LIVE, "APIKey" => $api_key, "sms" => $sms_info['sms'], "cellnumberlist" => json_encode($cell_number_list)));
        $result_event = json_decode($this->curl->execute());
        if (!empty($result_event)) {
            $response_code = '';
            if (property_exists($result_event, "responseCode") != FALSE) {
                $response_code = $result_event->responseCode;
            }
            if ($response_code == RESPONSE_CODE_SUCCESS) {
                if (property_exists($result_event, "result") != FALSE) {
                    $transaction_info = $result_event->result;
                    $transaction_id = $transaction_info->transactionId;
                    //update transaction id based on response for transaction and payment table

                    if (empty($transaction_id) || $transaction_id == "") {
                        $this->db->trans_rollback();
                        $this->set_message('error_no_transaction_id');
                        return FALSE;
                    } else {
                        //update transaction id for sms transaction, payment and sms details tables                        
                        $update_data = array(
                            'transaction_id' => $transaction_id
                        );
                        $this->db->where('transaction_id', $trx_id);
                        $this->db->update('user_sms_transactions', $update_data);

                        $this->db->where('transaction_id', $trx_id);
                        $this->db->update('user_payments', $update_data);

                        $this->db->where('transaction_id', $trx_id);
                        $this->db->update('sms_details', $update_data);

                        $this->db->trans_commit();
                        $this->set_message('transaction_successful');
                        return TRUE;
                    }
                } else {
                    $this->db->trans_rollback();
                    $this->set_error('error_no_result_event');
                    return FALSE;
                }
            } else {
                //set message based on response code
                $this->db->trans_rollback();
                $this->set_error('error_code_' . $response_code);
                return FALSE;
            }
        }
        $this->db->trans_rollback();
        $this->set_error('error_webservice_unavailable');
        return FALSE;
    }

    public function update_transaction_info($transaction_data, $user_profit_list) {
        $this->db->trans_begin();
        //update user transactions table
        $trx_update_data = array(
            'cell_no' => $transaction_data['cell_no'],
            'amount' => $transaction_data['amount']
        );
        $this->db->where('transaction_id', $transaction_data['transaction_id']);
        $this->db->update('user_transactions', $trx_update_data);
        //update user payment table
        $trx_payment_data = array(
            'balance_out' => $transaction_data['amount']
        );
        $this->db->where('transaction_id', $transaction_data['transaction_id']);
        $this->db->update('user_payments', $trx_payment_data);
        //update user profit table
        $this->db->where('transaction_id', $transaction_data['transaction_id']);
        $this->db->delete('user_profits');
        $this->db->insert_batch($this->tables['user_profits'], $user_profit_list);


        $this->curl->create(WEBSERVICE_URL_UPDATE_TRANSACTION);
        $this->curl->post(array("transaction_id" => $transaction_data['transaction_id'], "amount" => $transaction_data['amount'], "cell_no" => $transaction_data['cell_no']));
        $result_event = json_decode($this->curl->execute());
        if (!empty($result_event)) {
            $response_code = '';
            if (property_exists($result_event, "responseCode") != FALSE) {
                $response_code = $result_event->responseCode;
            }
            if ($response_code == RESPONSE_CODE_SUCCESS) {
                if (property_exists($result_event, "result") != FALSE) {
                    $transaction_info = $result_event->result;
                    $transaction_id = $transaction_info->transactionId;

                    if (empty($transaction_id) || $transaction_id == "") {
                        $this->db->trans_rollback();
                        $this->set_message('error_no_transaction_id');
                        return FALSE;
                    } else {
                        $this->db->trans_commit();
                        $this->set_message('transaction_successful');
                        return TRUE;
                    }
                } else {
                    $this->db->trans_rollback();
                    $this->set_error('error_no_result_event');
                    return FALSE;
                }
            } else {
                //set message based on response code
                $this->db->trans_rollback();
                $this->set_error('error_code_' . $response_code);
                return FALSE;
            }
        }
        $this->db->trans_rollback();
        $this->set_error('error_webservice_unavailable');
        return FALSE;
    }

    public function get_transaction_info($transaction_id) {
        $this->db->where('transaction_id', $transaction_id);
        return $this->db->select($this->tables['user_transactions'] . '.*')
                        ->from($this->tables['user_transactions'])
                        ->get();
    }

    /*
     * This method will return user transaction list
     * @param $service_id_list, service id list of transactions
     * @param $from_date, start date in unix format
     * @param $to_date, end date in unix format
     * @param $limit, limit
     * @param $offset, offset
     * @author nazmul hasan on 24th February 2016
     */

    public function get_user_transaction_list($service_id_list = array(), $status_id_list = array(), $from_date = 0, $to_date = 0, $limit = 0, $offset = 0) {
        //run each where that was passed
        if (isset($this->_ion_where) && !empty($this->_ion_where)) {
            foreach ($this->_ion_where as $where) {
                $this->db->where($where);
            }
            $this->_ion_where = array();
        }
        if ($limit > 0) {
            $this->db->limit($limit);
        }
        if ($offset > 0) {
            $this->db->offset($offset);
        }
        if ($from_date != 0 && $to_date != 0) {
            $this->db->where($this->tables['user_transactions'] . '.created_on >=', $from_date);
            $this->db->where($this->tables['user_transactions'] . '.created_on <=', $to_date);
        }
        if (!empty($service_id_list)) {
            $this->db->where_in($this->tables['user_transactions'] . '.service_id', $service_id_list);
        }
        if (!empty($status_id_list)) {
            $this->db->where_in($this->tables['user_transactions'] . '.status_id', $status_id_list);
        }
        $this->db->order_by($this->tables['user_transactions'] . '.id', 'desc');
        return $this->db->select($this->tables['user_transactions'] . '.*,' . $this->tables['user_transaction_statuses'] . '.title as status,' . $this->tables['services'] . '.title as service_title')
                        ->from($this->tables['user_transactions'])
                        ->join($this->tables['user_transaction_statuses'], $this->tables['user_transaction_statuses'] . '.id=' . $this->tables['user_transactions'] . '.status_id')
                        ->join($this->tables['services'], $this->tables['services'] . '.id=' . $this->tables['user_transactions'] . '.service_id')
                        ->get();
    }

    /*
     * This method will return user sms transaction list
     * @param $from_date, start date in unix format
     * @param $to_date, end date in unix format
     * @param $limit, limit
     * @param $offset, offset
     * @author nazmul hasan on 10th April 2016
     */

    public function get_user_sms_transaction_list($status_id_list = array(), $from_date = 0, $to_date = 0, $limit = 0, $offset = 0) {
        //run each where that was passed
        if (isset($this->_ion_where) && !empty($this->_ion_where)) {
            foreach ($this->_ion_where as $where) {
                $this->db->where($where);
            }
            $this->_ion_where = array();
        }
        if ($limit > 0) {
            $this->db->limit($limit);
        }
        if ($offset > 0) {
            $this->db->offset($offset);
        }
        if ($from_date != 0 && $to_date != 0) {
            $this->db->where($this->tables['user_sms_transactions'] . '.created_on >=', $from_date);
            $this->db->where($this->tables['user_sms_transactions'] . '.created_on <=', $to_date);
        }
        if (!empty($status_id_list)) {
            $this->db->where_in($this->tables['user_sms_transactions'] . '.status_id', $status_id_list);
        }
        $this->db->order_by($this->tables['user_sms_transactions'] . '.id', 'desc');
        return $this->db->select($this->tables['user_sms_transactions'] . '.*,' . $this->tables['user_transaction_statuses'] . '.title as status,' . $this->tables['sms_details'] . '.sms,' . $this->tables['sms_details'] . '.length,' . $this->tables['sms_details'] . '.unit_price')
                        ->from($this->tables['user_sms_transactions'])
                        ->join($this->tables['sms_details'], $this->tables['sms_details'] . '.transaction_id=' . $this->tables['user_sms_transactions'] . '.transaction_id')
                        ->join($this->tables['user_transaction_statuses'], $this->tables['user_transaction_statuses'] . '.id=' . $this->tables['user_sms_transactions'] . '.status_id')
                        ->get();
    }

    /*
     * This method will return user transaction summary
     * @param $service_id_list, service id list of transactions
     * @param $from_date, start date in unix format
     * @param $to_date, end date in unix format
     * @author rashida on 26th April 2016
     */

    function get_user_transaction_summary($service_id_list = array(), $status_id_list = array(), $from_date = 0, $to_date = 0) {
        //run each where that was passed
        if (isset($this->_ion_where) && !empty($this->_ion_where)) {
            foreach ($this->_ion_where as $where) {
                $this->db->where($where);
            }
            $this->_ion_where = array();
        }
        if ($from_date != 0 && $to_date != 0) {
            $this->db->where($this->tables['user_transactions'] . '.created_on >=', $from_date);
            $this->db->where($this->tables['user_transactions'] . '.created_on <=', $to_date);
        }
        if (!empty($service_id_list)) {
            $this->db->where_in($this->tables['user_transactions'] . '.service_id', $service_id_list);
        }
        if (!empty($status_id_list)) {
            $this->db->where_in($this->tables['user_transactions'] . '.status_id', $status_id_list);
        }
        return $this->db->select('COUNT(*) as total_transactions, sum(amount) as total_amount')
                        ->from($this->tables['user_transactions'])
                        ->get();
    }

    /*
     * This method will return user sms transaction summary
     * @param $user_id, user id 
     * @param $from_date, start date in unix format
     * @param $to_date, end date in unix format
     * @author rashida on 27th April 2016
     */

    public function get_user_sms_transaction_summary($status_id_list = array(), $from_date = 0, $to_date = 0) {
        //run each where that was passed
        if (isset($this->_ion_where) && !empty($this->_ion_where)) {
            foreach ($this->_ion_where as $where) {
                $this->db->where($where);
            }
            $this->_ion_where = array();
        }
        if ($from_date != 0 && $to_date != 0) {
            $this->db->where($this->tables['user_sms_transactions'] . '.created_on >=', $from_date);
            $this->db->where($this->tables['user_sms_transactions'] . '.created_on <=', $to_date);
        }
        if (!empty($status_id_list)) {
            $this->db->where_in($this->tables['user_sms_transactions'] . '.status_id', $status_id_list);
        }
        return $this->db->select('COUNT(*) as total_transactions, sum(unit_price) as total_amount')
                        ->from($this->tables['user_sms_transactions'])
                        ->join($this->tables['sms_details'], $this->tables['sms_details'] . '.transaction_id=' . $this->tables['user_sms_transactions'] . '.transaction_id')
                        ->get();
    }

    /**
     * this method return payment or receive history of a user
     * @$user_id
     * @$payment_type_ids
     * return payment history
     * @author Rashida on 17 feb 2016
     */
    public function get_payment_history($user_id = 0, $payment_type_ids = array(), $limit = 0, $offset = 0, $start_date = 0, $end_date = 0) {
        $this->db->where($this->tables['user_payments'] . '.user_id', $user_id);
        $this->db->where_in($this->tables['user_payments'] . '.type_id', $payment_type_ids);
        if ($start_date != 0 && $end_date != 0) {
            $this->db->where($this->tables['user_payments'] . '.created_on >=', $start_date);
            $this->db->where($this->tables['user_payments'] . '.created_on <=', $end_date);
        }
        if ($limit > 0) {
            $this->db->limit($limit);
        }
        if ($offset > 0) {
            $this->db->offset($offset);
        }
        return $this->db->select($this->tables['user_payments'] . '.*,' . $this->tables['users'] . '.username')
                        ->from($this->tables['users'])
                        ->join($this->tables['user_payments'], $this->tables['users'] . '.id=' . $this->tables['user_payments'] . '.reference_id')
                        ->get();
    }

    /**
     * this method will return user profit
     * @$user_id
     * @$service_ids
     * return profit history
     * @author Rashida on 17 feb 2016
     */
    public function get_user_profit($user_id, $service_ids) {
        $this->db->where($this->tables['user_profits'] . '.user_id', $user_id);
        $this->db->where_in($this->tables['user_profits'] . '.service_id', $service_ids);
        $this->db->where_in($this->tables['user_profits'] . '.status_id', array(TRANSACTION_STATUS_ID_PENDING, TRANSACTION_STATUS_ID_SUCCESSFUL));
        $this->db->group_by('service_id');
        return $this->db->select($this->tables['user_profits'] . '.service_id, sum(rate) as total_used_amount, sum(amount) as total_profit,' . $this->tables['services'] . '.title')
                        ->from($this->tables['user_profits'])
                        ->join($this->tables['services'], $this->tables['user_profits'] . '.service_id=' . $this->tables['services'] . '.id')
                        ->get();
    }

    public function send_email($email, $message) {
        $this->curl->create(WEBSERVICE_URL_SEND_EMAIL);
        $this->curl->post(array("email" => $email, "message" => $message));
        $this->curl->execute();
    }

}
