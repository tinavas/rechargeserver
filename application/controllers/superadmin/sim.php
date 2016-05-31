<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sim extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
    }

    public function index() {
        
    }    
}
