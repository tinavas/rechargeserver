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
        $human_current_time_array= explode(" ", $human_current_time);
        return $human_current_time_array[0];
    }

 
     /*
     * This method will convert unix time into human date dd-mm-yyyy format
     * @param $unix_time, time in unix format
     * @param $show_minute, whether minute will be showed or not
     * @param $country_code, country code of this user
     * @Author Nazmul on 17 June 2014
     */
    public function get_unix_to_human_date($unix_time, $show_minute = 0, $country_code = 'GB')
    {
        $time_zone_array = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code);
        $dateTimeZone = new DateTimeZone($time_zone_array[0]);
        $dateTime = new DateTime("now", $dateTimeZone);
        $relative_unix_time = $unix_time + $dateTime->getOffset();
        $human_current_time = unix_to_human($relative_unix_time);
        $human_current_time_array= explode(" ", $human_current_time);
        $human_current_date = $human_current_time_array[0];
        $splited_date_content = explode("-", $human_current_date);
        if($show_minute == 1)
        {
            return $splited_date_content[2].'-'.$splited_date_content[1].'-'.$splited_date_content[0].' '.$human_current_time_array[1];
        }
        else
        {
            return $splited_date_content[2].'-'.$splited_date_content[1].'-'.$splited_date_content[0];
        }
        
    }
}
