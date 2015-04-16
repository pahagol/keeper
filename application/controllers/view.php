<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once 'base.php';

class View extends Base
{
	public function index()
	{
		$this->load->model('Expense', 'expense');
		$this->load->model('Category', 'category');

		$from = $this->input->get('from');

		if (empty($from)
		|| !preg_match(Project::DATE_PATTERN, $from)) {
			$from = date('Y-m-01');
			$to = date('Y-m-t');
			$currentYearMonth = date('Y-m');
		} else {
			$parts = explode('-', $from);
			$to = date('Y-m-t', strtotime($from));
			$currentYearMonth = $parts[0] . '-' . $parts[1];
		}

		$dates = array();

		if ($results = $this->expense->getDatesGroupByMonth($this->_user->id)) {
			foreach ($results as $result) {
				$parts = explode('-', $result->yearMonthAdd);
				$dates[] = array(
					'value' => $parts[0] . '-' . $parts[1] . '-01',
					'html' => $parts[0] . ' ' . Project::$monthNames[intval($parts[1])],
					'current' => ($currentYearMonth == ($parts[0] . '-' . $parts[1])),
				);
			}
		}

		$groups = 
		$temp = array();

		if ($result = $this->expense->getAll($this->_user->id, $from, $to)) {
			foreach ($result as $res) {
				$temp[$res->dateAdd][] = $res;
			}

			// ставляем недостающие дни недели прошлого месяца, чтоб было кратно неделе
			if ($weekday = Project::getFormatDate('w', $from)) {
				$weekday = (int)$weekday;
				for ($i = 0; $i < $weekday; $i++) {
					$day = $weekday - $i;
					$key = date('Y-m-d', strtotime($from . '-' . $day . ' days'));
					$groups[$key] = false;
				}
			}

			// вставляем недостающие дни текущего месяца
			$lastDayMonth = date('t', strtotime($from));
			for ($i = 1; $i <= $lastDayMonth; $i++) {

				$day = ($i < 10) ? '0' . $i : $i;
				$key = $currentYearMonth . '-' . $day;
				if (!isset($temp[$key])) {
					$groups[$key] = false;
				} else {
					$groups[$key] = $temp[$key];
				}
			}
		}

		$view = array(
			'categories' => $this->category->getAllByUserId($this->_user->id),
			'groups' => $groups,
			'dates' => $dates,
		);

		$this->_view['content'] = $this->load->view('view/index', $view, true);
		$this->load->view('global', $this->_view);
	}

	public function save()
	{
		$this->load->model('Expense', 'expense');
		$data = $this->input->post(null, true);
		extract($data, EXTR_REFS);

		if (empty($id)) {
			$this->output->set_output(json_encode(array('error' => 'Id is empty')));
			return;
		}
		if (empty($field)) {
			$this->output->set_output(json_encode(array('error' => 'Field is empty')));
			return;
		}
		if (empty($value)) {
			$this->output->set_output(json_encode(array('error' => 'Value is empty')));
			return;
		}

		try {
			$this->expense->editByUserId($id, $this->_user->id, array($field => $value));
		} catch (Exception $e) {
			$this->output->set_output(json_encode(array('error' => $e->getMessage())));
			return;
		}
		$success = $field == 'price' ? array('summa' => $this->project->getSummaMonth($this->_user->id)) : true;
		$this->output->set_output(json_encode(array('success' => $success)));
	}

	public function delete()
	{
		$this->load->model('Expense', 'expense');
		$id = $this->input->post('id', true);

		if (empty($id)) {
			$this->output->set_output(json_encode(array('error' => 'Id is empty')));
			return;
		}
		
		try {
			$this->expense->deleteByUserId($id, $this->_user->id);
		} catch (Exception $e) {
			$this->output->set_output(json_encode(array('error' => $e->getMessage())));
			return;
		}

		$this->output->set_output(json_encode(array('success' => array('id' => $id, 'summa' => $this->project->getSummaMonth($this->_user->id)))));
	}

	public function saveDictionaryExpenseName()
	{
		$this->load->model('Expense', 'expense');
		$this->load->model('DictionaryExpenseName', 'dictionaryExpenseName');

		$data = $this->input->post(null, true);
		extract($data);

		if (empty($dictionaryExpenseNameId)) {
			if (!$dictionaryExpenseNameId = $this->dictionaryExpenseName->getIdByUserIdValue($this->_user->id, $name)) {
				if (!$dictionaryExpenseNameId = $this->dictionaryExpenseName->add(array(
					'userId' => $this->_user->id,
					'name' => $name,
				))) {
					$this->output->set_output(json_encode(array('error' => 'Can\'t add in DictionaryExpenseName')));
					return;
				}
			}
		}

		try {
			$this->expense->editByUserId($expenseId, $this->_user->id, array(
				'dictionaryExpenseNameId' => $dictionaryExpenseNameId,
			));
		} catch (Exception $e) {
			$this->output->set_output(json_encode(array('error' => $e->getMessage())));
			return;
		}

		$this->output->set_output(json_encode(array('success' => true)));
	}
}
