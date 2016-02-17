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
//        $this->data['message'] = "";
//        $this->load->library('reseller_library');
//        $allow_user_create = FALSE;
//        if ($child_user_id == 0) {
//            $user_id = $this->session->userdata('user_id');
//            $maximum_children = $this->reseller_library->get_maximum_children();
//            $reseller_list = array();
//            $current_children = 0;
//            if ($maximum_children > 0) {
//                $reseller_list = $this->reseller_library->get_reseller_list($user_id);
//                $current_children = count($reseller_list);
//            }
//            if ($current_children < $maximum_children) {
//                $allow_user_create = TRUE;
//            }
//        } else {
//            $reseller_list = $this->reseller_library->get_reseller_list($child_user_id);
//        }
//        $this->data['allow_user_create'] = $allow_user_create;
//        $this->data['reseller_list'] = json_encode($reseller_list);
//        $group = $this->session->userdata('group');
//        $successor_group_title = $this->config->item('successor_group_title', 'ion_auth');
//        $title = $successor_group_title[$group];
//        $this->data['title'] = $title;
//        $this->data['group'] = $group;
//        $this->data['app'] = RESELLER_APP;
//        $this->template->load(null, 'reseller/index', $this->data);
    }
    
  
    function get_reseller_list($parent_user_id = 0) {
        $this->data['message'] = "";
        $this->load->library('reseller_library');
        $allow_user_create = FALSE;
        $allow_user_edit = FALSE;
        $user_id = $this->session->userdata('user_id');
        $group = $this->session->userdata('group');
        if ($parent_user_id == 0 && $parent_user_id != $user_id) {
            $maximum_children = $this->reseller_library->get_maximum_children();
            $reseller_list = array();
            $current_children = 0;
            if ($maximum_children > 0) {
                $reseller_list = $this->reseller_library->get_reseller_list($user_id);
                $current_children = count($reseller_list);
            }
            if ($current_children < $maximum_children) {
                $allow_user_create = TRUE;
            }
            $allow_user_edit = TRUE;
            $user_group_name = $group;
        } else {
            $reseller_list = $this->reseller_library->get_reseller_list($parent_user_id);
            $child_group_array = $this->reseller_model->get_users_groups($parent_user_id)->result_array();
            if (!empty($child_group_array)) {
                $user_group_name = $child_group_array[0]['name'];
            }
        }
        $successor_group_title = $this->config->item('successor_group_title', 'ion_auth');
        $title = $successor_group_title[$user_group_name];
        $this->data['allow_user_create'] = $allow_user_create;
        $this->data['allow_user_edit'] = $allow_user_edit;
        $this->data['reseller_list'] = json_encode($reseller_list);
        $this->data['title'] = $title;
        $this->data['group'] = $group;
        $this->data['app'] = RESELLER_APP;
        $this->template->load(null, 'reseller/index', $this->data);
    }

    function get_user_service_list() {
        $user_id = $this->session->userdata('user_id');
        $response = array();
        $topup_service_allow_flag = FALSE;
        $service_list = $this->service_model->get_user_services($user_id)->result_array();
        if (!empty($service_list)) {
            foreach ($service_list as $service) {
                if ($service['service_id'] == SERVICE_TYPE_ID_TOPUP_GP || $service['service_id'] == SERVICE_TYPE_ID_TOPUP_ROBI || $service['service_id'] == SERVICE_TYPE_ID_TOPUP_BANGLALINK || $service['service_id'] == SERVICE_TYPE_ID_TOPUP_BANGLALINK || $service['service_id'] == SERVICE_TYPE_ID_TOPUP_AIRTEL) {
                    $topup_service_allow_flag = TRUE;
                }
            }
        }
        $response['service_list'] = $service_list;
        $response['topup_service_allow_flag'] = $topup_service_allow_flag;
        echo json_encode($response);
    }

    /*
     * This method will create a new reseller
     */

    public function create_reseller() {
        $this->load->library('utils');
        $user_is_admin = FALSE;
        $service_list = $this->service_model->get_user_all_services($this->session->userdata('user_id'))->result_array();
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
                        'service_id' => $service_info['service_id'],
                        'rate' => $service_info['rate'],
                        'commission' => $service_info['commission']
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
        $this->data['app'] = RESELLER_APP;
        $this->template->load(null, 'reseller/create_reseller', $this->data);
    }

    public function update_reseller($user_id = 0) {
       
        $this->load->library('utils');
        $service_list = $this->service_model->get_user_services($this->session->userdata('user_id'))->result_array();
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
                $reseller_inactive_service_List = array();
                foreach ($service_list as $service_info) {
                    $user_service_info = array(
                        'service_id' => $service_info['service_id']
                    );
                    if (in_array($service_info['service_id'], $selected_service_id_list)) {
                        $user_service_info['status'] = 1;
                    } else {
                        $user_service_info['status'] = 0;
                        $reseller_inactive_service_List[] = $service_info['service_id'];
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
                $child_id_list = $this->reseller_library->get_bfs_user_id_list($user_id);
                $result = $this->reseller_model->update_reseller_services($child_id_list, $reseller_inactive_service_List);
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
        $child_services = $this->service_model->get_user_services($user_id)->result_array();
        foreach ($service_list as $user_service) {
            $user_service['selected'] = FALSE;
            foreach ($child_services as $service) {
                if ($user_service['service_id'] == $service['service_id']) {
                    $user_service['selected'] = TRUE;
                }
            }
            $user_available_services[] = $user_service;
        }
        $this->data['allow_user_edit'] = TRUE;
        $this->data['service_list'] = json_encode($user_available_services);
        $group = $this->session->userdata('group');
        $successor_group_title = $this->config->item('successor_group_title', 'ion_auth');
        $title = $successor_group_title[$group];
        $this->data['title'] = $title;
        $this->data['app'] = RESELLER_APP;
        $this->template->load(null, 'reseller/update_reseller', $this->data);
    }

    public function show_reseller($user_id = 0) {
        $this->load->library('utils');
        $service_list = $this->service_model->get_user_services($this->session->userdata('user_id'))->result_array();
        $this->load->model('reseller_model');
        $reseller_info = $this->reseller_model->get_reseller_info($user_id)->result_array();
        if (!empty($reseller_info)) {
            $this->data['reseller_info'] = json_encode($reseller_info[0]);
        }
        $user_available_services = array();
        $child_services = $this->service_model->get_user_services($user_id)->result_array();
        foreach ($service_list as $user_service) {
            $user_service['selected'] = FALSE;
            foreach ($child_services as $service) {
                if ($user_service['service_id'] == $service['service_id']) {
                    $user_service['selected'] = TRUE;
                }
            }
            $user_available_services[] = $user_service;
        }
        $this->data['allow_user_edit'] = FALSE;
        $this->data['service_list'] = json_encode($user_available_services);
        $group = $this->session->userdata('group');
        $successor_group_title = $this->config->item('successor_group_title', 'ion_auth');
        $title = $successor_group_title[$group];
        $this->data['title'] = $title;
        $this->data['app'] = RESELLER_APP;
        $this->template->load(null, 'reseller/update_reseller', $this->data);
    }

    public function update_rate($user_id = 0) {
        $parent_user_id = $this->session->userdata("user_id");
        $this->load->model('reseller_model');
        $response = array();
        if (file_get_contents("php://input") != null) {
            $postdata = file_get_contents("php://input");
            $requestInfo = json_decode($postdata);
            $new_rate_list = array();
            if (property_exists($requestInfo, "updateRate")) {
                $new_rate_list = $requestInfo->updateRate;
            }
            $new_updated_rate_list = array();
            if ($parent_user_id != $user_id && $user_id != 0) {
                $parent_rate_list = $this->service_model->get_user_services($parent_user_id)->result_array();
                if (!empty($parent_rate_list)) {
                    foreach ($new_rate_list as $rate_info) {
                        foreach ($parent_rate_list as $paraent_rate_info) {
                            if ($rate_info->service_id == $paraent_rate_info['service_id']) {
                                if ($rate_info->rate != $paraent_rate_info['rate']) {
                                    $response['message'] = "Please assign rate " . $paraent_rate_info['rate'] . "for the service" . $rate_info->title;
                                    echo json_encode($response);
                                    return;
                                }
                                if ($rate_info->commission > $paraent_rate_info['commission']) {
                                    $response['message'] = "Assigned Commission is higher then your service  " . $rate_info->title;
                                    echo json_encode($response);
                                    return;
                                }
                            }
                        }
                        $new_rate_info = array(
                            'id' => $rate_info->id,
                            'rate' => $rate_info->rate,
                            'commission' => $rate_info->commission,
                            'charge' => $rate_info->charge
                        );
                        $new_updated_rate_list[] = $new_rate_info;
                    }
                }
            } else {
                foreach ($new_rate_list as $rate_info) {
                    $new_rate_info = array(
                        'id' => $rate_info->id,
                        'rate' => $rate_info->rate,
                        'commission' => $rate_info->commission,
                        'charge' => $rate_info->charge
                    );
                    $new_updated_rate_list[] = $new_rate_info;
                }
            }

            if ($this->reseller_model->update_reseller_rates($new_updated_rate_list) == True) {
                $response['message'] = "User Rate Updated successfully !";
            } else {
                $response['message'] = "Error! while User Rate Update !";
            }
            echo json_encode($response);
            return;
        }

        if (!isset($user_id) || $user_id == 0) {
            $user_id = $parent_user_id;
        }
        $rate_list = $this->service_model->get_user_services($user_id)->result_array();
        $this->data['rate_list'] = json_encode($rate_list);
        $this->data['user_id'] = $user_id;
        $this->data['app'] = RESELLER_APP;
        $this->template->load(null, 'reseller/update_rate', $this->data);
    }

    function get_reseller_service_rate() {
        $user_id = $this->session->userdata("user_id");
        $rate_list = $this->service_model->get_user_services($user_id)->result_array();
        $this->data['rate_list'] = json_encode($rate_list);
        $this->data['app'] = RESELLER_APP;
        $this->template->load(null, 'reseller/show_rate', $this->data);
    }

    function get_reseller_profile_info() {
        $this->load->model('service_model');
        $user_id = $this->session->userdata('user_id');
        $service_list = $this->service_model->get_user_services($user_id)->result_array();
        $this->load->model('reseller_model');
        $user_profile_info = array();
        $profile_info = $this->reseller_model->get_profile_info($user_id)->result_array();
        if (!empty($profile_info)) {
            $profile_info = $profile_info[0];
            $user_profile_info = $profile_info;
        }
        $this->data['profile_info'] = $user_profile_info;
        $this->data['service_list'] = $service_list;
        $this->data['app'] = RESELLER_APP;
        $this->template->load(null, 'reseller/profile', $this->data);
    }

}
