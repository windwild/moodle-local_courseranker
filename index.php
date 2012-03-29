<?php
//require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once('../../config.php');
require_once(dirname(__FILE__).'/locallib.php');

$context = get_system_context();

$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
$PAGE->set_url('/local/courseranker/index.php');
$PAGE->set_title('Course Ranker');
$PAGE->set_heading("$SITE->shortname: ".'Course Ranker');

$renderer = $PAGE->get_renderer('local_courseranker');


echo $renderer->header();
if(get_config('courseranker')->flush == 1){
	flush_cache();
	set_config('flush',0,'courseranker');
}

$course_detail_id = optional_param('course_id_detail',NULL,PARAM_INT);
$course_id = optional_param('course_id',NULL,PARAM_INT);
$user_id = optional_param('user_id',NULL,PARAM_INT);

echo $course_id;

if($course_detail_id){
	//state 1
	if($output = is_cached($course_id, $user_id, $course_detail_id)){
		echo $output;
	}else{
		$output = $renderer->course_score_detail($course_detail_id);
		cache_it($course_id, $user_id, $course_detail_id, $output);
		echo $output;
	}
	
}else if($course_id && $user_id){
	//state 2
	if($output = is_cached($course_id, $user_id, $course_detail_id)){
		echo $output;
	}else{
		$output = $renderer->rank_detail($user_id,$course_id);
		cache_it($course_id, $user_id, $course_detail_id, $output);
		echo $output;
	}
}else{
	if($course_id){
		//state 3
		if($output = is_cached($course_id, $user_id, $course_detail_id)){
			echo $output;
		}else{
			$output = $renderer->get_user_rank($course_id);
			cache_it($course_id, $user_id, $course_detail_id, $output);
			echo $output;
		}
	}else if($user_id){
		//state 4
		if($output = is_cached($course_id, $user_id, $course_detail_id)){
			echo $output;
		}else{
			$output = $renderer->user_info($user_id,$course_id);
			cache_it($course_id, $user_id, $course_detail_id, $output);
			echo $output;
		}
		
		echo $renderer->user_info(required_param('user_id',PARAM_INT));
	}else{
		//state 5
		if($output = is_cached($course_id, $user_id, $course_detail_id)){
			echo $output;
		}else{
			$output = $renderer->coursetable();
			cache_it($course_id, $user_id, $course_detail_id, $output);
			echo $output;
		}
	}
}


echo $renderer->footer();
