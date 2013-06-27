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
		<h1><?= $dir ?></h1>
	</div>

	<div class='section files'>
<?php
$files = getFiles($basepath.$dir);

printFiles($basepath, $dir, $files);
?>
	</div>

<!-- Breadcrumbs -->
	<div class='section'>
		<a href='/index.php'>home</a>
<?php
$path = array();

$path = explode( '/', $dir );
array_pop($path);

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
