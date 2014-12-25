<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once 'base.php';

class Usr extends Base
{
	public function index()
	{
		$this->_view['content'] = $this->load->view('usr/index', array(), true);
		$this->load->view('global', $this->_view);
	}

	public function savePass()
	{
		$data = $this->input->post(null, true);
		extract($data, EXTR_REFS);
		
		if (empty($password)) {
			$this->output->set_output(json_encode(array('error' => 'Password is empty')));
			return;
		}
		
		$this->load->model('User', 'user');
		
		try {
			$this->user->edit($this->_user->id, array('password' => $password));
		} catch (Exception $e) {
			$this->output->set_output(json_encode(array('error' => $e->getMessage())));
			return;
		}
		
		$this->output->set_output(json_encode(array('success' => true)));
	}
}
