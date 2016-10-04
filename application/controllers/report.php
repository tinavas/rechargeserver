<?php

class Report extends Role_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    public function index() {
        
    }

    /*
     * This method will display profit of services
     * @author nazmul hasan on 3rd march 2016
     */

    public function get_cost_and_profit() {
        $user_id = $this->session->userdata('user_id');
        $this->load->model('cost_profit_model');
        $user_profits = $this->cost_profit_model->get_user_service_profits($user_id)->result_array();
        $this->data['user_profits'] = $user_profits;
        $this->data['app'] = REPORT_APP;
        $this->template->load(null, 'report/cost_and_profit', $this->data);
    }

    public function get_balance_report() {

        $this->data['app'] = REPORT_APP;
        $this->template->load(null, 'report/balance_report', $this->data);
    }

    public function get_detailed_report() {
        $this->load->library('cost_profit_library');
        $user_id = $this->session->userdata('user_id');
        $where = array(
            'user_id' => $user_id
        );
        $start_date = 0;
        $end_date = 0;
        $limit = TRANSACTION_PAGE_DEFAULT_LIMIT;
        $offset = TRANSACTION_PAGE_DEFAULT_OFFSET;
        if (file_get_contents("php://input") != null) {
            $response = array();
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "searchInfo") != FALSE) {
                $search_param = $requestInfo->searchInfo;
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
                    if ($status_id != SELECT_ALL_STATUSES_TRANSACTIONS) {
                        $status_id_list = array($status_id);
                    } else {
                        $status_id_list = array();
                    }
                }
                if (property_exists($search_param, "serviceId") != FALSE) {
                    $service_id = $search_param->serviceId;
                    if ($service_id != SELECT_ALL_SERVICES_TRANSACTIONS) {
                        $service_id_list = array($service_id);
                    } else {
                        $service_id_list = array();
                    }
                }
            }
            $report_information = $this->cost_profit_library->get_report_detail_history($status_id_list, $service_id_list, $start_date, $end_date, $limit, $offset, $where);
            if (!empty($report_information)) {
                $response['report_list'] = $report_information['report_list'];
                $response['report_summary'] = $report_information['report_summary'];
            }
            echo json_encode($response);
            return;
        }



        $report_list = array();
        $report_summary = array();
        $report_information = $this->cost_profit_library->get_report_detail_history(array(), array(), $start_date, $end_date, $limit, $offset, $where);

        if (!empty($report_information)) {
            $report_list = $report_information['report_list'];
            $report_summary = $report_information['report_summary'];
        }
        $this->data['report_list'] = json_encode($report_list);
        $this->data['report_summary'] = json_encode($report_summary);
        $this->load->library('transaction_library');
        $transction_status_list = $this->transaction_library->get_user_transaction_statuses();
        $this->data['transction_status_list'] = $transction_status_list;
        $this->load->model('service_model');
        $service_list = array();
        $service_all = array(
            "id" => SELECT_ALL_SERVICES_TRANSACTIONS,
            "title" => "All"
        );
        $service_list[] = $service_all;
        $service_list_array = $this->service_model->get_user_all_services($user_id)->result_array();
        if (!empty($service_list_array)) {
            foreach ($service_list_array as $service_info) {
                $service_list[] = $service_info;
            }
        }
        $this->data['service_list'] = $service_list;
        $this->load->library('Date_utils');
        $current_date = $this->date_utils->get_current_date();
        $this->data['current_date'] = $current_date;
        $this->data['app'] = REPORT_APP;
        $this->template->load(null, 'report/detailed_report', $this->data);
    }

    public function get_user_profit_loss() {
        $this->load->library('cost_profit_library');
        $user_id = $this->session->userdata('user_id');
        $where = array(
            'user_id' => $user_id
        );
        $start_date = 0;
        $end_date = 0;
        $limit = TRANSACTION_PAGE_DEFAULT_LIMIT;
        $offset = TRANSACTION_PAGE_DEFAULT_OFFSET;
        if (file_get_contents("php://input") != null) {
            $response = array();
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "searchInfo") != FALSE) {
                $search_param = $requestInfo->searchInfo;
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
                if (property_exists($search_param, "serviceId") != FALSE) {
                    $service_id = $search_param->serviceId;
                    if ($service_id != SELECT_ALL_SERVICES_TRANSACTIONS) {
                        $service_id_list = array($service_id);
                    } else {
                        $service_id_list = array();
                    }
                }
            }
            $report_information = $this->cost_profit_library->get_report_detail_history(array(), $service_id_list, $start_date, $end_date, $limit, $offset, $where);
            if (!empty($report_information)) {
                $response['report_list'] = $report_information['report_list'];
                $response['report_summary'] = $report_information['report_summary'];
            }
            echo json_encode($response);
            return;
        }
        $report_list = array();
        $report_summary = array();
        $report_information = $this->cost_profit_library->get_user_profit_loss(array(), array(), $start_date, $end_date, $limit, $offset, $where);
        if (!empty($report_information)) {
            $report_list = $report_information['report_list'];
            $report_summary = $report_information['report_summary'];
        }
        $this->data['report_list'] = json_encode($report_list);
        $this->data['report_summary'] = json_encode($report_summary);
        $this->load->model('service_model');
        $service_list = array();
        $service_all = array(
            "id" => SELECT_ALL_SERVICES_TRANSACTIONS,
            "title" => "All"
        );
        $service_list[] = $service_all;
        $service_list_array = $this->service_model->get_user_all_services($user_id)->result_array();
        if (!empty($service_list_array)) {
            foreach ($service_list_array as $service_info) {
                $service_list[] = $service_info;
            }
        }
        $this->data['service_list'] = $service_list;
        $this->load->library('Date_utils');
        $current_date = $this->date_utils->get_current_date();
        $this->data['current_date'] = $current_date;
        $this->data['app'] = REPORT_APP;
        $this->template->load(null, 'report/profit_loss_analysis', $this->data);
    }

    public function get_total_report() {

        $this->load->library('cost_profit_library');
        $user_id = $this->session->userdata('user_id');
        $where = array(
            'user_id' => $user_id
        );
        $start_date = 0;
        $end_date = 0;
        $limit = TRANSACTION_PAGE_DEFAULT_LIMIT;
        $offset = TRANSACTION_PAGE_DEFAULT_OFFSET;
        if (file_get_contents("php://input") != null) {
            $response = array();
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "searchInfo") != FALSE) {
                $search_param = $requestInfo->searchInfo;
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
                    if ($status_id != SELECT_ALL_STATUSES_TRANSACTIONS) {
                        $status_id_list = array($status_id);
                    } else {
                        $status_id_list = array();
                    }
                }
                if (property_exists($search_param, "serviceId") != FALSE) {
                    $service_id = $search_param->serviceId;
                    if ($service_id != SELECT_ALL_SERVICES_TRANSACTIONS) {
                        $service_id_list = array($service_id);
                    } else {
                        $service_id_list = array();
                    }
                }
            }
            $profit_list_array = $this->cost_profit_library->get_profit_history($status_id_list, $service_id_list, $start_date, $end_date, $limit, $offset, $where);
            if (!empty($profit_list_array)) {
                $response['total_transactions'] = $profit_list_array['total_transactions'];
                $response['profit_list'] = $profit_list_array['profit_list'];
            }
            echo json_encode($response);
            return;
        }



        $total_transactions = 0;
        $profit_list = array();
        $profit_list_array = $this->cost_profit_library->get_profit_history(array(), array(), $start_date, $end_date, $limit, $offset, $where);
        if (!empty($profit_list_array)) {
            $total_transactions = $profit_list_array['total_transactions'];
            $profit_list = $profit_list_array['profit_list'];
        }
        $this->data['profit_list'] = json_encode($profit_list);
        $this->data['total_transactions'] = $total_transactions;
        $this->load->library('transaction_library');
        $transction_status_list = $this->transaction_library->get_user_transaction_statuses();
        $this->data['transction_status_list'] = $transction_status_list;
        $this->load->model('service_model');
        $service_list = array();
        $service_all = array(
            "id" => SELECT_ALL_SERVICES_TRANSACTIONS,
            "title" => "All"
        );
        $service_list[] = $service_all;
        $service_list_array = $this->service_model->get_user_all_services($user_id)->result_array();
        if (!empty($service_list_array)) {
            foreach ($service_list_array as $service_info) {
                $service_list[] = $service_info;
            }
        }
        $this->data['service_list'] = $service_list;
        $this->load->library('Date_utils');
        $current_date = $this->date_utils->get_current_date();
        $this->data['current_date'] = $current_date;
        $this->data['app'] = REPORT_APP;
        $this->template->load(null, 'report/total_report', $this->data);
    }

}

?>