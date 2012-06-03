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
		$this->minimum_student_number = 1;
		$this->minimum_ave_score = 1;
		$this->student_role_id = '5';
		$this->teacher_role_id = '3';

		//use $parent_categories to identify target category
		//$parent_categories 这个数组中存放着你想进行评估课程的分类
        $parent_categories = array(1,3,22, 2, 13, 41, 38, 37, 36, 35, 34, 16, 17, 18, 19, 20, 21, 25, 27, 31, 23, 42);
		$this->category = $this->get_sub_category($parent_categories);
		
		//$this->starttime set from when you want to start calculation
		//$this->starttime 这个属性保存了你希望开始计算的时间
		$this->starttime = strtotime('2012-02-27');
		
		//$this->highlight 这个属性保存了你希望高亮的个数
		$this->highlight = 3;
		
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
		$this->weight['assignment']['upload'] = 5;

		$this->weight['chat']['view'] = 1;
		$this->weight['chat']['talk'] = 50;
		$this->weight['chat']['report'] = 10;               // See session logs

		$this->weight['choice']['view'] = 1;
		$this->weight['choice']['choose'] = 100;
		$this->weight['choice']['choose again'] = 25;

		$this->weight['data']['view'] = 1;
		$this->weight['data']['fields add'] = 100;
		$this->weight['data']['fields update'] = 50;

		$this->weight['feedback']['view'] = 1;
		$this->weight['feedback']['startcomplete'] = 50;    // Start to fill feedback
		$this->weight['feedback']['submit'] = 100;          // Submit feedback

		$this->weight['folder']['view'] = 1;

		$this->weight['forum']['view forum'] = 1;
		$this->weight['forum']['view discussion'] = 10;
		$this->weight['forum']['add post'] = 100;
		$this->weight['forum']['update post'] = 50;
		$this->weight['forum']['add discussion'] = 100;

		$this->weight['glossary']['view forum'] = 1;
		$this->weight['glossary']['view entry'] = 10;
		$this->weight['glossary']['add entry'] = 100;
		$this->weight['glossary']['update entry'] = 50;

		$this->weight['lesson']['view'] = 1;
		$this->weight['lesson']['start'] = 50;
		$this->weight['lesson']['end'] = 100;

		$this->weight['page']['view'] = 1;

		$this->weight['quiz']['view'] = 1;
		$this->weight['quiz']['attempt'] = 50;
		$this->weight['quiz']['review'] = 50;
		$this->weight['quiz']['close attempt'] = 50;

		$this->weight['url']['view'] = 1;

		$this->weight['wiki']['view'] = 1;
		$this->weight['wiki']['edit'] = 100;
		$this->weight['wiki']['comments'] = 50;

		$this->weight['workshop']['view'] = 1;
		$this->weight['workshop']['add submission'] = 50;
		$this->weight['workshop']['update submission'] = 25;
		$this->weight['workshop']['view submission'] = 1;
		$this->weight['workshop']['add assessment'] = 100;
		$this->weight['workshop']['update assessment'] = 50;

		$this->weight['hotquestion']['view'] = 1;
		$this->weight['hotquestion']['add question'] = 100;
		$this->weight['hotquestion']['update vote'] = 50;
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
