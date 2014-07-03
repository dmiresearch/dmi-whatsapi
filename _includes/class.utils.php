<?php

	// class functions
	
	class Utils
	{
		function __construct()
		{
		}
		function generateTimestamp($date)
		{
			list($day, $month, $year, $hour, $minute) = split('[/ :]', $date); 
			//The variables should be arranged according to your date format and so the separators
			$timestamp = date('Y-m-d G:i:s', mktime($hour, $minute, 0, $month, $day, $year));
			return $timestamp;
		}
	}
	//$test = new Utils();
	//echo date("m/d/Y G:i:s", $test->generateTimestamp("30/07/2010 13:24"));
?>