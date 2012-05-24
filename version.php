<?php

/////////////////////////////////////////////////////////////////////////////////
///  Code fragment to define the version of Course Ranker
///  This fragment is called by moodle_needs_upgrading() and /admin/index.php
/////////////////////////////////////////////////////////////////////////////////


$plugin->version  = 2012052400;   // The (date) version of this plugin
$plugin->requires = 2010122900;   // Requires this Moodle version

$plugin->maturity  = MATURITY_BETA;
$plugin->release   = "1.1";       // User-friendly version number

$plugin->cron = 60*60;