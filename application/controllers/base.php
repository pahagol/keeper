<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

abstract class Base extends CI_Controller
{
	protected $_user = null;

	protected $_view = array(
		'content' => '',
		'userId' => null,
	);

	public function __construct()
	{
		parent::__construct();

		if (!($this->_user = $this->authorizer->check())) {
			if ($this->input->is_ajax_request()) {
				redirect('/login/ajax');
			} else {
				redirect('/login');
			}
			exit();
		}
		$this->_view['user'] = $this->_user;
	}
}
