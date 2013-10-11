<?php

	require "/srv/http/todo-list-and-file-browser/public/common.php";

	$db = connectToDB();

	$id = $_POST['id'];
	$description = $_POST['description'];
	$complete = $_POST['complete'];

	$query  = "UPDATE todo ";
	$query .= "SET complete=$complete ";
	$query .= "WHERE id=$id;";

	$result = mysqli_query($db, $query);
?>
