<?php

	require_once "common.php";

	$classes = queryClasses();

	$uptime = getUptime();

	writeHead();
?>
	<script src="js/jquery-1.7.2.min.js" type="text/javascript"></script>
	<script src="js/jquery-ui-1.8.23.custom.min.js" type="text/javascript"></script>
	<script src="js/todo.js" type="text/javascript"></script>
	<title>walen.me</title>
</head>
<body>
	<!-- begin title and uptime box -->
	<div class='section'>
		<div id='logout'>
			<a href='/index.php?logout'>logout</a>
		</div>
		<h1><?= gethostname() ?></h1>
		<p id='uptime'><?= $uptime ?></p>
	</div>
	<!-- end title and uptime box -->


	<!-- begin classes -->
	<div class='section'>
<?php
	if( empty($classes) ) {
?>
		<p class='no_classes'>No classes</p>
<?php
	} else {
?>
		<div id='notes'>
<?php
		foreach( $classes as $class )
		{
			$id = $class->id;
			$name = strtolower($class->name);
			$department = strtolower($class->department);
			$number = $class->number;
			$abbreviation = $class->abbreviation;
?>
	<a href='files.php?d=documents/<?= str_replace(' ', '_', $name) ?>'>
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
		<input type='button' id='submit' value='Add'/>
	</form>
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
	$files = getFiles('/home/lars/documents/');
	$public_files = getFiles('/home/web/site/public/');

	if( empty( $files ) && empty( $public_files ) )
	{
		echo "			<span id='no_files'>no files</span>\n";
	}

	if( !empty( $files ) ) 
	{
		$do_not_show = array();

		foreach( $files as $file )
		{
			$class_names = [];
			foreach ($classes as $class) {
				$class_names[] = $class->name;
			}

			if (!in_array($file, $do_not_show) && !in_array(ucfirst($file), $class_names)) {

				if( is_dir("/home/lars/documents/".$file) ) {
?>
		<span><a href='files.php?d=documents/<?= $file ?>'><?= $file ?>/</a></span>
<?php
				} else if(preg_match( '/.*\.pdf$/', $file) ) {

					$parts = explode('.', $file);
					$file = $parts[0];
					if( is_file("/home/lars/documents/".$file.".tex") ) {
						echo "		<span><a href='files.php?d=documents/&f=$file.pdf'>$file.pdf</a>";
						echo " [<a href='files.php?d=documents/&f=$file.tex'>.tex</a>]</span>\n";

						$do_not_show[] = $file.".tex";
					} else {
						echo "		<span><a href='files.php?d=documents/&f=$file.pdf'>$file.pdf</a></span>\n";
					}

				} else {

					echo "		<span><a href='files.php?d=documents/&f=$file'>$file</a></span>\n";

				}
			}
		}
	}
	
	if( !empty( $public_files ) ) 
	{
		echo "<div id='public_files'>Public</div>";
		foreach( $public_files as $file )
		{
			echo "			<span><a href='/public/$file'>$file</a></span>\n";
		}
	}

?>
		</div>
	</div>
	<!-- end files -->

</body>
</html>
