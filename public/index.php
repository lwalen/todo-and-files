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
			<a href='/login.php?logout'>logout</a>
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
	<table>
		<tr>
			<td class='expand'><input type='text' id='add_description' /></td>
<?php
if (count($classes) > 0) {
?>
			<td class='shrink'>
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
			</td>
<?php
}
?>
			<td class='shrink'><input type='text' id='add_due_date' placeholder='date' size='8'/></td>
			<td class='shrink'><input type='button' id='submit' value='Add' disabled/></td>
		</tr>
	</table>
</div>

<!-- it's gotta exist somewhere -->
<div id='due_date_calendar'></div>

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
?>
	<span class='no_content'>no files</span>
<?php
} 

if (!empty($files)) {
	printFiles("/home/lars", "/documents/", $files, " ", false);
?>
		<span><a href='files.php'>~</a></span>
<?php
}

if( !empty( $public_files ) ) 
{
?>
		<div id='public_files'>Public</div>
<?php
	foreach( $public_files as $file ) {
		$url = "/public/";

		// Markdown files over http to use Chrome extension
		if (preg_match('/.*\.md$/', $file)) {
			$url = "http://walen.me$url$file";
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
?>
		</div>
	</div>
	<!-- end files -->

</body>
</html>
