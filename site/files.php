<?php

require_once "/home/web/site/common.php";

$basepath = "/home/lars/";

if( isset( $_GET['d'] ) ) {
	$dir = $_GET['d'];
	if( preg_match( '/\.\./', $dir ) ) {
		exit(1);
	}
} else {
	$dir = "";
}

// make sure directory name ends in /
if( !preg_match( "/^.*\/$/", $dir ) ) {
	$dir .= "/";
}

if( isset( $_GET['f'] ) ) {
	$file = $_GET['f'];

	// output certain files as plain text
	if( preg_match( '/^.*\.(tex|py|s|sh)$/', $file ) ) {
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


writeHead($dir);
?>
<body>

	<div class='section'>
		<h1><?php echo $dir ?></h1>
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
