<?php
//require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once('../../config.php');

$context = get_system_context();

$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
$PAGE->set_url('/local/courseranker/index.php');
$PAGE->set_title('Course Ranker');
$PAGE->set_heading("$SITE->shortname: ".'Course Ranker');

$renderer = $PAGE->get_renderer('local_courseranker');


echo $renderer->header();


if(optional_param('course_id_detail',FALSE,PARAM_INT)){
	echo $renderer->course_score_detail(required_param('course_id_detail',PARAM_INT));
}else if(optional_param('course_id',FALSE,PARAM_INT)&&optional_param('user_id',FALSE,PARAM_INT)){
	echo $renderer->rank_detail(required_param('user_id',PARAM_INT),required_param('course_id',PARAM_INT));
}else{
	if(optional_param('course_id',FALSE,PARAM_INT)){
		$course_id = required_param('course_id',PARAM_INT);
		echo $renderer->get_user_rank($course_id);
	}else if(optional_param('user_id',FALSE,PARAM_INT)){
		echo $renderer->user_info(required_param('user_id',PARAM_INT));
	}else{
		echo $renderer->coursetable();
	}
}

/*if(isset($_GET['course_id_detail'])){
	echo $renderer->course_score_detail(required_param('course_id_detail',PARAM_INT));
}else if(isset($_GET['course_id'])&&isset($_GET['user_id'])){
	echo $renderer->rank_detail(required_param('user_id',PARAM_INT),required_param('course_id',PARAM_INT));
}else{
	if(isset($_GET['course_id'])){
		$course_id = required_param('course_id',PARAM_INT);
		echo $renderer->get_user_rank($course_id);
	}else if(isset($_GET['user_id'])){
		echo $renderer->user_info(required_param('user_id',PARAM_INT));
	}else{
		echo $renderer->coursetable();
	}
}*/

echo $renderer->footer();
