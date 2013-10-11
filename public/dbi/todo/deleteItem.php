<?php
	require "/srv/http/todo-list-and-file-browser/public/common.php";

	$db = connectToDB();

	$id = $_REQUEST['id'];
	
	$query  = "DELETE FROM todo WHERE id = $id; ";
	mysqli_query($db, $query);

?>
