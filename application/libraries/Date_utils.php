<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Name:  Date_utils
 * Added in Class Diagram
 * Requirements: PHP5 or above
 */
class Date_utils {

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
     * This method will return start unix time of today
     * @author nazmul hasan on 19th may 2016
     */

    public function server_start_unix_time_of_today() {
        $date = unix_to_human(now());
        $date_array = explode(" ", $date);
        return human_to_unix($date_array[0] . ' 00:00 AM');
    }

    /*
     * This method will return end unix time of today
     * @author nazmul hasan on 19th may 2016
     */

    public function server_end_unix_time_of_today() {
        $date = unix_to_human(now());
        $date_array = explode(" ", $date);
        return human_to_unix($date_array[0] . ' 00:00 AM') + 86400;
    }

    /*
     * This method will return unix time of start of a date
     * @param $date, date in yyyy-mm-dd format
     * @param $country_code country code
     * @author nazmul hasan on 2nd March 2016
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
     * @author nazmul hasan on 2nd March 2016
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
     * This method will return date from unix time
     * @param $time unix time
     * @param $$country_code country code
     * @author nazmul hasan on 3rd March 2016
     */

    public function get_unix_to_display($time, $country_code = 'BD') {
        $time_zone_array = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code);
        $dateTimeZone = new DateTimeZone($time_zone_array[0]);
        $dateTime = new DateTime("now", $dateTimeZone);
        $offset = $dateTime->getOffset();

        return unix_to_human($time + $offset);
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

    public function server_start_unix_time_of_previous_day() {
        $date = date('Y-m-d', strtotime("-1 days"));
        $date = date_parse_from_format('Y-m-d', $date);
        $timestamp = mktime(0, 0, 0, $date['month'], $date['day'], $date['year']);
        return $timestamp;
    }

    public function convert_date_to_unix_time($date) {
        $date = date_parse_from_format('Y-m-d', $date);
        $timestamp = mktime(0, 0, 0, $date['month'], $date['day'], $date['year']);
        return $timestamp + 86400;
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

    public function get_current_unit_time($country_code = 'BD') {
        $time_zone_array = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code);
        $dateTimeZone = new DateTimeZone($time_zone_array[0]);
        $dateTime = new DateTime("now", $dateTimeZone);
        $unix_current_time = now() + $dateTime->getOffset();
        return $unix_current_time;
    }
 
    
    
}
