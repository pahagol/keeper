<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once 'base.php';

class Stat extends Base
{
	public function index()
	{
		$this->load->model('Expense', 'expense');
		$options = array(
			array(
				'value' => Expense::OPTION_CATEGORY,
				'html' => 'По категориям',
				'current' => true,
			),
			array(
				'value' => Expense::OPTION_OWNER,
				'html' => 'По принадлежности',
				'current' => false,
			),
		);

		$dates = array();

		if ($results = $this->expense->getDatesGroupByMonth($this->_user->id)) {
			foreach ($results as $result) {
				$parts = explode('-', $result->yearMonthAdd);
				$dates[] = array(
					'value' => $parts[0] . '-' . $parts[1] . '-01',
					'html' => $parts[0] . ' ' . Project::$monthNames[intval($parts[1])],
					'current' => (date('Y-m') == ($parts[0] . '-' . $parts[1])),
				);
			}
		}

		$view = $this->_getView(Expense::OPTION_CATEGORY, date('Y-m-01'), date('Y-m-t'));

		$this->_view['content'] = $this->load->view('stat/index', array_merge($view, array(
			'dates' => $dates,
			'options' => $options,
		)), true);

		$this->load->view('global', $this->_view);
	}

	public function change()
	{
		$this->load->model('Expense', 'expense');
		$data = $this->input->post(null, true);

		if (empty($data['from']) || empty($data['to'])
		|| !in_array($data['option'], $this->expense->options)
		|| !preg_match(Project::DATE_PATTERN, $data['from'])
		|| !preg_match(Project::DATE_PATTERN, $data['to'])) {
			// var_dump($data);
			$this->output->set_output(json_encode(array('error' => 'Date from or to are empty or wrong')));
			return;
		}
		
		$view = $this->_getView($data['option'], $data['from'], $data['to']);
		$this->output->set_output(json_encode(array('success' => $view)));
	}

	protected function _getView($option, $from, $to)
	{
		$this->load->model('Expense', 'expense');
		$this->load->model(ucfirst($option), $option);
		// $this->load->model('Category', 'category');
		
		$series = 
		$options = array();
		
		if (!$dataOptions = $this->$option->getAllByUserId($this->_user->id)) {
			throw new Exception('Error: categories is empty');
		}
		
		if ($dates = $this->expense->getDays($this->_user->id, $from, $to)) {
			
			foreach ($dates as $date) {
				$options[] = $date->dateAdd;	
			}
			
			foreach ($dataOptions as $dataOption) {
				$serie = array();
				
				foreach ($dates as $date) {
					if ($sum = $this->expense->getSumByGroupDay($this->_user->id, $option, $dataOption->id, $date->dateAdd)) {
						$serie[] = (int)$sum->summa;
					} else {
						$serie[] = 0;
					}
				}
				
				$series[] = array(
					'type' => 'column',
					'name' => $dataOption->name,
					'data' => $serie,
				);
			}
		}

		return array(
			'series' => json_encode($series),
			'categories' => json_encode($options),
		);
	}	
}
