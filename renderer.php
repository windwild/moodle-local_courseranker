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
		$results = get_course_table();
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
	
	function get_course($course_id){
		$output = '';
		$users = get_enrolled_users_by_course($course_id);
		$output .= '<table border=1>';
		if(count($users > 0)){
			$output .= '<tr><td>userid</td><td>view</td><td>post</td><td>score</td></tr>';
			foreach ($users as $key => $values){
				$output .= '<tr>';
				$output .= '<td><a href="../../user/view.php?id='.$values['userid'].'">'.$values['userid'].'</a></td>';
				$output .= '<td>'.$values['view'].'</td>';
				$output .= '<td>'.$values['post'].'</td>';
				$output .= '<td>'.$values['score'].'</td>';
				$output .= '</tr>';
			}
			$output .= '</table>';
		}
		return $output;
	}
	
	function user_info($user_id){
		$output = '';
		$output .= 'id:'.$user_id.' a good guy';
		return $output;
	}
}