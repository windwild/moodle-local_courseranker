<?php


// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Library of functions and constants for module chat
 *
 * @package   courseranker
 * @copyright 2012 Jiayang Gao  {@link http://windiwld.net}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once 'locallib.php';

function local_courseranker_cron(){
	mtrace('Course Ranker start checking cache');
	
	mtrace('flusing');
	flush_all_cache();
	mtrace('flushed');
	
	mtrace('Course Ranker cache checked');
}

function courseranker_extends_navigation(global_navigation $navigation) {

    $courseranker = $navigation->add('本学期活跃课程排行榜', new moodle_url('/local/courseranker/'));

}

