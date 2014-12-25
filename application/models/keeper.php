<?php

abstract class Keeper extends CI_Model
{
	public function __construct()
	{
		// Call the Model constructor
		parent::__construct();

		if (empty($this->_fields)) {
			throw new Exception('Fields are empty');
		}

		if (empty($this->_table)) {
			throw new Exception('Table is empty');
		}
	}

	public function getAll()
	{
		$query = $this->db->get($this->_table);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function getAllByUserId($userId)
	{
		if (empty($userId)) {
			throw new Exception('userId is empty');
		}

		$query = $this->db
			->where('userId = ' . $userId)
			->get($this->_table);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function load($id)
	{
		if (empty($id)) {
			return false;
		}

		$query = $this->db->get_where($this->_table, array('id' => $id));

		if ($query->num_rows()) {
			return $query->row();
		} else {
			return false;
		}
	}

	public function add($params)
	{
		if (empty($params) || !is_array($params)) {
			throw new Exception('Params are empty or aren\'t array');
		}

		$data = array();

		foreach ($this->_fields as $field) {
			if (empty($params[$field])) {
				throw new Exception('Param ' . $field . ' is empty');		
			} else {
				$data[$field] = $params[$field];
			}
		}

		if ($this->db->insert($this->_table, $data)) {
			return $this->db->insert_id();
		} else {
			return false;
		} 
	}

	public function edit($id, $params)
	{
		if (empty($id)) {
			throw new Exception('Id is empty');
		}
		if (empty($params) || !is_array($params)) {
			throw new Exception('Params are empty or aren\'t array');
		}

		foreach ($params as $field => $value) {
			if ($field == 'id') {
				continue;
			} elseif (empty($value)) {
				throw new Exception('Value is empty');		
			} elseif (!in_array($field, $this->_fields)) {
				throw new Exception('Field ' . $field . ' isn\'t exist');
			} else {
				$this->db->set($field, $value);
			}
		}
		
		$this->db->where('id', $id);
		return $this->db->update($this->_table);
	}

	public function editByUserId($id, $userId, $params)
	{
		if (empty($id)) {
			throw new Exception('Id is empty');
		}
		if (empty($userId)) {
			throw new Exception('userId is empty');
		}
		if (empty($params) || !is_array($params)) {
			throw new Exception('Params are empty or aren\'t array');
		}

		foreach ($params as $field => $value) {
			if ($field == 'id') {
				continue;
			} elseif (empty($value)) {
				throw new Exception('Value is empty');		
			} elseif (!in_array($field, $this->_fields)) {
				throw new Exception('Field ' . $field . ' isn\'t exist');
			} else {
				$this->db->set($field, $value);
			}
		}
		
		$this->db->where('id = ' . $id . ' AND userId = ' . $userId);
		return $this->db->update($this->_table);
	}

	public function delete($id)
	{
		if (empty($id)) {
			throw new Exception('Id is empty');
		}
		return $this->db->delete($this->_table, 'id = ' . $id, 1);
	}

	public function deleteByUserId($id, $userId)
	{
		if (empty($id)) {
			throw new Exception('id is empty');
		}
		if (empty($userId)) {
			throw new Exception('userId is empty');
		}
		return $this->db->delete($this->_table, 'id = ' . $id . ' AND userId = ' . $userId, 1);
	}
}
