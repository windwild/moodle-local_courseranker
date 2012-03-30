<?php

class cr_config{
	public $starttime;
	public $weight = array();
	public $category;
	public $cache = array();
	function __construct(){
		
		$this->starttime = strtotime('2012-03-29');
		$this->category = '3';
		$this->cache['home'] = true; //state 4
		$this->cache['course'] = true; //state 3
		$this->cache['course_detail'] = false; //state 1
		$this->cache['course_user'] = false; //state 2

		$this->weight['forum']['view forum'] = 1; 
		$this->weight['forum']['add post'] = 10; 
		$this->weight['forum']['add'] = 0; 
		$this->weight['forum']['add discussion'] = 10; 
		$this->weight['course']['view'] = 1; 
		$this->weight['course']['add mod'] = 1; 
	}
}

$cr_config = new cr_config();
