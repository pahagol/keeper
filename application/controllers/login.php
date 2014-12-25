<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
{
	public function index()
	{
		// Не обязательно
		// if ($this->authorizer->check()) {
		// 	redirect('/');
		// }
		$this->load->view('login');
	}

	public function check()
	{
		$data = $this->input->post(null, true);

		extract($data, EXTR_REFS);
		$error = array();
			
		if (empty($login)) {
			$this->output->set_output(json_encode(array('error' => 'Login required')));
			return;
		}
		if (empty($password)) {
			$this->output->set_output(json_encode(array('error' => 'Password required')));
			return;
		}
		if (!$this->authorizer->auth($login, $password)) {
			$this->output->set_output(json_encode(array('error' => 'Login/Password is wrong')));
			return;
		}
		
		$this->output->set_output(json_encode(array('success' => true)));
	}

	public function ajax()
	{
		$this->output->set_output(json_encode(array('login' => true)));
	}
}
