<?php
/*
http://carbon.nesbot.com/docs/#api-modifiers
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Mdate
{
	protected $ci;
	protected $time;
	protected $date_format;

	public function __construct()
	{
		$this->ci =& get_instance();
		$this->time = time();
		$this->date_format = 'Y-m-d';
		// date_default_timezone_set('UTC');
	}

	function create($time, $format = '')
	{
		$this->time = $time;
		if($format !='')
			$this->set_format($format);
		return $this;
	}

	function set_format($format)
	{
		$this->date_format = $format;
		return $this;
	}

	function date($format,$time = 0)
	{
		$time = $this->convert_time($time);
		return date($format,$time);
	}
	function convert_time($time = '')
	{
		if($time =='')
		{
			return time();
		}
		$t = strtotime($time);
		if(!is_numeric($time) && $t !== false && $t >=0)
		{
			return $t;
		}
		return $time;
	}

	// 2012-1-31 12:0:0 -> 2012-01-31 00:00:00
	function startOfDay($time = '', $to_time = TRUE)
	{
		$time = $this->convert_time($time);
		$date = date('Y-m-d',$time);
		if($to_time)
			return strtotime($date);
		return $date;
	}

	// 2012-1-31 12:0:0 -> 2012-01-31 23:59:59
	function endOfDay($time = '', $to_time = TRUE)
	{
		$time = $this->startOfDay($time);
		$time = strtotime('+1 day', $time)-1;
		$date = date('Y-m-d H:i:s',$time);
		if($to_time)
			return $time;
		return $date;
	}
	
	// 2012-1-31 12:0:0 -> 2012-01-31 23:59:59
	function startOfMonth($time = '', $to_time = TRUE)
	{
		$time = $this->convert_time($time);
		$time = strtotime(date('Y-m-01',$time));
		if($to_time)
			return $time;
		return date($this->date_format,$time);
	}

	function endOfMonth($time = '', $to_time = TRUE)
	{
		$time = $this->convert_time($time);
		$time = strtotime(date('Y-m-t',$time));
		if($to_time)
			return $time;
		return date($this->date_format,$time);
	}

	function startOfYear()
	{
		
	}

	function endOfYear()
	{

	}

	function week_name($time = null, $day_of_week = array()) {
		$time = $this->convert_time($time);
		if(!$day_of_week)
			$day_of_week = array(
				'Chủ nhật' , 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'
				);
		return @$day_of_week[date('w', $time)];
	}
}

/* End of file Mdate.php */
/* Location: ./application/libraries/Mdate.php */