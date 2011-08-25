<?php	
/*
 *	@Title: BehaviorAnalyzer.php
 *	@Author: kevyu
 *	@Version
 *	@Included Files
 *		-log4php plugin
 */

include_once('log4php/Logger.php');

class BehaviorAnalyzer{
	protected $logger;
	protected $PATH_LOGS;
	//log behavior
	function __construct($path = 'logs/'){
		Logger::configure('log4php.properties'); 
		$this->logger = Logger::getLogger("behavior");
		
		$this->PATH_LOGS = $path;
	}

	function log($controller, $action, $uid, $note){
		$url = "space/feed";
		$this->logger->error(sprintf('%s,%s,%s,%d,%s,%s',
					$uid,
					$controller, 
					$action,
					$url,
					$note,
					$_SERVER['HTTP_USER_AGENT']
					));
	}

	function render($actions){
		uksort($actions, 'strnatcmp');
		foreach($actions as $key => $action){
			echo $key . ': ' . $action . '<br/>';
		}

	}

	//analyze
	function getDaily(){
		//analysis
		$actions = array();
		$ago = mktime(0,0,0,date('m'),date('d'),date('Y'));
		$this->getPeriodLog($ago, $actions);

		//display
		echo '<h4>Daily Analyzer - '.date('Y/m/d'). '</h4>';
		$this->render($actions);
	}
	
	function getWeekly(){
		//analysis
		$actions = array();
		$ago = mktime(0,0,0,date('m'),date('d')-7,date('Y'));
		$this->getPeriodLog($ago, $actions);

		//display
		echo '<h4>Weekly Analyzer (' . date("Y/m/d", $ago) . ' - ' . date('Y/m/d'). ')</h4>';
		$this->render($actions);
	}
	function getMonthly(){
		//analysis
		$actions = array();
		$ago = mktime(0,0,0,date('m')-1,date('d'),date('Y'));
		$this->getPeriodLog($ago, $actions);

		//display
		echo '<h4>Monthly Analyzer (' . date("Y/m/d", $ago) . ' - ' . date('Y/m/d'). ')</h4>';
		$this->render($actions);

	}

	function getYearly(){$actions = array();
		//analysis
		$actions = array();
		$ago = mktime(0,0,0,date('m'),date('d'),date('Y')-1);
		$this->getPeriodLog($ago, $actions);

		//display
		echo '<h4>Yearly Analyzer (' . date("Y/m/d", $ago) . ' - ' . date('Y/m/d'). ')</h4>';
		$this->render($actions);

	}

	function getPeriodLog($periodStartSecond,&$actions){
		//echo "a week ago =" .$weekAgo .'<br/>';
		$dir = dir($this->PATH_LOGS);
		while (($filename = $dir->read()) !== false)
		{
			//within this week
			$logDate = basename($filename,'.log');
			$logDateSeconds = strtotime($logDate);
			if(!$logDateSeconds)
				continue;
			if($periodStartSecond > $logDateSeconds )
				continue;
			//echo $filename . " = " . $logDateSeconds . '<br/>';

			//		
			$filename =$this->PATH_LOGS . $filename; 
			$file = fopen($filename, "r") or false;
			if(!$file)
				continue;

			while(!feof($file))
				$this->parse($actions,fgets($file));

			fclose($file);
		}

		$dir->close();

	}

	function parse(&$actions, $line){
		$values = explode(',',$line);
		if(count($values) < 5)
			return;
		$key = $values[2] . '/' . $values[3];
		if(!isset($actions[$key]))
			$actions[$key] = 0;
		$actions[$key]++;
	}


	function getDate($date){
	}

	function getMonth($month){
	}

	function getSeason($season){

	}

	function getYear($year){
	}
}
