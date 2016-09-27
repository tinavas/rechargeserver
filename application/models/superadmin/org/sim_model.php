<?php

class Sim_model extends Ion_auth_model {

    public function __construct() {
        parent::__construct();
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
                    foreach ($result as $simInfo) {
                        $sim_info = array();
                        $sim_info['sim_no'] = $simInfo->simNo;
                        $sim_info['description'] = $simInfo->description;
                        $sim_info['status'] = $simInfo->status;
                        $sim_list[] = $sim_info;
                    }
                }
            }
        }
        return $sim_list;
    }

    /*
     * This method will call authentication server to add a new sim with services
     * @param $additional_data, sim info to be added
     * @param $service_info_list, sim service info list
     * @author nazmul hasan on 11th June 2016
     */

    public function add_sim($additional_data, $service_info_list) {
        $this->curl->create(WEBSERVICE_ADD_SIM);
        $this->curl->post(array('sim_no' => $additional_data['sim_no'], 'identifier' => $additional_data['identifier'], 'description' => $additional_data['description'], 'status' => $additional_data['status'], 'sim_service_list' => json_encode($service_info_list)));
        return json_decode($this->curl->execute());
    }

    /*
     * This method will call authentication server to return sim info
     * @param $sim_no, sim number
     * @author nazmul hasan on 11th June 2016
     */

    public function get_sim_info($sim_no) {
        $sim_info = array();
        $this->curl->create(WEBSERVICE_GET_SIM_SERVICE_INFO);
        $this->curl->post(array("sim_no" => $sim_no));
        $result_event = json_decode($this->curl->execute());
        if (!empty($result_event)) {
            $response_code = '';
            if (property_exists($result_event, "responseCode") != FALSE) {
                $response_code = $result_event->responseCode;
            }
            if ($response_code == RESPONSE_CODE_SUCCESS) {
                if (property_exists($result_event, "result") != FALSE) {
                    $result = $result_event->result;
                    $sim_info['simNo'] = $result->simNo;
                    $sim_info['identifier'] = $result->identifier;
                    $sim_info['description'] = $result->description;
                    $sim_info['status'] = $result->status;
                    $sim_service_list = array();

                    $this->load->library('superadmin/org/super_utils');
                    $simServiceList = $result->simServiceList;
                    foreach ($simServiceList as $simServiceInfo) {
                        $simServiceObj = new stdClass();
                        $simServiceObj->serviceId = $simServiceInfo->id;
                        $simServiceObj->categoryId = $simServiceInfo->categoryId;
                        $simServiceObj->currentBalance = $simServiceInfo->currentBalance;
                        $simServiceObj->modifiedOn = $this->super_utils->get_auth_unix_to_human_date($simServiceInfo->modifiedOn);
                        $sim_service_list[] = $simServiceObj;
                    }
                    $sim_info['simServiceList'] = $sim_service_list;
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
        $result_event = json_decode($this->curl->execute());
        if (!empty($result_event)) {
            $response_code = '';
            if (property_exists($result_event, "responseCode") != FALSE) {
                $response_code = $result_event->responseCode;
            }
            if ($response_code == RESPONSE_CODE_SUCCESS) {
                return TRUE;
            } else {
                $this->set_error('error_code_' . $response_code);
                return FALSE;
            }
        } else {
            $this->set_error('error_webservice_unavailable');
            return FALSE;
        }
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

    /*
     * This method will return sim sms list from authentication server
     * @param $sim_no
     * @param $start_time
     * @param $end_time
     * @param $offset
     * @param $limit
     * @author nazmul hasan on 18th september 2016
     */

    public function get_sms_list($sim_no, $start_time, $end_time, $offset = 0, $limit = 0) {
        $sms_info_list['sms_list'] = array();
        $sms_info_list['total_counter'] = 0;
        $this->curl->create(WEBSERVICE_GET_SMS_LIST);
        $this->curl->post(array("sim_no" => $sim_no, "offset" => $offset, "limit" => $limit, "start_time" => $start_time, "end_time" => $end_time));
        $result_event = json_decode($this->curl->execute());
        if (!empty($result_event)) {
            $response_code = '';
            if (property_exists($result_event, "responseCode") != FALSE) {
                $response_code = $result_event->responseCode;
            }
            if ($response_code == RESPONSE_CODE_SUCCESS) {
                if (property_exists($result_event, "result") != FALSE) {
                    $this->load->library('superadmin/org/Super_utils');
                    $result = $result_event->result;
                    $sim_sms_list = array();
                    $counter = 0;
                    if (property_exists($result, "counter") != FALSE && property_exists($result, "simSMSList") != FALSE) {
                        $counter = $result->counter;
                        foreach ($result->simSMSList as $sim_sms_info) {
                            //reducing 2 hours from auth server and converting to human date format from unix format
                            $sim_sms_info->createdOn = $this->super_utils->get_auth_unix_to_human_date($sim_sms_info->createdOn);
                            $sim_sms_list[] = $sim_sms_info;
                        }
                    }
                    $sms_info_list['sms_list'] = $sim_sms_list;
                    $sms_info_list['total_counter'] = $counter;
                }
            }
        }
        return $sms_info_list;
    }

}
