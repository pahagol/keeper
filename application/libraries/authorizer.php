<?php

class Authorizer
{
	protected $_sessionLifeTime = 86400; // сутки

	public function __construct()
	{
		$this->_ci = &get_instance();
	}

	protected function _generateHash()
	{
		return base_convert(md5(uniqid('keeper rules', true)), 16, 36);
	}

	public function auth($login, $pass)
	{
		$this->_ci->load->model('User', 'user');
		
		if (!$result = $this->_ci->user->checkByCredential($login, $pass)) {
			return false;
		}

		$hash = $this->_generateHash();
		$expire = time() + $this->_sessionLifeTime;
		
		$this->_ci->user->edit(
			$result->id, 
			array(
				'hash' => $hash, 
				'expire' => $expire,
		));
		
		setcookie('auth', $hash, $expire, '/');

		return true;
	}

	public function check()
	{
		$hash = get_cookie('auth');
		
		if (empty($hash)) {
			return false;
		}

		$this->_ci->load->model('User', 'user');
		
		if (!$user = $this->_ci->user->checkByHash($hash)) {
			return false;
		}
		
		if ($user->expire < time()) {
			return false;
		}
			
		return $user;
	}

	public function logout()
	{
		setcookie('auth', '');
	}
}
