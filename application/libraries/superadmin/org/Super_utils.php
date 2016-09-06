<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Name:  Date_utils
 * Added in Class Diagram
 * Requirements: PHP5 or above
 */
class Super_utils {

    /**
     * __construct
     *
     * @return void
     * @author Ben
     * */
    public function __construct() {
        
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
     * This method will return current date for user end in YYYY-MM-DD format
     * @Author Nazmul on 27July 2016
     */

    public function get_current_date($country_code = 'BD') {
        $time_zone_array = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code);
        $dateTimeZone = new DateTimeZone($time_zone_array[0]);
        $dateTime = new DateTime("now", $dateTimeZone);
        $unix_current_time = now() + $dateTime->getOffset();
        $human_current_time = unix_to_human($unix_current_time);
        $human_current_time_array = explode(" ", $human_current_time);
        return $human_current_time_array[0];
    }

    /*
     * This method will convert unix time into human date dd-mm-yyyy format
     * @param $unix_time, time in unix format
     * @param $show_minute, whether minute will be showed or not
     * @param $country_code, country code of this user
     * @Author Nazmul on 17 June 2014
     */

    public function get_unix_to_human_date($unix_time, $show_minute = 0, $country_code = 'GB') {
        $time_zone_array = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code);
        $dateTimeZone = new DateTimeZone($time_zone_array[0]);
        $dateTime = new DateTime("now", $dateTimeZone);
        $relative_unix_time = $unix_time + $dateTime->getOffset();
        $human_current_time = unix_to_human($relative_unix_time);
        $human_current_time_array = explode(" ", $human_current_time);
        $human_current_date = $human_current_time_array[0];
        $splited_date_content = explode("-", $human_current_date);
        if ($show_minute == 1) {
            return $splited_date_content[2] . '-' . $splited_date_content[1] . '-' . $splited_date_content[0] . ' ' . $human_current_time_array[1];
        } else {
            return $splited_date_content[2] . '-' . $splited_date_content[1] . '-' . $splited_date_content[0];
        }
    }

    public function resize_image($source_path, $new_path, $height, $width) {
        $result = array();
        $config = array(
            'image_library' => 'gd2',
            'source_image' => FCPATH . $source_path,
            'new_image' => FCPATH . $new_path,
            'maintain_ratio' => FALSE,
            'overwrite' => TRUE,
            'height' => $height,
            'width' => $width
        );
        $image_absolute_path = FCPATH . dirname($new_path);
        if (!is_dir($image_absolute_path)) {
            mkdir($image_absolute_path, 0777, TRUE);
        }
        $this->image_lib->clear();
        $this->image_lib->initialize($config);
        if (!$this->image_lib->resize()) {
            $result['status'] = 0;
            $result['message'] = $this->image_lib->display_errors();
        } else {
            $result['status'] = 1;
        }
        return $result;
    }

}
