<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Activate_account extends CI_Controller {

    function index() {
       $d['cont']=$this->load->view("activate_account",null,true);
        $this->load->view("global_view",$d);
    }

}

?>