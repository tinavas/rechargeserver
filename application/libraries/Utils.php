<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Name:  Date_utils
 * Added in Class Diagram
 * Requirements: PHP5 or above
 */
class Utils {

    /**
     * __construct
     *
     * @return void
     * @author Ben
     * */
    public function __construct() {
        $this->load->config('ion_auth', TRUE);
        // Load the session, CI2 as a library, CI3 uses it as a driver
        if (substr(CI_VERSION, 0, 1) == '2') {
            $this->load->library('session');
        } else {
            $this->load->driver('session');
        }
        $this->load->library('image_lib');
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

    /*
     * this method will validate cell number
     * @param $cell_no phone number
     * @return boolean, true if the cell number is valid otherwise false
     * @author nazmul hasan on 28th february 2016
     */

    public function cell_number_validation($cell_no) {
        if (preg_match("/^((^\880|0)[1][1|5|6|7|8|9])[0-9]{8}$/", $cell_no) === 0) {
            RETURN FALSE;
        } else {
            RETURN TRUE;
        }
    }

    /*
     * This method will retunr a random string of length 32
     * @author nazmul hasan on 2nd March 2016
     */

    public function get_transaction_id() {
        return random_string('unique');
    }

    /*
     * This method will retunr a random integer
     * @author rashida sultana on 8th March 2016
     */

    public function get_random_mapping_id() {
        return random_string('unique', 16);
//        return rand(1, 99);
    }

    /*
     * This method will retunr a random string of length 32
     * @author rashida on 4th May 2016
     */

    public function get_random_string() {
        return random_string('unique');
    }

    public function get_transaction_verification_code() {
        return rand(1000, 9999);
    }


    /*
     * This method will return a operator type id 
     * @param $cell_number, mobile number
     * @author rashida  on 29th August 2016
     */

    public function get_operator_type_id($cell_number) {
        $temp_cell_number = str_replace('88', '', $cell_number);
        $operator_code = substr($temp_cell_number, 0, 3);
        if ($operator_code == OPERATOR_CODE_GP) {
            return SERVICE_TYPE_ID_TOPUP_GP;
        } else if ($operator_code == OPERATOR_CODE_ROBI) {
            return SERVICE_TYPE_ID_TOPUP_ROBI;
        } else if ($operator_code == OPERATOR_CODE_BANGLALINK) {
            return SERVICE_TYPE_ID_TOPUP_BANGLALINK;
        } else if ($operator_code == OPERATOR_CODE_AIRTEL) {
            return SERVICE_TYPE_ID_TOPUP_AIRTEL;
        } else if ($operator_code == OPERATOR_CODE_TELETALK) {
            return SERVICE_TYPE_ID_TOPUP_TELETALK;
        } else {
            return $operator_code;
        }
    }

   
}
