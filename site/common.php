<?php

define("BASEPATH", "/home/web/site/");
define("INC", "/home/web/include/");

if (!(defined("NO_LOGIN") && NO_LOGIN)) {
	require BASEPATH."login.php";
}

require INC."dbi/db.inc";

require INC."course.inc";
require INC."dbi/todo/item.inc";
require INC."dbi/todo/getItems.php";

function queryClasses() {

	$courseList = array();

	$query  = "SELECT * FROM courses ";
	$query .= "ORDER BY number";

	$result = mysql_query($query);

	while( $row = mysql_fetch_array($result) ) {
		$course = new Course( $row['id'],
			$row['department'],
			$row['number'],
			$row['name'], 
			$row['abbreviation'] );
		$courseList[] = $course;
	}

	return $courseList;
}

function getFiles( $directory ) {

//	if( $handle = opendir("/home/lars/documents/".$directory) ) {
	if( $handle = opendir($directory) ) {
		$files = array();

		// prevent getting directories for current classes
		/*
		$classes = queryClasses();
		$course_pattern = "/";
		foreach( $classes as $key => $class ) {
			if( $key != 0 ) {
				$course_pattern .= "|";
			}
			//$course_pattern .= strtolower($class->getDepartment()).$class->getNumber();
			$course_pattern .= "^".strtolower($class->getAbbreviation());
			$course_pattern .= "|";
			$course_pattern .= strtolower($class->getName());
		}
		$course_pattern .= "/";

		$course_pattern = str_replace(' ', '_', $course_pattern);
		 */
		// prevent getting parent directory, current directory, and hidden directories
		$misc_pattern = '/^\..*$|\.php/';

		while( ($file = readdir($handle)) ) {
			//	if ( !preg_match($misc_pattern, $file) && !preg_match($course_pattern, $file) && $file != "public" )
			if ( !preg_match($misc_pattern, $file) && $file != "public")
			{
				$files[] = $file;
			}
		}
		natsort($files);
		closedir($handle);
		return $files;
	} else {
		echo "Could not retrieve files";
	}

}

function getUptime() {
	$file = @fopen('/proc/uptime', 'r');
	if (!$file) return 'Opening of /proc/uptime failed!';
	$data = @fread($file, 128);
	if ($data === false) return 'fread() failed on /proc/uptime!';
	$upsecs = (int)substr($data, 0, strpos($data, ' '));
	$up = Array (
		'days' => floor($data/60/60/24),
		'hours' => $data/60/60%24,
		'minutes' => $data/60%60,
		'seconds' => $data%60
	);
	$uptime  = "";
	if( $up['days'] != 0 ) {
		$uptime .= $up['days'];
		$uptime .= $up['days'] == 1 ? " day " : " days ";
	}
	if( $up['hours'] != 0 ) {
		$uptime .= $up['hours'];
		$uptime .= $up['hours'] == 1 ? " hour " : " hours ";
	}
	if( $up['minutes'] != 0 ) {
		$uptime .= $up['minutes'];
		$uptime .= $up['minutes'] == 1 ? " minute" : " minutes";
	}

	return $uptime;
}

function writeHead() {
?>

<!DOCTYPE html>
<head>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
	<link href="/favicon.png" rel="icon" type="image/png" />
	<link href="/css/main.css" rel="stylesheet" type="text/css" />
	<link href="/css/custom-theme/jquery-ui-1.8.23.custom.css" rel="stylesheet" type="text/css" />
	<link href="/css/todo.css" rel="stylesheet" type="text/css" />
	<link href='//fonts.googleapis.com/css?family=Open+Sans:300,600' rel='stylesheet' type='text/css'>

<?php
}
?>
