<?php
	require "/home/web/site/common.php";

	$db = connectToDB();

	$query  = "DELETE FROM todo WHERE complete = 1; ";
	mysqli_query($db, $query);

?>
