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

//echo $renderer->welcome();
//echo $renderer->coursetable();

if(isset($_GET['course_id'])&&isset($_GET['user_id'])){
	echo $renderer->rank_detail($_GET['user_id'],$_GET['course_id']);
}else{
	if(isset($_GET['course_id'])){
		$course_id = $_GET['course_id'];
		echo $renderer->get_course($course_id);
	}else if(isset($_GET['user_id'])){
		echo $renderer->user_info($_GET['user_id']);
	}else{
		echo $renderer->coursetable();
	}
}

echo $renderer->footer();
