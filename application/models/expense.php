<?php

require_once 'keeper.php';

class Expense extends Keeper
{
	const OPTION_DICTIONARY_EXPENSE_NAME = 'dictionaryExpenseName';
	const OPTION_CATEGORY = 'category';
	const OPTION_OWNER = 'owner';

	protected $_table = 'Expense';
	protected $_fields = array(
		'userId',
		'categoryId',
		'ownerId',
		'dictionaryExpenseNameId',
		'dateAdd',
		'price',
	);
	protected $_optionFields = array(
		self::OPTION_DICTIONARY_EXPENSE_NAME => 'dictionaryExpenseNameId',
		self::OPTION_CATEGORY => 'categoryId',
		self::OPTION_OWNER => 'ownerId',
	);

	public $options = null;
	
	public function __construct()
	{
		// Call the Model constructor
		parent::__construct();

		$this->options = array_keys($this->_optionFields);
	}

	public function getAll($userId, $from, $to)
	{
		if (empty($userId)) {
			throw new Exception('userId is empty');
		}
		if (empty($from) || !preg_match(Project::DATE_PATTERN, $from)) {
			throw new Exception('Date from is empty or wrong');
		}
		if (empty($to) && !preg_match(Project::DATE_PATTERN, $to)) {
			throw new Exception('Date to is empty or wrong');
		}
		$query = $this->db->select('e.*, d.name AS name, c.name AS categoryName, o.name AS ownerName')
			->join('Category c', 'c.id = categoryId')
			->join('Owner o', 'o.id = ownerId')
			->join('DictionaryExpenseName d', 'e.dictionaryExpenseNameId = d.id')
			->where('e.userId = ' . $userId . ' AND e.dateAdd >= "' . $from . '" AND e.dateAdd <= "' . $to . '"')
			->order_by('e.dateAdd', 'ASC')
			->get($this->_table . ' e');

		// echo $this->db->last_query();

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function getCountRecordInDay($userId, $day)
	{
		if (empty($userId)) {
			throw new Exception('userId is empty');
		}
		if (empty($day) || !preg_match(Project::DATE_PATTERN, $day)) {
			throw new Exception('Date is empty or wrong');
		}
		$query = $this->db->select('COUNT(*) AS `count`')
			->where('userId = ' . $userId . ' AND dateAdd >= "' . $day . '" AND dateAdd <= "' . $day . '"')
			->get($this->_table);

		$result = $query->row();

		return $result['count'];
	}

	public function getSumPriceByPeriod($userId, $from, $to = null)
	{
		if (empty($userId)) {
			throw new Exception('userId is empty');
		}
		if (empty($from) || !preg_match(Project::DATE_PATTERN, $from)) {
			throw new Exception('Date from is empty or wrong');
		}
		if (!empty($to) && !preg_match(Project::DATE_PATTERN, $to)) {
			throw new Exception('Date to is empty or wrong');
		}

		$query = $this->db->select_sum('price', 'summa')
			->where('userId = ' . $userId . ' AND dateAdd >= "' . $from . '"');
		
		if (!empty($to)) {
			$query = $this->db->where('dateAdd <= "' . $to . '"');
		}

		return $this->db->get($this->_table)->row();
	}

	public function getSumByGroupDay($userId, $option, $optionId, $dateAdd)
	{
		if (empty($userId)) {
			throw new Exception('userId is empty');
		}
		$where = 'userId = ' . $userId;
		if (!empty($optionId)) {
			$where .= ' AND ' . $this->_optionFields[$option] . ' = ' . $optionId;
		}
		if (!empty($dateAdd)) {
			$where .= ' AND dateAdd = "' . $dateAdd . '"';
		}
		if (!empty($optionId) && !empty($dateAdd)) {
			$where .= ' AND ' . $this->_optionFields[$option] . ' = ' . $optionId . ' AND dateAdd = "' . $dateAdd . '"';
		}
		if (empty($where)) {
			throw new Exception('Params are empty');
		}
		
		$query = $this->db->select_sum('price', 'summa')
			->where($where);
		
		return $this->db->get($this->_table)->row();
	}

	public function getUniqueDateByPeriod($userId, $from, $to = null)
	{
		if (empty($userId)) {
			throw new Exception('userId is empty');
		}
		if (empty($from) || !preg_match(Project::DATE_PATTERN, $from)) {
			throw new Exception('Date from is empty or wrong');
		}
		if (!empty($to) && !preg_match(Project::DATE_PATTERN, $to)) {
			throw new Exception('Date to is empty or wrong');
		}

		$where = 'userId = ' . $userId . ' AND dateAdd >= "' . $from . '"';
		if (!empty($to)) {
			$where .= ' AND dateAdd <= "' . $to . '"';
		}

		$query = $this->db->select('dateAdd')
			->where($where)
			->group_by('dateAdd');
		
		return $this->db->get($this->_table)->result();
	}

	public function getDatesGroupByMonth($userId)
	{
		if (empty($userId)) {
			throw new Exception('userId is empty');
		}
		$query = $this->db->select('dateAdd as yearMonthAdd')
			->where('userId = ' . $userId)
			->group_by('YEAR(dateAdd), MONTH(dateAdd)');
		
		return $this->db->get($this->_table)->result();
	}

	public function deleteByOption($id, $option, $userId)
	{
		if (empty($id)) {
			throw new Exception('Id is empty');
		}
		if (!in_array($option, $this->options)) {
			throw new Exception('Option is wrong');
		}
		if (empty($userId)) {
			throw new Exception('userId is empty');
		}
		return $this->db->delete($this->_table, $this->_optionFields[$option] . ' = ' . $id . ' AND userId = ' . $userId);
	}
}
