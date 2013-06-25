<?php

	require "/home/web/site/common.php";

	$description = mysql_real_escape_string( $_POST['description'] );
	$course_id = $_POST['course_id'] != '' ? $_POST['course_id'] : 'NULL';
	if( $_POST['due_date'] != '' ) {
		$due_date = $_POST['due_date'];

		list( $month, $date, $year ) = explode( ".", $due_date );

		$due_date = "'20".$year."-".$month."-".$date."'";
	} else {
		$due_date = 'NULL';
	}

	$query  = "INSERT INTO todo (description, course_id, due_date) ";
	$query .= "VALUES ('$description', $course_id, $due_date)";


	$result = mysql_query( $query );
	echo $result;

	$_POST['result'] = $result;
?>
