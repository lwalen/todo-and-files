<?php

require_once "common.php";

$classes = queryClasses();
$uptime = getUptime();

writeHead("walen.me");
?>
<body>
	<!-- begin title and uptime box -->
	<div class='section'>
		<div class='logout'>
			<a href='/index.php?logout'>logout</a>
		</div>
		<h1><?= gethostname() ?></h1>
		<p class='uptime'><?= $uptime ?></p>
	</div>
	<!-- end title and uptime box -->


	<!-- begin classes -->
	<div class='section'>
<?php
if( empty($classes) ) {
?>
		<p class='no_content'>No classes</p>
<?php
} else {
?>
		<div id='notes'>
<?php
	foreach( $classes as $class )
	{
		$id = $class->id;
		$name = strtolower($class->name);
		$department = $class->department;
		$number = $class->number;
		$abbreviation = $class->abbreviation;
?>
	<a href='files.php?d=/documents/<?= str_replace(' ', '_', $name) ?>' title='<?= $department, " ", $number ?>'>
		<div class='name'><?= $name ?></div>
	</a>
<?php
	}
?>
		</div>
<?php
}
?>
	</div>
	<!-- end classes -->


	<!-- begin todo -->
	<div class='section'>
		<div id='todo'>
			<div id='items'>
<?php include "todo.php" ?>
			</div> <!-- end items -->

<div id='add_item'>
	<form>
		<input type='text' id='add_description' />
<?php
if (count($classes) > 0) {
?>
		<select id='add_class'>
			<option></option>
<?php
	foreach( $classes as $class ) {
		$id = $class->id;
		$department = $class->department;
		$number = $class->number;
		$abbreviation = $class->abbreviation;
		echo "			<option value='$id'>$abbreviation</option>\n";
	}	
?>
		</select>
<?php
}
?>
		<input type='text' id='add_due_date' placeholder='mm.dd.yy' size='8'/>
		<input type='button' id='submit' value='Add' disabled/>
	</form>
</div>
<!-- it's gotta exist somewhere -->
<div id='due_date_calendar'></div>
<input type='button' value='Remove complete' class='remove_complete'/>

		</div>
	</div>
	<!-- end todo -->


	<!-- begin files -->
	<div class='section'>
		<div id='files'>

<?php
$files = [];
// prevent getting classes

for ($i = 0; $i < count($classes); $i++) {
	$classes[$i] = str_replace(' ', '_', strtolower($classes[$i]->name));
}

//		while (($file = readdir($handle))) {
foreach (getFiles('/home/lars/documents/') as $file) {
	if ($file != "public" && !in_array($file, $classes)) {
		$files[] = $file;
	}
}
$public_files = getPublicFiles();

if (empty($files) && empty($public_files)) {
	echo "			<span class='no_content'>no files</span>\n";
} 

if (!empty($files)) {
	printFiles("/home/lars", "/documents/", $files, " ", false);
?>
		<span><a href='files.php'>~</a></span>
<?php
}

if( !empty( $public_files ) ) 
{
	echo "<div id='public_files'>Public</div>";
	foreach( $public_files as $file )
	{
		// Markdown files over http to use Chrome extension
		if (preg_match('/.*\.md$/', $file)) {
			echo "			<span><a href='http://walen.me/public/$file'>$file</a></span>\n";
		} else {
			echo "			<span><a href='/public/$file'>$file</a></span>\n";
		}
	}
}
?>
		</div>
	</div>
	<!-- end files -->

</body>
</html>
