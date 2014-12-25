<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once 'base.php';

class Option extends Base
{
	protected $_requiredOptions = array(
		'dictionaryExpenseName',
		'category',
		'owner',
	);

	public function index()
	{
		$this->load->model('DictionaryExpenseName', 'dictionaryExpenseName');
		$this->load->model('Category', 'category');
		$this->load->model('Owner', 'owner');
		
		$view = array(
			'dictionaries' => $this->dictionaryExpenseName->getAllByUserId($this->_user->id),
			'categories' => $this->category->getAllByUserId($this->_user->id),
			'owners' => $this->owner->getAllByUserId($this->_user->id),
		);

		$this->_view['content'] = $this->load->view('option/index', $view, true);
		$this->load->view('global', $this->_view);
	}

	public function add()
	{
		$data = $this->input->post(null, true);
		extract($data, EXTR_REFS);
		
		if (!in_array($option, $this->_requiredOptions)) {
			$this->output->set_output(json_encode(array('error' => 'Wrong option')));
			return;
		}
		if (empty($value)) {
			$this->output->set_output(json_encode(array('error' => 'Value is empty')));
			return;
		}

		$this->load->model(ucfirst($option), $option);
		
		try {
			$this->$option->add(array(
				'userId' => $this->_user->id,
				'name' => $value,
			));
		} catch (Exception $e) {
			$this->output->set_output(json_encode(array('error' => $e->getMessage())));
			return;
		}
		
		$this->output->set_output(json_encode(array('success' => true)));
	}

	public function save()
	{
		$data = $this->input->post(null, true);
		extract($data, EXTR_REFS);
		
		if (!in_array($option, $this->_requiredOptions)) {
			$this->output->set_output(json_encode(array('error' => 'Wrong option')));
			return;
		}
		if (empty($value)) {
			$this->output->set_output(json_encode(array('error' => 'Value is empty')));
			return;
		}
		if (empty($id)) {
			$this->output->set_output(json_encode(array('error' => 'Id is empty')));
			return;
		}

		$this->load->model(ucfirst($option), $option);
		
		try {
			$this->$option->editByUserId($id, $this->_user->id, array('name' => $value));
		} catch (Exception $e) {
			$this->output->set_output(json_encode(array('error' => $e->getMessage())));
			return;
		}
		
		$this->output->set_output(json_encode(array('success' => true)));
	}

	public function delete()
	{
		$data = $this->input->post(null, true);
		extract($data, EXTR_REFS);
		
		if (!in_array($option, $this->_requiredOptions)) {
			$this->output->set_output(json_encode(array('error' => 'Wrong option')));
			return;
		}
		if (empty($id)) {
			$this->output->set_output(json_encode(array('error' => 'Id is empty')));
			return;
		}

		$this->load->model(ucfirst($option), $option);
		$this->load->model('Expense', 'expense');
		
		try {
			if (!$this->$option->deleteByUserId($id, $this->_user->id)) {
				$this->output->set_output(json_encode(array('error' => 'Can\'t delete expense')));
				return;
			}
			if (!$this->expense->deleteByOption($id, $option, $this->_user->id)) {
				$this->output->set_output(json_encode(array('error' => 'Can\'t delete expense')));
				return;
			}
		} catch (Exception $e) {
			$this->output->set_output(json_encode(array('error' => $e->getMessage())));
			return;
		}	 

		$this->output->set_output(json_encode(array('success' => array('option' => $option, 'id' => $id))));
	}
}
