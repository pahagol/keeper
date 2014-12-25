<?php

require_once 'keeper.php';

class Category extends Keeper
{
	protected $_table = 'Category';
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
