<?php
require_once('../../config.php');
require_once(dirname(__FILE__).'/locallib.php');
require_once 'config.php';
global $cr_config;

$context = get_system_context();

$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
$PAGE->set_url('/local/courseranker/userrank.php');
$PAGE->set_title('用户活跃度统计');
$PAGE->set_heading("$SITE->shortname: ".'用户活跃度统计');

$renderer = $PAGE->get_renderer('local_courseranker');

echo $renderer->header();

$course_id = optional_param('course_id',NULL,PARAM_INT);

$output = $renderer->user_rank($course_id);
echo $output;

echo $renderer->footer();