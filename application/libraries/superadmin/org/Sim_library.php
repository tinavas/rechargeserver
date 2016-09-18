<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 */
class Sim_library {

    public function __construct() {
        $this->load->model("superadmin/org/sim_model");
    }

    /**
     * __call
     *
     * Acts as a simple way to call model methods without loads of stupid alias'
     *
     * */
    public function __call($method, $arguments) {
        if (!method_exists($this->sim_model, $method)) {
            throw new Exception('Undefined method Sim_library::' . $method . '() called');
        }

        return call_user_func_array(array($this->sim_model, $method), $arguments);
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

    public function get_sms_list($sim_no, $start_date, $end_date, $offset = 0, $limit = 0) {
        $start_time = 0;
        $end_time = 0;
        return $this->sim_model->get_sms_list($sim_no, $start_time, $end_time, $offset, $limit);
    }

    
    
}
