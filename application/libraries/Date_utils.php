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

    public function server_start_unix_time_of_today() {
        $date = unix_to_human(now());
        $date_array = explode(" ", $date);
        return human_to_unix($date_array[0] . ' 00:00 AM');
    }

    public function server_end_unix_time_of_today() {
        $date = unix_to_human(now());
        $date_array = explode(" ", $date);
        return human_to_unix($date_array[0] . ' 00:00 AM') + 86400;
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

}
