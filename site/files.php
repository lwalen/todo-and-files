<?php

	require_once "/home/web/site/common.php";

	$isClass = FALSE;
	$basepath = "/home/lars/documents/";

	if( isset( $_GET['f'] ) ) {
		$file = $_GET['f'];
		$dir = "";
		if( isset( $_GET['d'] ) ) {
			$dir = $_GET['d'];
			if( preg_match( '/\.\./', $dir ) ) {
				exit(1);
			}
		}

		// output .tex and .py files as plain text
		if( preg_match( '/^.*\.(tex|py|s)$/', $file ) ) {
			header('Content-Type: text/plain');
		} else {
			header('Content-Type: '.mime_content_type($basepath.$dir.$file));
		}
		header('Content-Disposition: inline; filename='.$file);
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: '.filesize($basepath.$dir.$file));
		header('Accept-Ranges: bytes');
		readfile($basepath.$dir.$file);
		exit;
	}


	if( isset( $_GET['d'] ) ) {
		$dir = $_GET['d'];
		if( preg_match( '/\.\./', $dir ) ) {
			exit(1);
		}

		if( preg_match( '/^[a-zA-Z]{4}[0-9]{3}$/', $dir ) ) {
			$courseDeptNumber = $dir;

			$courses = queryClasses();
			$courseName = "";

			foreach( $courses as $course ) {
				if( $courseDeptNumber == strtolower($course->getDepartment()).$course->getNumber() ) {
					$courseName = $course->getName();
					$courseDept = $course->getDepartment();
					$courseNumber = $course->getNumber();
					$dir = $courseDeptNumber;

					$isClass = TRUE;
				}
			}
		}
	}

	// make sure directory name ends in /
	if( !preg_match( "/^.*\/$/", $dir ) ) {
		$dir .= "/";
	}

?>
<html>
<head>
	<title><?php echo $dir ?></title>
	<link href="/css/main.css" rel="stylesheet" type="text/css" />
	<link href='//fonts.googleapis.com/css?family=Open+Sans:300,400' rel='stylesheet' type='text/css'>
</head>
<body>

	<div class='section'>
		<h1><?php echo $isClass ? $courseName : $dir ?></h1>
	</div>

	<div class='section files'>
<?php
	$files = getFiles($basepath.$dir);

	$do_not_show = array();
	$path = array();

	$path = explode( '/', $dir );
	array_pop($path);

	foreach( $files as $file ) {

		if( !in_array( $file, $do_not_show ) ) {

			if( is_dir($basepath.$dir.$file) ) {

				echo "		<p><a href='?d=$dir$file'>$file/</a></p>\n";

			} else if(preg_match( '/.*\.pdf$/', $file) ) {

				$parts = explode('.', $file);
				$file = $parts[0];

				if( is_file($basepath.$dir.$file.".tex") ) {
					echo "		<p><a href='?d=$dir&f=$file.pdf'>$file.pdf</a>";
					echo " [<a href='?d=$dir&f=$file.tex'>.tex</a>]</p>\n";

					$do_not_show[] = $file.".tex";
				} else {
					echo "		<p><a href='?d=$dir&f=$file.pdf'>$file.pdf</a></p>\n";
				}

			} else {

				echo "		<p><a href='?d=$dir&f=$file'>$file</a></p>\n";

			}
		}

	}

?>
	</div>

<!-- Breadcrumbs -->
	<div class='section'>
		<a href='/index.php'>home</a>
<?php
	for( $i = 0; $i < count($path) - 1; $i++ ) {  
		echo "		<div class='right_arrow'></div>\n";
		echo "		<a href='?d=";
		$path2[$i] = "";
		for( $j = 0; $j <= $i; $j++ ) {
			echo $path[$j]."/";
		}
		echo "'>".$path[$i]."</a>\n";
	}
	echo "		<div class='right_arrow'></div>\n";
	echo "		".$path[count($path)-1]."\n";
?>
	</div>

</body>
</html>
