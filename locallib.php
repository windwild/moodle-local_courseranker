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
			$view_num = $DB->get_record_sql('SELECT COUNT(*) AS view_num FROM mdl_log WHERE course='.$course->id.' AND action="view"')->view_num;
			$post_num = $DB->get_record_sql('SELECT COUNT(*) AS post_num FROM mdl_log WHERE course='.$course->id.' AND action LIKE"add%"')->post_num;
			$score = $view_num + $post_num * 10;
			$result = array('id'=>$course->id,'fullname'=>$course->fullname,'view_num'=>$view_num,'post_num'=>$post_num,'score'=>$score);
			$results[] = $result;
		}

		//sort by score
		foreach ($results as $key => $row){
			$score_a[$key] = $row['score'];
		}
		array_multisort($score_a,SORT_DESC,$results);

		//print table header
		$output .= '<table border=1>';
		if(count($results) > 0){
			$output .= '<tr>';
			foreach ($results[0] as $key => $value){
				$output .= '<td>'.$key.'</td>';
			}
			$output .= '</tr>';
		}
		//print result table
		foreach ($results as $result){
			$output .= '<tr>'.
				'<td>'.'<a href="?course_id='.$result['id'].'">'.$result['id'].'</a></td>'.
				'<td>'.$result['fullname'].'</td>'.
				'<td>'.$result['view_num'].'</td>'.
				'<td>'.$result['post_num'].'</td>'.
				'<td>'.$result['score'].'</td>'.
				'<tr>';
		}
		$output .= '</table>';
		
		return $output;
}

function get_enrolled_users_by_course($course_id){
	global $DB;
	$sql_old = 'SELECT u.id, u.username, u.firstname, u.lastname, u.email
		FROM mdl_role_assignments ra, mdl_user u, mdl_course c, mdl_context cxt
		WHERE ra.userid = u.id
		AND ra.contextid = cxt.id
		AND cxt.contextlevel =50
		AND cxt.instanceid = c.id
		AND c.id = '.$course_id.'
		AND (roleid =5 OR roleid=3)';
	$sql = 'SELECT id, userid,COUNT( `action`) AS times,`action` FROM mdl_log WHERE course = '.$course_id.' AND userid IN 
		(SELECT u.id AS id
		FROM mdl_role_assignments ra, mdl_user u, mdl_course c, mdl_context cxt
		WHERE ra.userid = u.id
		AND ra.contextid = cxt.id
		AND cxt.contextlevel =50
		AND cxt.instanceid = c.id
		AND c.id = '.$course_id.'
		AND (roleid =5 OR roleid=3)) GROUP BY `userid`,`action` LIKE "add%" OR "view%"  ORDER BY userid';
	$db_results = $DB->get_records_sql($sql);
	$results = array();
	foreach($db_results as $db_result){
		if (strncmp('view',$db_result->action,4) == 0){
			$results[$db_result->userid]['view'] = $db_result->times;
		}else{
			$results[$db_result->userid]['post'] = $db_result->times;
		}
		$results[$db_result->userid]['userid'] = $db_result->userid;
	}
	
	foreach ($results as $userid => $value){
		if(!isset($value['post'])) $results[$key]['post'] = 0;
		if(!isset($value['view'])) $results[$key]['view'] = 0;
		$results[$userid]['score'] = $value['view'] + $value['post'] * 10;
	}
	
	foreach ($results as $key => $row){
			$score_a[$key] = $row['score'];
		}
	array_multisort($score_a,SORT_DESC,$results);
		
	return $results;
}

