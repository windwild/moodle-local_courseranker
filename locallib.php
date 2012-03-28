<?php

//require_once('../../config.php');

function get_course_table(){
	global $DB;
	$output = '';
	$results = array();
		//get all courses
		$courses = $DB->get_records('course',array());

		//get courses data
		foreach ($courses as $course){
			$view_num = $DB->get_record_sql('SELECT COUNT(*) AS view_num FROM {log} WHERE course='.$course->id.' AND action="view"')->view_num;
			$post_num = $DB->get_record_sql('SELECT COUNT(*) AS post_num FROM {log} WHERE course='.$course->id.' AND action LIKE"add%"')->post_num;
			$score = $view_num + $post_num * 10;
			$result = array('id'=>$course->id,'fullname'=>$course->fullname,'view_num'=>$view_num,'post_num'=>$post_num,'score'=>$score);
			$results[] = $result;
		}

		//sort by score
		foreach ($results as $key => $row){
			$score_a[$key] = $row['score'];
		}
		array_multisort($score_a,SORT_DESC,$results);
		
		return $results;
}

function get_enrolled_users_by_course($course_id){
	global $DB;
	
	$weight = array('view'=>1,
		'view forum'=>1,
		'view discussion'=>1,
		'view forum'=>1,
		'add post'=>1,
		'login'=>0,
		'mailer'=>0,
		'add discussion'=>10,
		'update'=>0);
	
	$sql_old = 'SELECT u.id, u.username, u.firstname, u.lastname, u.email
		FROM mdl_role_assignments ra, mdl_user u, mdl_course c, mdl_context cxt
		WHERE ra.userid = u.id
		AND ra.contextid = cxt.id
		AND cxt.contextlevel =50
		AND cxt.instanceid = c.id
		AND c.id = '.$course_id.'
		AND (roleid =5 OR roleid=3)';
	$sql_old2 = 'SELECT id, userid,COUNT( `action`) AS times,`action` FROM {log} WHERE course = ? AND userid IN 
		(SELECT u.id AS id
		FROM {role_assignments} ra, {user} u, {course} c, {context} cxt
		WHERE ra.userid = u.id
		AND ra.contextid = cxt.id
		AND cxt.contextlevel =50
		AND cxt.instanceid = c.id
		AND c.id = ?
		AND (roleid =5 OR roleid=3)) GROUP BY `userid`,`action` LIKE "add%" OR "view%"  ORDER BY userid';
	$sql = 'SELECT l.id,re1.userid,username,re1.firstname,re1.lastname,re1.email,ACTION,COUNT(`action`) AS times FROM 
		((SELECT u.id AS userid, u.username,u.firstname,u.lastname,u.email,c.id AS courseid
		FROM {role_assignments} ra, {user} u, {course} c, {context} cxt
		WHERE ra.userid = u.id
		AND ra.contextid = cxt.id
		AND cxt.contextlevel =50
		AND cxt.instanceid = c.id
		AND c.id = ?
		AND roleid IN (SELECT id FROM {role} r WHERE archetype = "student")) AS re1
		LEFT JOIN {log} l ON re1.userid = l.userid AND re1.courseid = l.course)
		WHERE l.`time` >= ?
		GROUP BY `action`';
	$param_array = array($course_id,1332828172);
	$db_results = $DB->get_records_sql($sql,$param_array);
	if(count($db_results) == 0){
		return array();
	}
	$results = array();
	foreach($db_results as $db_result){
		if(!isset($results[$db_result->userid]))
			$results[$db_result->userid]['score'] = 0;
		$results[$db_result->userid]['userid'] = $db_result->userid;
		$results[$db_result->userid]['username'] = $db_result->username;
		$results[$db_result->userid]['firstname'] = $db_result->firstname;
		$results[$db_result->userid]['lastname'] = $db_result->lastname;
		$results[$db_result->userid]['email'] = $db_result->email;
		if(isset($weight[$db_result->action]))
			$results[$db_result->userid]['score'] += $db_result->times * $weight[$db_result->action];
	}
	
	foreach ($results as $key => $row){
			$score_a[$key] = $row['score'];
		}
	array_multisort($score_a,SORT_DESC,$results);
		
	return $results;
}

function get_rank_detail($user_id,$course_id){
	global $DB;
	$sql = "SELECT l.id,l.userid,l.course, l.ACTION,COUNT(`action`) AS times FROM {log} l
		WHERE userid = ? AND course = ? GROUP BY `action`";
	$param_array = array($user_id,$course_id);
	$db_results = $DB->get_records_sql($sql,$param_array);
	if(count($db_results) == 0){
		return array();
	}
	return $db_results;
}

