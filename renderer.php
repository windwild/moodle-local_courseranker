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
		global $cr_config;
		
		$output = '';
		$results = get_course_table();
		$table = new html_table();
		$table->head = array('排名','课程名称','主讲教师','人均活跃指数','学生数');
		$pos =1;
		foreach ($results as $result){
			if($result['ave_score'] < $cr_config->minimum_ave_score || 
				$result['student_number'] < $cr_config->minimum_student_number){
				continue;
			}
			$cell1 = new html_table_cell();
			$cell2 = new html_table_cell();
			$cell3 = new html_table_cell();
			$cell4 = new html_table_cell();
			$cell5 = new html_table_cell();
			
			$cell1->text = $pos;
			$cell2->text = 	'<a href="../../course/view.php?id='.$result['course_id'].'">'.$result['fullname'].'</a>';
			$teacher_count=0;
			foreach ($result['course_teacher'] as $teacher){
				$teacher_name = $teacher['lastname'].' '.$teacher['firstname']."<br>";
				$user = new stdClass();
				$user->firstname = $teacher['firstname'];
				$user->lastname = $teacher['lastname'];
				$teacher_name = fullname($user);
				$cell3->text .= '<a href="../../user/view.php?id='.$teacher['user_id'].'">'.$teacher_name.' </a><br>';
				$teacher_count++;
				if($teacher_count % 4 == 0){
					$cell3->text .= '<br>';
				}
			}
			$cell4->text = $result['ave_score'];
			$cell5->text = $result['student_number'];
			
			$row = new html_table_row();
			$row->cells[] = $cell1;
			$row->cells[] = $cell2;
			$row->cells[] = $cell3;
			$row->cells[] = $cell4;
			$row->cells[] = $cell5;
			$table->data[] = $row;
			++$pos;
		}
		$output .= html_writer::table($table);
		return $output;
	}
	
}