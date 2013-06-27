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

function getFiles($directory) {

//	if( $handle = opendir("/home/lars/documents/".$directory) ) {
	if ($handle = opendir($directory)) {
		$files = array();

		// prevent getting parent directory, current directory, and hidden directories
		$misc_pattern = '/^\..*$/';

		while (($file = readdir($handle))) {
			if (!preg_match($misc_pattern, $file) && $file != "public") {
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

function printFiles($basepath, $dir, $files, $separator = "\n") {
	$do_not_show = [];

	if ($separator == "\n") {
		$tag = "p";
	} else if ($separator == " ") {
		$tag = "span";
	}


	foreach ($files as $file) {

		if (!in_array($file, $do_not_show)) {

			if (is_dir($basepath.$dir.$file)) {

				echo "		<$tag><a href='files.php?d=$dir$file'>$file/</a></$tag>\n";

			} else if(preg_match('/.*\.pdf$/', $file)) {

				$parts = explode('.', $file);
				$file = $parts[0];

				if (is_file($basepath.$dir.$file.".tex")) {
					echo "		<$tag><a href='files.php?d=$dir&f=$file.pdf'>$file.pdf</a>";
					echo " [<a href='files.php?d=$dir&f=$file.tex'>.tex</a>]</$tag>\n";

					$do_not_show[] = $file.".tex";
				} else {
					echo "		<$tag><a href='files.php?d=$dir&f=$file.pdf'>$file.pdf</a></$tag>\n";
				}
			} else {
				echo "		<$tag><a href='files.php?d=$dir&f=$file'>$file</a></$tag>\n";
			}
		}

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

function writeHead($title) {
?>

<!DOCTYPE html>
<head>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
	<link href="/favicon.png" rel="icon" type="image/png" />
	<link href="/css/main.css" rel="stylesheet" type="text/css" />
	<link href="/css/custom-theme/jquery-ui-1.8.23.custom.css" rel="stylesheet" type="text/css" />
	<link href="/css/todo.css" rel="stylesheet" type="text/css" />
	<link href='//fonts.googleapis.com/css?family=Open+Sans:300,600' rel='stylesheet' type='text/css'>
	<script src="js/jquery-1.7.2.min.js" type="text/javascript"></script>
	<script src="js/jquery-ui-1.8.23.custom.min.js" type="text/javascript"></script>
	<script src="js/todo.js" type="text/javascript"></script>
	<title><?= $title ?></title>
</head>
<?php
}
?>
