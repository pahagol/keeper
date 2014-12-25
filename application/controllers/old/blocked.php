<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Blocked extends CI_Controller {

    function index() {
        $d['cont']="<div style='background:white;padding:10px;'><h3 style='color:red;'>Ваш аккаунт заблокирован</h3></div>";
        $this->load->view("global_view", $d);   
    }

}

?>