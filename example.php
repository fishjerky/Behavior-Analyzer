<?php
include('BehaviorAnalyzer.php');
date_default_timezone_set('Asia/Taipei');

//logBehavior();
display();


function display(){
	$analyzer = new BehaviorAnalyzer();
	$analyzer->getDaily();
	$analyzer->getWeekly();
	$analyzer->getMonthly();
	$analyzer->getYearly();
}

function logBehavior(){
	$controller = "space";
	$action = "feed";
	$uid = 1;
	$note = "this is note";
	$analyzer = new BehaviorAnalyzer();
	$analyzer->log($controller, $action, $uid, $note);
}
