<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reseller extends Role_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->library('ion_auth');
        $this->load->model('service_model');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    public function index() {
        $this->data['message'] = "";
        $user_id = $this->session->userdata('user_id');
        $this->load->library('reseller_library');
        $maximum_children = $this->reseller_library->get_maximum_children();
        $reseller_list = array();
        $current_children = 0;
        if ($maximum_children > 0) {
            $reseller_list = $this->reseller_library->get_reseller_list($user_id);
            $current_children = count($reseller_list);
        }
        $this->data['reseller_list'] = json_encode($reseller_list);
        if ($current_children < $maximum_children) {
            $this->data['allow_user_create'] = TRUE;
        } else {
            $this->data['allow_user_create'] = FALSE;
        }
        $group = $this->session->userdata('group');
        $successor_group_title = $this->config->item('successor_group_title', 'ion_auth');
        $title = $successor_group_title[$group];
        $this->data['title'] = $title;
        $this->data['group'] = $group;
        $this->template->load(null, 'reseller/index', $this->data);
    }

    /*
     * This method will create a new reseller
     */

    public function create_reseller() {
        $this->load->library('utils');
        $this->load->model('service_model');
        $user_is_admin = FALSE;
        $service_list = $this->reseller_model->get_user_services($this->session->userdata('user_id'))->result_array();
        $response = array();
        if (file_get_contents("php://input") != null) {
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "resellerInfo") != FALSE) {
                $resellerInfo = $requestInfo->resellerInfo;
                if (property_exists($resellerInfo, "username")) {
                    $username = $resellerInfo->username;
                }
                if (property_exists($resellerInfo, "password")) {
                    $password = $resellerInfo->password;
                }
                if (property_exists($resellerInfo, "email")) {
                    $email = $resellerInfo->email;
                }
                if (property_exists($resellerInfo, "max_user_no")) {
                    $max_user_no = $resellerInfo->max_user_no;
                }
                if (property_exists($resellerInfo, "mobile")) {
                    $cell_no = $resellerInfo->mobile;
                }

                if ($this->utils->cell_number_validation($cell_no) == FALSE) {
                    $response["message"] = "Please Enter a Valid Cell Number !!";
                    echo json_encode($response);
                    return;
                }
                $selected_service_id_list = $resellerInfo->selected_service_id_list;
                $user_service_list = array();
                foreach ($service_list as $service_info) {
                    $user_service_info = array(
                        'service_id' => $service_info['service_id']
                    );
                    if (in_array($service_info['service_id'], $selected_service_id_list)) {
                        $user_service_info['status'] = 1;
                    } else {
                        $user_service_info['status'] = 0;
                    }
                    $user_service_list[] = $user_service_info;
                }
                $additional_data = array(
                    'first_name' => $resellerInfo->first_name,
                    'last_name' => $resellerInfo->last_name,
                    'mobile' => $cell_no,
                    'note' => $resellerInfo->note,
                    'max_user_no' => $max_user_no,
                    'parent_user_id' => $this->session->userdata('user_id'),
                    'user_service_list' => $user_service_list
                );
            }
            $group = $this->session->userdata('group');
            $group_successor_config = $this->config->item('successor_group_id', 'ion_auth');
            $group_ids = array(
                $group_successor_config[$group]
            );
            $user_id = $this->ion_auth->register($username, $password, $email, $additional_data, $group_ids);
            if ($user_id !== FALSE) {
                $response['message'] = 'User is created successfully.';
            } else {
                $response['message'] = 'Error while creating the user. Please try again later.';
            }

            echo json_encode($response);
            return;
        }

        $this->data['message'] = "";
        $group = $this->session->userdata('group');
        $successor_group_title = $this->config->item('successor_group_title', 'ion_auth');
        $title = $successor_group_title[$group];
        $this->data['title'] = $title;
        $this->data['service_list'] = json_encode($service_list);
        $this->template->load(null, 'reseller/create_reseller', $this->data);
    }

    public function update_reseller($user_id = 0) {
        $this->load->library('utils');
        $this->load->model('service_model');
        $service_list = $this->reseller_model->get_user_services($this->session->userdata('user_id'))->result_array();
        $response = array();
        if (file_get_contents("php://input") != null) {
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            if (property_exists($requestInfo, "resellerInfo") != FALSE) {
                $resellerInfo = $requestInfo->resellerInfo;
                if (property_exists($resellerInfo, "username")) {
                    $username = $resellerInfo->username;
                }
                if (property_exists($resellerInfo, "password")) {
                    $password = $resellerInfo->password;
                }
                if (property_exists($resellerInfo, "email")) {
                    $email = $resellerInfo->email;
                }
                if (property_exists($resellerInfo, "max_user_no")) {
                    $max_user_no = $resellerInfo->max_user_no;
                }
                if (property_exists($resellerInfo, "user_id")) {
                    $user_id = $resellerInfo->user_id;
                }
                if (property_exists($resellerInfo, "mobile")) {
                    $cell_no = $resellerInfo->mobile;
                }

                if ($this->utils->cell_number_validation($cell_no) == FALSE) {
                    $response["message"] = "Please Enter a Valid Cell Number !!";
                    echo json_encode($response);
                    return;
                }
                $selected_service_id_list = $resellerInfo->selected_service_id_list;
                $user_service_list = array();
                foreach ($service_list as $service_info) {
                    $user_service_info = array(
                        'service_id' => $service_info['service_id']
                    );
                    if (in_array($service_info['service_id'], $selected_service_id_list)) {
                        $user_service_info['status'] = 1;
                    } else {
                        $user_service_info['status'] = 0;
                    }
                    $user_service_list[] = $user_service_info;
                }
                $additional_data = array(
                    'first_name' => $resellerInfo->first_name,
                    'last_name' => $resellerInfo->last_name,
                    'mobile' => $cell_no,
                    'note' => $resellerInfo->note,
                    'max_user_no' => $max_user_no,
                    'user_service_list' => $user_service_list
                );
            }
            if ($this->ion_auth->update($user_id, $additional_data) !== FALSE) {
                $response['message'] = 'User is updated successfully.';
            } else {
                $response['message'] = 'Error while updating the user. Please try again later.';
            }

            echo json_encode($response);
            return;
        }
        $this->load->model('reseller_model');
        $reseller_info = $this->reseller_model->get_reseller_info($user_id)->result_array();
        if (!empty($reseller_info)) {
            $this->data['reseller_info'] = json_encode($reseller_info[0]);
        }

        $user_available_services = array();
        $child_services = $this->reseller_model->get_user_services($user_id)->result_array();
        foreach ($service_list as $user_service) {
            $user_service['selected'] = FALSE;
            foreach ($child_services as $service) {
                if ($user_service['service_id'] == $service['service_id']) {
                    $user_service['selected'] = TRUE;
                }
            }
            $user_available_services[] = $user_service;
        }
        $this->data['service_list'] = json_encode($user_available_services);
        $group = $this->session->userdata('group');
        $successor_group_title = $this->config->item('successor_group_title', 'ion_auth');
        $title = $successor_group_title[$group];
        $this->data['title'] = $title;
        $this->template->load(null, 'reseller/update_reseller', $this->data);
    }

    public function update_rate($user_id) {
        $this->data['message'] = "";
        $this->load->model('reseller_model');
        if ($this->input->post('submit_update_rate')) {
            $new_rate_list = array();
            foreach ($this->input->post('update') as $rate_info) {
                if (array_key_exists("enable", $rate_info)) {
                    $new_rate_info = array(
                        'id' => $rate_info['key'],
                        'rate' => $rate_info['rate'],
                        'commission' => $rate_info['commission'],
                        'charge' => $rate_info['charge']
                    );
                    $new_rate_list[] = $new_rate_info;
                }
            }
            if (!empty($new_rate_list)) {
                $this->reseller_model->update_reseller_rates($new_rate_list);
            }
        }

        $rate_list = $this->reseller_model->get_user_services($user_id)->result_array();
        $this->data['rate_list'] = $rate_list;
        $this->data['user_id'] = $user_id;
        $this->template->load(null, 'reseller/update_rate', $this->data);
    }

   
}
