<?php
defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) { // needs this condition or there is error on login page
	
    $temp = new admin_settingpage('courseranker', 'Course Ranker');

    $temp->add(new admin_setting_configtext('courseranker/starttime', 'Start time', 'time to start calulation', 0, PARAM_INT));
    
    $temp->add(new admin_setting_configcheckbox('courseranker/flush','Flush?','flush when you visit course ranker home page',0));

    $ADMIN->add('localplugins', $temp);
}
