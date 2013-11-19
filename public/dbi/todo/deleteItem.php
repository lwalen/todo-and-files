<?php
	require "/srv/http/todo-and-files/public/common.php";

	$db = connectToDB();

	$id = $_REQUEST['id'];
	
	$query  = "DELETE FROM todo WHERE id = $id; ";
	mysqli_query($db, $query);

?>
