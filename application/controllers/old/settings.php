<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Settings extends CI_Controller {

    function index() {
        
    }

    function change_pass() {
        $pass = $this->input->post("pass");
        $repeat_pass = $this->input->post("repeat_pass");
        if (!empty($pass) && !empty($repeat_pass)) {
            if ($pass == $repeat_pass) {
                $this->user->set_pass($pass);
                $this->session->set_flashdata("msg", array("text" => "Ваш пароль успешно изменен!", "type" => "success"));
                redirect("/");
            }
        }
        $d['cont'] = $this->load->view('change_pass_view', null, true);
        $this->load->view("global_view", $d);
    }

	function change_email() 
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('mail', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('repeat_mail', 'Repeat Email', 'trim|required|valid_email');
		
        $mail = $this->input->post("mail");
        $repeat_mail = $this->input->post("repeat_mail");
        if ($this->form_validation->run() == true){
			if (!empty($mail) && !empty($repeat_mail)) {
				if ($mail == $repeat_mail) {
					$this->user->set_email($mail);
					$this->session->set_flashdata("msg", array("text" => "Ваш Email успешно изменен!", "type" => "success"));
					redirect("/");
				}
			}
		}
        $d['cont'] = $this->load->view('change_email_view', null, true);
        $this->load->view("global_view", $d);
    }
	/*
    function change_email() {
                $mail = $this->input->post("mail");
        $repeat_mail = $this->input->post("repeat_mail");
        if (!empty($mail) && !empty($repeat_mail)) {
            if ($mail == $repeat_mail) {
                $this->user->set_pass($mail);
                $this->session->set_flashdata("msg", array("text" => "Ваш Email успешно изменен!", "type" => "success"));
                redirect("/");
            }
        }
        $d['cont'] = $this->load->view('change_email_view', null, true);
        $this->load->view("global_view", $d);
    }
	*/
}