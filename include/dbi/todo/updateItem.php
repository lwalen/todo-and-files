<?php

	require "/home/web/site/dbi/db.inc";

	$id = $_POST['id'];
	$description = $_POST['description'];
	$complete = $_POST['complete'];

	$query  = "UPDATE todo ";
	$query .= "SET complete=$complete ";
	$query .= "WHERE id=$id;";

	$result = mysql_query( $query );
?>
