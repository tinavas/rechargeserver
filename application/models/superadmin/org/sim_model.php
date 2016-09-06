<?php

class Sim_model extends Ion_auth_model {

    public function __construct() {
        parent::__construct();
    }

    /*
     * This method will call authentication server to add a new sim
     * @param $additional_data, sim info to be added
     * @author nazmul hasan on 11th June 2016
     */

    public function add_sim($additional_data, $service_info_list) {
        $this->curl->create(WEBSERVICE_ADD_SIM);
        $this->curl->post(array('sim_no' => $additional_data['sim_no'], 'identifier' => $additional_data['identifier'], 'description' => $additional_data['description'], 'status' => $additional_data['status'], 'sim_service_list' => json_encode($service_info_list)));
        return json_decode($this->curl->execute());
    }

    /*
     * This method will call authentication server to return entire sim list
     * @author nazmul hasan on 11th June 2016
     */

    public function get_sim_list() {
        $sim_list = array();
        $this->curl->create(WEBSERVICE_GET_ALL_SIMS);
        $this->curl->post(array("identifier" => LOCAL_SERVER_IDENTIFIER));
        $result_event = json_decode($this->curl->execute());
        if (!empty($result_event)) {
            $response_code = '';
            if (property_exists($result_event, "responseCode") != FALSE) {
                $response_code = $result_event->responseCode;
            }
            if ($response_code == RESPONSE_CODE_SUCCESS) {
                if (property_exists($result_event, "result") != FALSE) {
                    $result = $result_event->result;
                    $this->load->library('date_utils');
                    foreach ($result as $simInfo) {
                        $sim_info = array();
                        $sim_info['sim_no'] = $simInfo->simNo;
                        $sim_info['identifier'] = $simInfo->identifier;
                        $sim_info['description'] = $simInfo->description;
                        $sim_info['status'] = $simInfo->status;
                        $sim_info['modified_on'] = $this->date_utils->get_unix_to_display($simInfo->simServiceList[0]->modifiedOn);
                        $sim_list[] = $sim_info;
                    }
                }
            }
        }
        return $sim_list;
    }

    /*
     * This method will call authentication server to return sim info
     * @param $sim_no, sim number
     * @author nazmul hasan on 11th June 2016
     */

    public function get_sim_info($sim_no) {
        $sim_info = array();
        $this->curl->create(WEBSERVICE_GET_SIM_INFO);
        $this->curl->post(array("sim_no" => $sim_no));
        $result_event = json_decode($this->curl->execute());
        if (!empty($result_event)) {
            $response_code = '';
            if (property_exists($result_event, "responseCode") != FALSE) {
                $response_code = $result_event->responseCode;
            }
            if ($response_code == RESPONSE_CODE_SUCCESS) {
                if (property_exists($result_event, "result") != FALSE) {
                    // start test data added when $result->simServiceList is dynamic then remove this
                    $simServiceList = array();
                    $obj = new stdClass();
                    $obj->serviceId = SERVICE_TYPE_ID_BKASH_CASHIN;
                    $obj->categoryId = SIM_CATEGORY_TYPE_AGENT;
                    $obj->currentBalance = 5000;
                    $obj1 = new stdClass();
                    $obj1->serviceId = SERVICE_TYPE_ID_UCASH_CASHIN;
                    $obj1->categoryId = SIM_CATEGORY_TYPE_PERSONAL;
                    $obj1->currentBalance = 1000;



                    $simServiceList[] = $obj;
                    $simServiceList[] = $obj1;
                    // end 
                    $result = $result_event->result;
                    $sim_info['simNo'] = $result->simNo;
                    $sim_info['identifier'] = $result->identifier;
                    $sim_info['description'] = $result->description;
                    $sim_info['status'] = $result->status;
//                    $sim_info['simServiceList'] = $result->simServiceList;
                    $sim_info['simServiceList'] = $simServiceList;
                }
            }
        }
        return $sim_info;
    }

    /*
     * This method will call authentication server to update sim info
     * @param $additional_data, sim data to be updated
     * @author nazmul hasan on 11th June 2016
     */

