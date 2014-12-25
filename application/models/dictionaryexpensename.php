<?php

require_once 'keeper.php';

class DictionaryExpenseName extends Keeper
{
	protected $_table = 'DictionaryExpenseName';
	protected $_fields = array(
		'userId',
		'name',
	);

	public function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function getAllByUserIdValue($userId, $name)
	{
		if (empty($userId)) {
			throw new Exception('userId is empty');
		}
		
		$name = trim($name);
		if (empty($name)) {
			throw new Exception('name is empty');
		}

		$query = $this->db
			->where('userId = ' . $userId . ' AND name LIKE "' . $name . '%"')
			->get($this->_table);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function getIdByUserIdValue($userId, $name)
	{
		if (empty($userId)) {
			throw new Exception('userId is empty');
		}
		
		$name = trim($name);
		if (empty($name)) {
			throw new Exception('name is empty');
		}

		$query = $this->db->get_where($this->_table, array(
			'userId' => $userId, 
			'name' => $name
		));
			
		// echo $this->db->last_query();

		if ($query->num_rows()) {
			$result = $query->row();
			return $result->id;
		} else {
			return false;
		}
	}	
}
