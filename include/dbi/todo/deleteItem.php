<?php
	require "/home/web/site/dbi/db.inc";

	$id = $_REQUEST['id'];
	
	$query  = "DELETE FROM todo WHERE id = $id; ";
	mysql_query( $query );

?>
