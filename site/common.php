<?php

define("BASEPATH", "/home/web/site/");
define("INC", "/home/web/include/");

if (!(defined("NO_LOGIN") && NO_LOGIN)) {
	require BASEPATH."login.php";
}

require BASEPATH."dbi/course.inc";
require INC."db.inc";
require BASEPATH."dbi/todo/item.inc";
require BASEPATH."dbi/todo/getItems.php";

function queryClasses() {
	$db = connectToDB();

	$courseList = [];

	$query  = "SELECT * FROM courses ";
	$query .= "ORDER BY number";

	$result = mysqli_query($db, $query);

	while ($row = mysqli_fetch_array($result)) {
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
	if ($handle = opendir($directory)) {
		$files = array();

		// prevent getting parent directory, current directory, and hidden files
		$misc_pattern = '/^\..*$/';

		while (($file = readdir($handle))) {
			if (!preg_match($misc_pattern, $file)) {
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

function getPublicFiles() {
	if ($handle = opendir("/home/web/site/public")) {
		$files = array();

		// prevent getting parent directory, current directory, and hidden files
		$misc_pattern = '/^\..*$/';

		while (($file = readdir($handle))) {
			if (!preg_match($misc_pattern, $file)) {
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
			$file_tex = str_replace('pdf', 'tex', $file);

			// Directory
			if (is_dir($basepath.$dir.$file)) {
				echo "		<$tag><a href='files.php?d=$dir$file'>$file/</a></$tag>\n";
				
			// PDF with TeX
			} else if (preg_match('/.*\.pdf$/', $file) && is_file($basepath.$dir.$file_tex)) {
				echo "		<$tag>", fileLink($dir, $file);
				echo " [<a href='files.php?d=$dir&f=$file_tex'>.tex</a>]</$tag>\n";
				$do_not_show[] = $file_tex;
				
			// Markdown files over http to use Chrome extension
			} else if (preg_match('/.*\.md$/', $file)) {
				echo "		<$tag><a href='http://walen.me/files.php?d=$dir&f=$file'>$file</a></$tag>\n";
				
			// Regular file
			} else {
				echo "		<$tag>", fileLink($dir, $file), "</$tag>\n";
			}
		}
	}
}

function fileLink($dir, $file) {
	$link = "<a href='files.php?d=$dir&f=$file' title='$file'>";
	if (strlen($file) > 25) {
		$file = substr($file, 0, 25)."...";
	}
	return $link.$file."</a>";
}

function getUptime() {
	$data = file_get_contents('/proc/uptime');
	if ($data === false) return 'failed to read /proc/uptime';
	$upsecs = (int)substr($data, 0, strpos($data, ' '));
	$up = Array (
		'days' => floor($data/60/60/24),
		'hours' => $data/60/60%24,
		'minutes' => $data/60%60,
		'seconds' => $data%60
	);
	$uptime = "";
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

function getBytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    switch($last) {
        // The 'G' modifier is available since PHP 5.1.0
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }

    return $val;
}

function writeHead($title, $extra="") {
?>

<!DOCTYPE html>
<head>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
	<link href="/favicon.ico" rel="shortcut icon" />
	<link href="/css/custom-theme/jquery-ui-1.8.23.custom.css" rel="stylesheet" type="text/css" />
	<link href='//fonts.googleapis.com/css?family=Open+Sans:300,600' rel='stylesheet' type='text/css'>
	<link href="/css/main.css" rel="stylesheet" type="text/css" />
	<link href="/css/todo.css" rel="stylesheet" type="text/css" />

	<script src="/js/jquery-1.7.2.min.js" type="text/javascript"></script>
	<script src="/js/jquery-ui-1.8.23.custom.min.js" type="text/javascript"></script>
	<script src="/js/todo.js" type="text/javascript"></script>

	<?= $extra ?>

	<title><?= $title ?></title>
</head>
<?php
}
?>
