<?php

define("BASEPATH", "/srv/http/todo-and-files/public/");
define("INC", "/srv/http/todo-and-files/include/");

require BASEPATH."dbi/course.inc";
require BASEPATH."dbi/person.inc";
require BASEPATH."dbi/todo/item.inc";
require BASEPATH."dbi/todo/getItems.php";
require INC."config.inc";

if (!(defined("NO_LOGIN") && NO_LOGIN)) {
	require BASEPATH."login.php";
}

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

function queryPeople() {
	$db = connectToDB();

	$peopleList = [];

	$query  = "SELECT * FROM people ";
	$query .= "ORDER BY id;";

	$result = mysqli_query($db, $query);

	while ($row = mysqli_fetch_array($result)) {
		$person = new Person($row['id'],
			$row['name'],
			$row['website']);
		$peopleList[] = $person;
	}

	return $peopleList;
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
	}
}

function getPublicFiles() {
	if ($handle = opendir("/srv/http/todo-and-files/public/public")) {
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
		echo "Could not retrieve public files";
	}
}

function printFiles($basepath, $dir, $files, $separator = "\n", $download = true) {
	$do_not_show = [];

	if ($separator == "\n") {
		$tag = "p";
	} else if ($separator == " ") {
		$tag = "span";
	}

	foreach ($files as $file) {
		if (!in_array($file, $do_not_show)) {
			$file_tex = str_replace('pdf', 'tex', $file);

			echo "<$tag class='file'>";

			// Directory
			if (is_dir($basepath.$dir.$file)) {
				echo "<a href='files.php?d=$dir$file'>$file/</a>";

				// PDF with TeX
			} else if (preg_match('/.*\.pdf$/', $file) && is_file($basepath.$dir.$file_tex)) {
				echo fileLink($dir, $file);
				echo " [<a href='files.php?d=$dir&f=$file_tex'>.tex</a>]";
				if ($download) echo downloadLink($dir, $file);
				$do_not_show[] = $file_tex;

				// Markdown files over http to use Chrome extension
			} else if (preg_match('/.*\.md$/', $file)) {
				echo "<a href='http://".DOMAIN."/files.php?d=$dir&f=$file'>$file</a>";
				if ($download) echo downloadLink($dir, $file);

				// Regular file
			} else {
				echo fileLink($dir, $file);
				if ($download) echo downloadLink($dir, $file);
			}

			echo "</$tag>\n";
		}
	}
}

function printPublicFiles($files) {
	foreach( $files as $file ) {
		$url = "/public/";

		// Markdown files over http to use Chrome extension
		if (preg_match('/.*\.md$/', $file)) {
			$url = "http://".DOMAIN.$url.$file;
		} else {
			$url .= $file;
		}
?>
			<span class='file'>
				<a href='<?= $url ?>'><?= $file ?></a>
			</span>
<?php
	}
}

function fileLink($dir, $file) {
	$link = "<a href='files.php?d=$dir&f=$file' title='$file'>";
	if (strlen($file) > 24) {
		$file = substr($file, 0, 24)."...";
	}
	return $link.$file."</a>";
}

function downloadLink($dir, $file) {
	return "<button type='submit' name='download' value='$dir$file' class='download'></button>";
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
	<script src="/js/files.js" type="text/javascript"></script>

	<?= $extra ?>

	<title><?= $title ?></title>
</head>
<?php
}

function connectToDB() {

	$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if (!$db) {
		die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
	} else {
		return $db;
	}
}

function deleteOldComplete() {
	$db = connectToDB();

	$query  = "DELETE FROM todo WHERE complete = 1 ";
	$query .= "AND due_date < CURDATE(); ";
	mysqli_query($db, $query);
}
?>
