<?php
defined('MOODLE_INTERNAL') || die();
require_once(dirname(__FILE__).'/locallib.php');

class local_courseranker_renderer extends plugin_renderer_base{	

	function coursetable(){
		$output = '';
		$results = get_course_table();
		$table = new html_table();
		$table->head = array('fullname','score');
		foreach ($results as $result){
			$cell1 = new html_table_cell();
			$cell2 = new html_table_cell();
			$cell1->text = '<a href="?course_id='.$result['courseid'].'">'.$result['fullname'].'</a>';
			$cell2->text = '<a href="?course_id_detail='.$result['courseid'].'">'.$result['score'].'</a>';
			$row = new html_table_row();
			$row->cells[] = $cell1;
			$row->cells[] = $cell2;
			$table->data[] = $row;
		}
		$output .= html_writer::table($table);
		return $output;
	}
	
	function get_user_rank($course_id){
		$output = '';
		$users = get_user_rank($course_id);
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
	
	function course_score_detail($course_id){
		$output = '';
		$table = new html_table();
		$table->head = array('action','times');
		$results = get_course_score_detail($course_id);
		
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