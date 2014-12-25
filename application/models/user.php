<?php

require_once 'keeper.php';

class User extends Keeper
{
	protected $_table = 'User';
	protected $_fields = array(
		'login',
		'password',
		'hash',
		'expire',
	);

	public function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function checkByCredential($login, $password)
	{
		if (empty($login) || empty($password)) {
			return false;
		}

		$query = $this->db->select('id')
			->where('login = "' . $login . '" and password = "' . $password . '"');
		
		return $this->db->get($this->_table)->row();
	}

	public function checkByHash($hash)
	{
		if (empty($hash)) {
			return false;
		}

		$query = $this->db->select('id, login, expire')
			->where('hash = "' . $hash . '"');
		
		return $this->db->get($this->_table)->row();
	}
}
