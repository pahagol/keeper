<?php

require_once 'keeper.php';

class Owner extends Keeper
{
	protected $_table = 'Owner';
	protected $_fields = array(
		'userId',
		'name',
	);

	public function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
}
