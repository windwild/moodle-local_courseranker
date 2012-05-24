<?php
require_once('../../config.php');
require_once(dirname(__FILE__).'/locallib.php');
require_once 'config.php';
global $cr_config;

$context = get_system_context();

$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
$PAGE->set_url('/local/courseranker/index.php');
$PAGE->set_title('本学期活跃课程排行榜');
$PAGE->set_heading("$SITE->shortname: ".'本学期活跃课程排行榜');

$renderer = $PAGE->get_renderer('local_courseranker');

/*if(optional_param('flush',NULL,PARAM_INT) == 1){
	flush_all_cache();
}*/

echo '<style type="text/css">
.r0{
	background-color: yellow;
}</style>';

echo $renderer->header();



$course_detail_id = optional_param('course_id_detail',NULL,PARAM_INT);
$course_id = optional_param('course_id',NULL,PARAM_INT);
$user_id = optional_param('user_id',NULL,PARAM_INT);


if($cr_config->cache['home'] && $output = is_cached($course_id, $user_id, $course_detail_id)){
	echo $output;
}else{
	$output = $renderer->coursetable();
	cache_it($course_id, $user_id, $course_detail_id, $output);
	echo $output;
}



echo $renderer->footer();
