<?php
defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) { // needs this condition or there is error on login page
	
    $temp = new admin_settingpage('courseranker', 'Course Ranker');

    $temp->add(new admin_setting_configtext('courseranker/starttime', '计算开始时间', '计算课程活跃度的开始时间', 0, PARAM_INT));
    
    $temp->add(new admin_setting_configcheckbox('courseranker/flush','清空缓存？','当访问模块时清空缓存',0));

    $ADMIN->add('localplugins', $temp);
}
