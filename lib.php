<?php
require_once 'locallib.php';

function local_courseranker_cron(){
	mtrace('Course Ranker start checking cache');
	
	mtrace('flusing');
	flush_all_cache();
	mtrace('flushed');
	
	mtrace('Course Ranker cache checked');
}

