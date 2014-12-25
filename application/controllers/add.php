<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once 'base.php';

class Add extends Base
{
	public function index()
	{
		$this->load->model('Category', 'category');
		$this->load->model('Owner', 'owner');

		$view = array(
			'categories' => $this->category->getAllByUserId($this->_user->id),
			'owners' => $this->owner->getAllByUserId($this->_user->id),
			'now' => date('Y-m-d'),
		);

		$this->_view['content'] = $this->load->view('add/index', $view, true);
		$this->load->view('global', $this->_view);
	}

	public function save()
	{
		$this->load->model('Expense', 'expense');
		$this->load->model('DictionaryExpenseName', 'dictionaryExpenseName');

		$data = $this->input->post(null, true);
		extract($data, EXTR_REFS);
		$userId = $this->_user->id;

		if (empty($categoryId)) {
			$this->output->set_output(json_encode(array('error' => 'Category is empty')));
			return;
		}
		if (empty($ownerId)) {
			$this->output->set_output(json_encode(array('error' => 'Owner is empty')));
			return;
		}
		if (empty($dateAdd) || !preg_match(Project::DATE_PATTERN, $dateAdd)) {
			$this->output->set_output(json_encode(array('error' => 'Date is wrong or empty')));
			return;
		}

		if (empty($dictionaryExpenseNameId)) {
			if (!$dictionaryExpenseNameId = $this->dictionaryExpenseName->getIdByUserIdValue($userId, $name)) {
				if (!$dictionaryExpenseNameId = $this->dictionaryExpenseName->add(array(
					'userId' => $userId,
					'name' => $name,
				))) {
					$this->output->set_output(json_encode(array('error' => 'Can\'t add in DictionaryExpenseName')));
					return;
				}
			}
		}

		try {
			$this->expense->add(array(
				'userId' => $userId,
				'categoryId' => $categoryId,
				'ownerId' => $ownerId,
				'dictionaryExpenseNameId' => $dictionaryExpenseNameId,
				'dateAdd' => $dateAdd,
				'price' => $price,
			));
		} catch (Exception $e) {
			$this->output->set_output(json_encode(array('error' => $e->getMessage())));
			return;
		}

		$price = preg_match('/^' . date('Y-m') . '/', $dateAdd) ? $price : 0;
		$this->output->set_output(json_encode(array('success' => array('price' => $price))));
	}

	public function searchDictionaryExpenseName()
	{
		$this->load->model('DictionaryExpenseName', 'dictionaryExpenseName');

		$name = $this->input->get('query', true);
		$userId = $this->_user->id;

		if (empty($name)) {
			$this->output->set_output(json_encode(array(
				'error' => 'Name is empty')));
			return;
		}
		
		$suggestions = 
		$data = array();

		if ($result = $this->dictionaryExpenseName->getAllByUserIdValue($userId, $name)) {
			foreach ($result as $res) {
				$suggestions[] = $res->name;
				$data[] = $res->id;
			}
		}

		$this->output->set_output(json_encode(array(
			'query' => $name,
			'suggestions' => $suggestions,
			'data' => $data,
		)));
	}
}
