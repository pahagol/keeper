<?php

class Project
{
	const MAX_COUNT_RECORD_IN_DAY = 10;
	const DATE_PATTERN = '/^[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])$/';
	const START_PAGE = 'add';
	
	protected $_ci;
	
	public static $monthNames = array(
		'',
		'jan',
		'feb',
		'mar',
		'apr',
		'may',
		'jun',
		'jul',
		'aug',
		'sep',
		'okt',
		'nov',
		'des',
	);

	public static $weekNames = array(
		'Понедельник',
		'Вторник',
		'Среда',
		'Четверг',
		'Пятница',
		'Суббота',
		'Воскресенье',
	);
	
	public function __construct()
	{
		$this->_ci = &get_instance();
	}

	public static function isActiveMenu($alias)
	{
		$currentPage = str_replace('/', '', $_SERVER['REQUEST_URI']);
		return empty($currentPage) && $page == static::START_PAGE || $currentPage == $page;
	}

	public function getSummaMonth($userId)
	{
		$this->_ci->load->model('Expense', 'expense');
		$result = $this->_ci->expense
			->getSumPriceByPeriod($userId, date('Y-m-01'), date('Y-m-t'));
		return is_null($result->summa) ? 0 : $result->summa;
	}

	public static function getJSModuleName()
	{
		preg_match('/^\/(\w+)/', $_SERVER['REQUEST_URI'], $currentPage);
		return empty($currentPage) ? static::START_PAGE : $currentPage[1];	
	}

	public static function getFormatDate($format, $date)
	{
		if (!$stamp = strtotime($date)) {
			var_dump($date);
			throw new Exception('Error: Date is wrong format');
		}
		return date($format, $stamp);
	}

	public static function getWeekDay($date)
	{
		$day = static::getFormatDate('w', $date);
		return static::$weekNames[$day];
	}

	public static function validateDate($date, $format = 'Y-m-d H:i:s')
	{
		$d = DateTime::createFromFormat($format, $date);
		return ($d && $d->format($format) == $date);
	}
}
