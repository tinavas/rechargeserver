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
    public function add_sim($additional_data)
    {        
        $this->curl->create(WEBSERVICE_ADD_SIM);
        $this->curl->post(array('sim_no'=>$additional_data['sim_no'], 'identifier'=>$additional_data['identifier'], 'description'=>$additional_data['description'],'current_balance'=>$additional_data['current_balance'],'status'=>$additional_data['status']));
        return json_decode($this->curl->execute());
    }
    
    /*
     * This method will call authentication server to return entire sim list
     * @author nazmul hasan on 11th June 2016
     */
    public function get_sim_list()
    {
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
                    foreach ($result as $simInfo) 
                    {
                        $sim_info = array();
                        $sim_info['sim_no'] = $simInfo->simNo;
                        $sim_info['identifier'] = $simInfo->identifier;
                        $sim_info['description'] = $simInfo->description;
                        $sim_info['status'] = $simInfo->status;
                        $sim_info['current_balance'] = $simInfo->simServiceList[0]->currentBalance;
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
    public function get_sim_info($sim_no)
    {
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
                    $result = $result_event->result;
                    $sim_info['sim_no'] = $result->simNo;
                    $sim_info['identifier'] = $result->identifier;
                    $sim_info['description'] = $result->description;
                    $sim_info['status'] = $result->status;
                    $sim_info['current_balance'] = $result->simServiceList[0]->currentBalance;
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
    public function edit_sim($additional_data)
    {
        $this->curl->create(WEBSERVICE_EDIT_SIM);
        $this->curl->post(array('sim_no'=>$additional_data['sim_no'], 'identifier'=>$additional_data['identifier'], 'description'=>$additional_data['description'],'current_balance'=>$additional_data['current_balance'],'status'=>$additional_data['status']));
        return json_decode($this->curl->execute());
    }
    
    public function check_sim_balance($sim_no)
    {
        $this->curl->create(WEBSERVICE_CHECK_SIM_BALANCE);
        $this->curl->post(array('sim_no' => $sim_no));
        return json_decode($this->curl->execute());
    }
    
    
//     public function get_sim_list() {
//        $this->curl->create(WEBSERVICE_ADD_SIM_INFORMATION);
//        return json_decode($this->curl->execute());
//    }

//    public function add_sim($sim_info) {
//        $this->curl->create(WEBSERVICE_ADD_SIM_INFORMATION);
//        $this->curl->post(array("sim_info" => json_encode($sim_info)));
//        return json_decode($this->curl->execute());
//    }
//
//    public function edit_sim($sim_info) {
//        $this->curl->create(WEBSERVICE_EDIT_SIM_INFORMATION);
//        $this->curl->post(array("sim_info" => json_encode($sim_info)));
//        return json_decode($this->curl->execute());
//    }

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

}
