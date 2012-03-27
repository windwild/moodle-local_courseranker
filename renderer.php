<?php
defined('MOODLE_INTERNAL') || die();
require_once(dirname(__FILE__).'/locallib.php');

class local_courseranker_renderer extends plugin_renderer_base{	
	function welcome(){
		$output = '';
		$output .= 'hello world';
		return $output;
	}
	
	function coursetable(){
		$output = '';
		$output .= get_course_table();
		return $output;
	}
}