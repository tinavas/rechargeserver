<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login_attempt extends CI_Controller {

    public $tmpl = '';

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('form_validation');
        $this->load->library('superadmin/org/login_attemtps_library');
        $this->load->helper('url');

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
        $this->load->helper('language');
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in()) {
            redirect('superadmin/auth/login', 'refresh');
        }
        $this->tmpl = SUPER_ADMIN_TEMPLATE;
    }

    function index() {
        $this->data['app'] = LOGIN_ATTEMPT_APP;
        $this->data['message'] = '';
        $login_attempt_list = array();
        $login_attempts = array();
        $login_attempt_list_array = $this->login_attemtps_library->get_all_login_attempts()->result_array();
        if (!empty($login_attempt_list_array)) {
            $login_attempt_list = $login_attempt_list_array;
        }
        $this->load->library('superadmin/org/super_utils');
        foreach ($login_attempt_list as $value) {
            $value['time'] = $this->super_utils->get_unix_to_human_date($value['time']);
            $login_attempts[] = $value;
        }
        $this->data['login_attempt_list'] = $login_attempts;
        $this->template->load(SUPER_ADMIN_TEMPLATE, "superadmin/login_attempt/index", $this->data);
    }

    /*
     * Ajax call
     * This method will delete login attempt
     * @Author Redoy on 4th September 2016
     */

    public function delete_login_attempt() {
        $result = array();
        $delete_id = $this->input->post('delete_id');
        if ($this->login_attemtps_library->delete_login_attempt($delete_id)) {
            $result['message'] = 'Delete login attempts successful';
        } else {
            $result['message'] = 'Delete login attempts fail';
        }
        echo json_encode($result);
    }

}
