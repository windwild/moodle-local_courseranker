<?php

class cr_config{
	public $starttime;
	public $weight = array();
	public $category;
	static private $_instance = NULL;
	public $cache = array();
	public $minimum_student_number;
	public $minimum_ave_score;
	public $student_role_id;
	public $teacher_role_id;
	
	function __construct(){
		$this->$minimum_student_number = 1;
		$this->$minimum_ave_score = 10;
		$this->$student_role_id = '5';
		$this->$teacher_role_id = '3';
		//use $parent_categories to identify target category
		//$parent_categories 这个数组中存放着你想进行评估课程的分类
        $parent_categories = array(22, 2, 13, 41, 38, 37, 36, 35, 34, 16, 17, 18, 19, 20, 21, 25, 27, 31, 23, 42);
		$this->category = $this->get_sub_category($parent_categories);
		
		//$this->starttime set from when you want to start calculation
		//$this->starttime 这个属性保存了你希望开始计算的时间
		$this->starttime = strtotime('2012-02-27');
		
		//$this->cache swithes for cache
		//$this->cache 存放了四种页面是否需要cache
		$this->cache['home'] = true; //state 4
		$this->cache['course'] = true; //state 3
		$this->cache['course_detail'] = false; //state 1
		$this->cache['course_user'] = false; //state 2

		//$this->weight weight you want to added for each action in each module
		//$this->weight 存放了你对每种module中每种action的权值 不设置默认为0
		$this->weight['course']['view'] = 1;

		$this->weight['assignment']['view'] = 1;
		$this->weight['assignment']['upload'] = 50;

		$this->weight['chat']['view'] = 1;
		$this->weight['chat']['talk'] = 50;
		$this->weight['chat']['report'] = 10;

		$this->weight['choice']['view'] = 1;
		$this->weight['choice']['choose'] = 50;
		$this->weight['choice']['choose again'] = 25;

		$this->weight['resource']['view'] = 1;
		$this->weight['forum']['view forum'] = 1;
		$this->weight['forum']['add post'] = 0;
		$this->weight['forum']['add discussion'] = 0;
	}
	
	private function get_sub_category($parent_categories){
		global $DB;
		$result = array();
		foreach ($parent_categories as $parent_category) {
			$sql = 'SELECT cc.id FROM {course_categories} cc WHERE cc.path LIKE "%/'.$parent_category.'/%"';
			$db_results = $DB->get_records_sql($sql);
			foreach ($db_results as $db_result){
				$result[] = $db_result->id;
			}
			$result[] = $parent_category;
		}
		$results = array_unique($result);
		$return = '';
		foreach ($results as $result){
			$return .= "$result,";
		}
		$return = substr($return,0,-1);
		return $return;
	}
	
	public static function getInstance() {
		if (!(self::$_instance instanceof self)) {
			self::$_instance = new self ();
		}
		return self::$_instance;
	}
	
}

$cr_config = cr_config::getInstance();
