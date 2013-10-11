<?php
	require "/srv/http/todo-list-and-file-browser/public/common.php";

	$db = connectToDB();

	$query  = "DELETE FROM todo WHERE complete = 1; ";
	mysqli_query($db, $query);

?>
