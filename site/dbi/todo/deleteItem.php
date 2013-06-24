<?php
	require_once "/home/web/include/dbi/db.inc";

	$id = $_REQUEST['id'];
	
	$query  = "DELETE FROM todo WHERE id = $id; ";
	mysql_query( $query );

?>
