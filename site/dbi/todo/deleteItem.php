<?php
	require "/home/web/site/common.php";

	$id = $_REQUEST['id'];
	
	$query  = "DELETE FROM todo WHERE id = $id; ";
	mysql_query($query);

?>
