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

    public function get_unix_to_human_date($unix_time, $country_code = 'BD') {
        $time_zone_array = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code);
        $dateTimeZone = new DateTimeZone($time_zone_array[0]);
        $dateTime = new DateTime("now", $dateTimeZone);
        $offset = $dateTime->getOffset();

        return unix_to_human($unix_time + $offset);
    }

    /*
     * This method will convert unix time into human date dd-mm-yyyy format
     * @param $unix_time, time in unix format
     * @param $show_minute, whether minute will be showed or not
     * @param $country_code, country code of this user
     * @Author Nazmul on 17 June 2014
     */

    public function get_auth_unix_to_human_date($unix_time, $country_code = 'BD') {
        $time_zone_array = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code);
        $dateTimeZone = new DateTimeZone($time_zone_array[0]);
        $dateTime = new DateTime("now", $dateTimeZone);
        $offset = $dateTime->getOffset();

        return unix_to_human($unix_time + $offset - 7200);
    }

    /*
     * This method will return unix time of start of a date
     * @param $date, date in yyyy-mm-dd format
     * @param $country_code country code
     * @author nazmul hasan on 18th september 2016
     */

    public function server_start_unix_time_of_date($date, $country_code = 'BD') {
        $date_start_unix = human_to_unix($date . ' 00:00 AM');

        $time_zone_array = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code);
        $dateTimeZone = new DateTimeZone($time_zone_array[0]);
        $dateTime = new DateTime("now", $dateTimeZone);
        $offset = $dateTime->getOffset();

        return $date_start_unix - $offset;
    }

    /*
     * This method will return unix time of end of a date
     * @param $date, date in yyyy-mm-dd format
     * @param $country_code country code
     * @author nazmul hasan on 18th september 2016
     */

    public function server_end_unix_time_of_date($date, $country_code = 'BD') {
        $date_start_unix = human_to_unix($date . ' 00:00 AM');

        $time_zone_array = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code);
        $dateTimeZone = new DateTimeZone($time_zone_array[0]);
        $dateTime = new DateTime("now", $dateTimeZone);
        $offset = $dateTime->getOffset();

        return $date_start_unix - $offset + 86400;
    }

    /*
     * This method will return unix time of start of a date at authentication server
     * @param $date, date in yyyy-mm-dd format
     * @param $country_code country code
     * @author nazmul hasan on 18th september 2016
     */

    public function auth_server_start_unix_time_of_date($date, $country_code = 'BD') {
        $date_start_unix = human_to_unix($date . ' 00:00 AM');

        $time_zone_array = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code);
        $dateTimeZone = new DateTimeZone($time_zone_array[0]);
        $dateTime = new DateTime("now", $dateTimeZone);
        $offset = $dateTime->getOffset();

        return $date_start_unix - $offset + 7200;
    }

    /*
     * This method will return unix time of end of a date at authentication server
     * @param $date, date in yyyy-mm-dd format
     * @param $country_code country code
     * @author nazmul hasan on 18th september 2016
     */

    public function auth_server_end_unix_time_of_date($date, $country_code = 'BD') {
        $date_start_unix = human_to_unix($date . ' 00:00 AM');

        $time_zone_array = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code);
        $dateTimeZone = new DateTimeZone($time_zone_array[0]);
        $dateTime = new DateTime("now", $dateTimeZone);
        $offset = $dateTime->getOffset();

        return $date_start_unix - $offset + 86400 + 7200;
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

    /*
     * This method will return date from unix time
     * @param $time unix time
     * @param $$country_code country code
     * @author nazmul hasan on 3rd March 2016
     * @modified rashida on 7th Sep 2016
     */

    public function get_unix_to_display($time, $country_code = 'BD') {
        $time_zone_array = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code);
        $dateTimeZone = new DateTimeZone($time_zone_array[0]);
        $dateTime = new DateTime("now", $dateTimeZone);
        $offset = $dateTime->getOffset();
        return unix_to_human($time + $offset - 7200);
    }

    public function server_start_unix_time_of_previous_day() {
        $date = date('Y-m-d', strtotime("-1 days"));
        $date = date_parse_from_format('Y-m-d', $date);
        $timestamp = mktime(0, 0, 0, $date['month'], $date['day'], $date['year']);
        return $timestamp;
    }

    /*
     * This method will return start unix time of today
     * @author nazmul hasan on 19th may 2016
     */

    public function server_start_unix_time_of_today() {
        $date = unix_to_human(now());
        $date_array = explode(" ", $date);
        return human_to_unix($date_array[0] . ' 00:00 AM');
    }

    /*
     * This method will return time list from 1 min to 30mins in secconds
     * @author rashida on 26th Sep 2016
     */

    public function get_transaction_interval_list() {
        $time_list = array();
        $obj = new stdClass();
        $obj->time_in_sec = 60;
        $obj->time_in_min = 1 . "min";
        $time_list[] = $obj;
        for ($i = 2; $i <= 30; $i++) {
            $obj1 = new stdClass();
            $obj1->time_in_sec = $i * 60;
            $obj1->time_in_min = $i . "mins";
            $time_list[] = $obj1;
        }
        return $time_list;
    }

}
