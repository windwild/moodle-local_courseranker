<?php
require_once 'config.php';

/**
 * get data of all the course from db 
 *
 * @Copyright(c) 2012, Gao Jiayang All Rights Reserved.
 * @Author Gao Jiayang http://windwild.net
 * @param none
 * @return array of courses
 */
function get_enrolled_number(){
	global $DB;
	global $cr_config;
	
	$sql = 'SELECT c.id AS course_id, COUNT(DISTINCT(u.id)) AS enrolled_number
		FROM {role_assignments} ra, {user} u, {course} c, {context} cxt
		WHERE ra.userid = u.id
		AND ra.contextid = cxt.id
		AND cxt.contextlevel =50
		AND cxt.instanceid = c.id
		AND roleid IN ('.$cr_config->student_role_id.')
		AND c.category IN ('.$cr_config->category.')
		GROUP BY c.id';
	$db_results = $DB->get_records_sql($sql);
	if(count($db_results) == 0){
		return array();
	}
	$result = array();
	foreach ($db_results as $db_result){
		$result[$db_result->course_id] =  $db_result->enrolled_number;
	}
	return $result;
}

function get_courses_teacher(){
	global $DB;
	global $cr_config;
	$results = array();
	$sql = 'SELECT ra.id,c.id AS course_id, u.id AS user_id, u.firstname, u.lastname
		FROM mdl_role_assignments ra, mdl_user u, mdl_course c, mdl_context cxt
		WHERE ra.userid = u.id
		AND ra.contextid = cxt.id
		AND cxt.contextlevel =50
		AND cxt.instanceid = c.id
		AND roleid IN ('.$cr_config->teacher_role_id.')
		AND c.category IN ('.$cr_config->category.')';
	$db_results = $DB->get_records_sql($sql);
	foreach ($db_results as $db_result) {
		$results[$db_result->course_id][$db_result->user_id] = array('user_id'=>$db_result->user_id
			,'firstname'=>$db_result->firstname,'lastname'=>$db_result->lastname);
	}
	return $results;
}



function get_course_table(){
	global $DB;
	global $cr_config;
	$output = '';
	
	$sql = 'SELECT l.id,l.course AS courseid,re1.fullname,`module`,`action`,COUNT(`action`) AS times FROM 
		(
			(
			SELECT u.id AS userid,c.id AS courseid, c.fullname
			FROM {role_assignments} ra, {user} u, {course} c, {context} cxt
			WHERE ra.userid = u.id
			AND ra.contextid = cxt.id
			AND cxt.contextlevel =50
			AND cxt.instanceid = c.id
			AND c.category IN ('.$cr_config->category.')
			AND roleid IN ('.$cr_config->student_role_id.')
			) AS re1
		LEFT JOIN {log} l ON re1.userid = l.userid AND re1.courseid = l.course
		)
		WHERE l.`time` >= ?
		GROUP BY `module`,`action`,`course`';
	
	$param = array($cr_config->starttime);
	$db_results = $DB->get_records_sql($sql,$param);
	if(count($db_results) == 0){
		return array();
	}
	$results = array();
	foreach($db_results as $db_result){
		if(!isset($results[$db_result->courseid])){
			$results[$db_result->courseid]['score'] = 0;
			$results[$db_result->courseid]['course_id'] = $db_result->courseid;
			$results[$db_result->courseid]['fullname'] = $db_result->fullname;
		}
		if(isset($cr_config->weight[$db_result->module][$db_result->action]))
			$results[$db_result->courseid]['score'] += $db_result->times * $cr_config->weight[$db_result->module][$db_result->action];
	}
	
	$course_enrolled_number = get_enrolled_number();
	$course_teacher = get_courses_teacher(); 
	foreach($results as $key => $result){
		$results[$key]['student_number'] = $course_enrolled_number[$key];
		$results[$key]['ave_score'] = round($result['score'] / $course_enrolled_number[$key],2);
		$results[$key]['course_teacher'] = $course_teacher[$key];
	}
	
	foreach ($results as $key => $row){
			$score_a[$key] = $row['ave_score'];
		}
	array_multisort($score_a,SORT_DESC,$results);
	return $results;
}


function get_user_rank($course_id){
	global $DB;
	global $cr_config;
	$output = '';
	
	$sql = 'SELECT l.id,l.userid AS userid,re1.fullname,re1.firstname,re1.lastname,`module`,`action`,COUNT(`action`) AS times FROM
		(
			(
			SELECT u.id AS userid,c.id AS courseid, c.fullname, u.firstname,u.lastname
			FROM {role_assignments} ra, {user} u, {course} c, {context} cxt
			WHERE ra.userid = u.id
			AND ra.contextid = cxt.id
			AND cxt.contextlevel =50
			AND cxt.instanceid = c.id
			AND c.id = ?
			AND roleid IN ('.$cr_config->student_role_id.')
			) AS re1
		LEFT JOIN {log} l ON re1.userid = l.userid AND re1.courseid = l.course
		)
		WHERE l.`time` >= ?
		GROUP BY `module`,`action`,l.`userid`';
	
	$param = array($course_id,$cr_config->starttime);
	$db_results = $DB->get_records_sql($sql,$param);
	if(count($db_results) == 0){
		return array();
	}
	$results = array();
	foreach($db_results as $db_result){
		if(!isset($results[$db_result->userid])){
			$results[$db_result->userid]['userid'] = $db_result->userid;
			$results[$db_result->userid]['fullname'] = $db_result->fullname;
			$results[$db_result->userid]['firstname'] = $db_result->firstname;
			$results[$db_result->userid]['lastname'] = $db_result->lastname;
			$results[$db_result->userid]['score'] = 0;
		}
		if(isset($cr_config->weight[$db_result->module][$db_result->action]))
			$results[$db_result->userid]['score'] += $db_result->times * $cr_config->weight[$db_result->module][$db_result->action];
	}
	
	
	foreach ($results as $key => $row){
			$score_a[$key] = $row['score'];
		}
	array_multisort($score_a,SORT_DESC,$results);
	return $results;
}


function is_cached($course_id,$user_id,$course_detail_id){
	global $DB;
	$result = $DB->get_record('courseranker',array('course_id'=>$course_id,'user_id'=>$user_id,'course_detail_id'=>$course_detail_id));;
	if(isset($result->value)){
		return $result->value;
	}else{
		return false;
	}
}

function cache_it($course_id,$user_id,$course_detail_id,$value){
	global $DB;
	$DB->delete_records('courseranker',array('course_id'=>$course_id,'user_id'=>$user_id,'course_detail_id'=>$course_detail_id));
	$DB->insert_record('courseranker',array('course_id'=>$course_id,'user_id'=>$user_id,'course_detail_id'=>$course_detail_id,'value'=>$value,'time'=>time()));
}

function flush_all_cache(){
	global $DB;
	$DB->delete_records('courseranker',array());
}
