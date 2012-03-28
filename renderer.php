<?php
defined('MOODLE_INTERNAL') || die();
require_once(dirname(__FILE__).'/locallib.php');

class local_courseranker_renderer extends plugin_renderer_base{	
	function welcome(){
		$output = '';
		$output .= 'hello world';
		return $output;
	}
	
	function coursetable_old(){
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
	
	function coursetable(){
		$output = '';
		$results = get_course_table();
		$table = new html_table();
		$table->head = array('fullname','view number','post number','score');
		$table->align = array('center','center','center','center');
		foreach ($results as $result){
			$cell1 = new html_table_cell();
			$cell2 = new html_table_cell();
			$cell3 = new html_table_cell();
			$cell4 = new html_table_cell();
			$cell1->text = '<a href="?course_id='.$result['id'].'">'.$result['fullname'].'</a>';
			$cell2->text = $result['view_num'];
			$cell3->text = $result['post_num'];
			$cell4->text = $result['score'];
			$row = new html_table_row();
			$row->cells[] = $cell1;
			$row->cells[] = $cell2;
			$row->cells[] = $cell3;
			$row->cells[] = $cell4;
			$table->data[] = $row;
		}
		$output .= html_writer::table($table);
		return $output;
	}
	
	function get_course_old($course_id){
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
	
	function get_course($course_id){
		$output = '';
		$users = get_enrolled_users_by_course($course_id);
		if(count($users > 0)){
			$table = new html_table();
			$table->head = array('user id','username','email','first name','last name','score');
			$table->align = array('center','center','center','center');
			foreach ($users as $key => $values){
				$cell1 = new html_table_cell();
				$cell2 = new html_table_cell();
				$cell3 = new html_table_cell();
				$cell4 = new html_table_cell();
				$cell5 = new html_table_cell();
				$cell6 = new html_table_cell();
				
				$cell1->text = $values['userid'];
				$cell1->text = '<a href="../../user/view.php?id='.$values['userid'].'">'.$values['userid'].'</a>';
				$cell2->text = $values['username'];
				$cell3->text = $values['email'];
				$cell4->text = $values['firstname'];
				$cell5->text = $values['lastname'];
				$cell6->text = '<a href="?user_id='.$values['userid'].'&course_id='.$course_id.'">'.$values['score'].'</a>';
				
				$row = new html_table_row();
				$row->cells[] = $cell1;
				$row->cells[] = $cell2;
				$row->cells[] = $cell3;
				$row->cells[] = $cell4;
				$row->cells[] = $cell5;
				$row->cells[] = $cell6;
				
				$table->data[] = $row;
			}
			$output .= html_writer::table($table);
		}
		return $output;
	}
	
	function user_info($user_id){
		$output = '';
		$output .= 'id:'.$user_id.' a good guy';
		return $output;
	}
	
	function rank_detail($user_id,$course_id){
		$output = '';
		$table = new html_table();
		$table->head = array('action','times');
		$results = get_rank_detail($user_id,$course_id);
		
		foreach ($results as $result){
			$row = new html_table_row();
			$cell1 = new html_table_cell();
			$cell2 = new html_table_cell();
			
			$cell1->text = $result -> action;
			$cell2->text = $result -> times;
			
			$row->cells[] = $cell1;
			$row->cells[] = $cell2;
			$table->data[] = $row;
		}
		$output = html_writer::table($table);
		return $output;
	}
}