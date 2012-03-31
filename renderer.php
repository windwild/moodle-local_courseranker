<?php

/**
 * Course Ranker render class
 *
 * @package   local_courseranker
 * @copyright 2012 Gao Jiayang (http://windwild.net)
 * @author    Gao Jiayang
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();
require_once(dirname(__FILE__).'/locallib.php');
require_once 'config.php';

class local_courseranker_renderer extends plugin_renderer_base{	

	/**
	 * this function is used to get output HTML of all selected
	 * courses' score and rank.
	 *
	 * @Copyright(c) 2012, Gao Jiayang All Rights Reserved.
	 * @Author Gao Jiayang http://windwild.net
	 * @param none
	 * @return string. html of courses's score and rank.
	 */
	
	function coursetable(){
		$output = '';
		$results = get_course_table();
		$table = new html_table();
		$table->head = array('名次','课程名称','总分','平均分');
		$pos =1;
		foreach ($results as $result){
			$cell1 = new html_table_cell();
			$cell2 = new html_table_cell();
			$cell3 = new html_table_cell();
			$cell4 = new html_table_cell();
			
			$cell1->text = $pos;
			$cell2->text = '<a href="?course_id='.$result['courseid'].'">'.
				$result['fullname'].'</a>';
			$cell3->text = '<a href="?course_id_detail='.$result['courseid'].'">'.
				$result['score'].'</a>';
			$cell4->text = '<a href="?course_id_detail='.$result['courseid'].'">'.
				$result['ave_score'].'</a>';
			
			$row = new html_table_row();
			$row->cells[] = $cell1;
			$row->cells[] = $cell2;
			$row->cells[] = $cell3;
			$row->cells[] = $cell4;
			$table->data[] = $row;
			++$pos;
		}
		$output .= html_writer::table($table);
		return $output;
	}
	
	/**
	 * get user rank of the course
	 *
	 * @Copyright(c) 2012, Gao Jiayang All Rights Reserved.
	 * @Author Gao Jiayang http://windwild.net
	 * @param int $course_id id of the course which you want to rank 
	 * @return string. html of the user score and rank in course
	 */
	
	function get_user_rank($course_id){
		$output = '';
		$users = get_user_rank($course_id);
		if(count($users > 0)){
			$table = new html_table();
			$table->head = array('用户id','用户名','邮箱','名','姓','贡献分数');
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
	
	/**
	 * show rank detail of a user in a course
	 *
	 * @Copyright(c) 2012, Gao Jiayang All Rights Reserved.
	 * @Author Gao Jiayang http://windwild.net
	 * @param int $user_id, int $course_id
	 * @return string. html of the user score detail
	 */
	
	function rank_detail($user_id,$course_id){
		global $cr_config; 
		$output = '';
		$table = new html_table();
		$table->head = array('模块','动作','次数','权重');
		$results = get_rank_detail($user_id,$course_id);
		
		foreach ($results as $result){
			$row = new html_table_row();
			$cell1 = new html_table_cell();
			$cell2 = new html_table_cell();
			$cell3 = new html_table_cell();
			$cell4 = new html_table_cell();
			
			$cell1->text = $result -> module;
			$cell2->text = $result -> action;
			$cell3->text = $result -> times;
			if(isset($cr_config->weight[$result -> module][$result -> action])){
				$cell4->text = $cr_config->weight[$result -> module][$result -> action];
			}else {
				$cell4->text = 0;
			}
			
			$row->cells[] = $cell1;
			$row->cells[] = $cell2;
			$row->cells[] = $cell3;
			$row->cells[] = $cell4;
			$table->data[] = $row;
		}
		$output .= html_writer::table($table);
		return $output;
	}
	
	/**
	 * show course score detail
	 *
	 * @Copyright(c) 2012, Gao Jiayang All Rights Reserved.
	 * @Author Gao Jiayang http://windwild.net
	 * @param int $course_id
	 * @return string. show detail score of the course
	 */
	
	function course_score_detail($course_id){
		global $cr_config;
		$output = '';
		$table = new html_table();
		$table->head = array('模块','动作','次数','权重');
		$results = get_course_score_detail($course_id);
		
		foreach ($results as $result){
			$row = new html_table_row();
			$cell1 = new html_table_cell();
			$cell2 = new html_table_cell();
			$cell3 = new html_table_cell();
			$cell4 = new html_table_cell();
			
			$cell1->text = $result -> module;
			$cell2->text = $result -> action;
			$cell3->text = $result -> times;
			if(isset($cr_config->weight[$result -> module][$result -> action])){
				$cell4->text = $cr_config->weight[$result -> module][$result -> action];
			}else {
				$cell4->text = 0;
			}
			
			$row->cells[] = $cell1;
			$row->cells[] = $cell2;
			$row->cells[] = $cell3;
			$row->cells[] = $cell4;
			$table->data[] = $row;
		}
		$output .= html_writer::table($table);
		return $output;
	}
}
