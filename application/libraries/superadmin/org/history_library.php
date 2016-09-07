<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 */
class History_library {

    public function __construct() {
        $this->load->model("superadmin/org/history_model");
    }

    /**
     * __call
     *
     * Acts as a simple way to call model methods without loads of stupid alias'
     *
     * */
    public function __call($method, $arguments) {
        if (!method_exists($this->history_model, $method)) {
            throw new Exception('Undefined method Payment_library::' . $method . '() called');
        }

        return call_user_func_array(array($this->history_model, $method), $arguments);
    }

    /**
     * __get
     *
     * Enables the use of CI super-global without having to define an extra variable.
     *
     * I can't remember where I first saw this, so thank you if you are the original author. -Militis
     *
     * @access	public
     * @param	$var
     * @return	mixed
     */
    public function __get($var) {
        return get_instance()->$var;
    }

    public function get_deshbord_info() {
        $this->load->library('superadmin/org/super_utils');
        $todays_unix_time = $this->super_utils->server_start_unix_time_of_today();
        $prviuous_days_unix_time = $this->super_utils->server_start_unix_time_of_previous_day();
        $result_event = $this->history_model->get_deshbord_info($todays_unix_time, $prviuous_days_unix_time);
        return $result_event;
    }

    
    
}