    public function edit_sim($additional_data, $service_info_list) {
        $this->curl->create(WEBSERVICE_EDIT_SIM);
        $this->curl->post(array('sim_no' => $additional_data['sim_no'], 'identifier' => $additional_data['identifier'], 'description' => $additional_data['description'], 'status' => $additional_data['status'], 'simServiceList' => json_encode($service_info_list)));
        return json_decode($this->curl->execute());
    }

    public function check_sim_balance($sim_no) {
        $this->curl->create(WEBSERVICE_CHECK_SIM_BALANCE);
        $this->curl->post(array('sim_no' => $sim_no));
        return json_decode($this->curl->execute());
    }

    public function add_service_balance($service_balance_info) {
        $this->curl->create(WEBSERVICE_ADD_SIM_INFORMATION);
        $this->curl->post(array("service_balance_info" => json_encode($service_balance_info)));
        return json_decode($this->curl->execute());
    }

    public function get_sim_service_list($service_balance_info) {
        $this->curl->create(WEBSERVICE_ADD_SIM_INFORMATION);
        $this->curl->post(array("service_balance_info" => json_encode($service_balance_info)));
        return json_decode($this->curl->execute());
    }

    public function get_sim_transactions($start_date = 0, $end_date = 0) {
        $this->curl->create(WEBSERVICE_GET_SIM_TRANSACTION_LIST);
        $this->curl->post(array("start_date" => $start_date, "end_date" => $end_date));
        return json_decode($this->curl->execute());
    }

    public function get_sim_category_list() {
        $obj = new stdClass();
        $obj->id = SIM_CATEGORY_TYPE_AGENT;
        $obj->title = "Agent";
        $obj->selected = true;
        $obj1 = new stdClass();
        $obj1->id = SIM_CATEGORY_TYPE_PERSONAL;
        $obj1->title = "Personal";
        $obj1->selected = false;

        $sim_category_list = array(
        );
        $sim_category_list[] = $obj;
        $sim_category_list[] = $obj1;
        return $sim_category_list;
    }

    public function get_sim_status_list() {
        $obj = new stdClass();
        $obj->id = SIM_STATUS_ENABLE;
        $obj->title = "Enable";
        $obj->selected = true;
        $obj1 = new stdClass();
        $obj1->id = SIM_STATUS_DISABLE;
        $obj1->title = "Disable";
        $obj1->selected = false;

        $sim_status_list = array(
        );
        $sim_status_list[] = $obj;
        $sim_status_list[] = $obj1;
        return $sim_status_list;
    }

    public function get_sms_list($sim_no = 0, $offset = 0, $limit = 0, $from_date = 0, $to_date = 0) {
        $sms_info_list = array();
        //uncommet while work within
//        $this->curl->create(WEBSERVICE_GET_SMS_LIST);
//        $this->curl->post(array("simNo" => $sim_no, "offset" => $offset, "limit" => $limit, "from_date" => $from_date, "to_date" => $to_date));
//        $result_event = json_decode($this->curl->execute());
//        if (!empty($result_event)) {
//            $response_code = '';
//            if (property_exists($result_event, "responseCode") != FALSE) {
//                $response_code = $result_event->responseCode;
//            }
//            if ($response_code == RESPONSE_CODE_SUCCESS) {
//                if (property_exists($result_event, "result") != FALSE) {
                    //discard while dynamic
                    $obj = new stdClass();
                    $obj->id = "1234";
                    $obj->simNo = "01723598606";
                    $obj->sender = "01678112509";
                    $obj->sms = "Transaction successful! your tansaction id is 0123456789";
                    $obj->createdOn = "2016-09-06";
                    $obj1 = new stdClass();
                    $obj1->id = "12345";
                    $obj1->simNo = "01712341213";
                    $obj1->sender = "01678112509";
                    $obj1->sms = "Transaction successful! your tansaction id is 0123456789";
                    $obj1->createdOn = "2016-09-05";
                    $sms_list = array(
                    );
                    $sms_list[] = $obj;
                    $sms_list[] = $obj1;
                    // end 
//                    $result = $result_event->result;
//                    $smsList = $result->smsList;
//                    $sms_counter = $result->totalCounter;
//                   //
                    $sms_info_list['sms_list'] = $sms_list;
                    $sms_info_list['total_counter'] = 10;
//                }
//            }
//        }
        return $sms_info_list;
    }

}
