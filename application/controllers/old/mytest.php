<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/** NOAUTH CONTROLLER* */
class Mytest extends CI_Controller {

    public function index() {
    	$this->load->view("mytest_view");
	}	
}

/* End of file forward.php */
/* Location: ./application/controllers/forward.php */
