<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Help extends CI_Controller {

    public function index() {
        $dat['cont'] = $this->load->view("help/main_view", null, true);
        $this->load->view("help/global_view", $dat);
    }

    function tutorial() {
        $dat['cont'] = $this->load->view("help/tutorial_view", null, true);
        $this->load->view("help/global_view", $dat);
    }

    function faq() {
        $dat['cont'] = $this->load->view("help/faq_view", null, true);
        $this->load->view("help/global_view", $dat);
    }

    function about() {
        $dat['cont'] = $this->load->view("help/about_view", null, true);
        $this->load->view("help/global_view", $dat);
    }

	function contact() {
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$params['fio']		= $this->input->post('fio', true);
		$params['email']	= $this->input->post('email', true);
		$params['subject']	= $this->input->post('subject', true);
		$params['text']		= $this->input->post('text', true);
		
		$this->form_validation->set_rules('fio', 'Фамилия Имя Отчество', 'trim|required|max_length[80]|min_length[3]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('subject', 'Тема', 'trim|required');
		$this->form_validation->set_rules('text', 'Сообщение', 'trim|required');
		
		
        if ($this->form_validation->run() == true) {
        	$text = 
<<<HD
Письмо с Формы обратной связи
ФИО : {$params['fio']}	
E-mail : {$params['email']}	
Текст : {$params['text']}	
HD;
			send_admin_mail($params['subject'], $text);
			
			$params['success'] = 1;
			$params['fio']		= '';
			$params['email']	= $this->input->post('email', true);
			$params['subject']	= '';
			$params['text']		= '';
		} 
				
        $dat['cont'] = $this->load->view("help/contact_view", $params, true);
        $this->load->view("help/global_view", $dat);
    }
}