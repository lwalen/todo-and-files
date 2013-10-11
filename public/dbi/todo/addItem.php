<?php

	require "/srv/http/todo-list-and-file-browser/public/common.php";

	if (!isset($_POST['description'])) {
		return;
	}

	$db = connectToDB();

	$description = mysqli_real_escape_string($db, $_POST['description']);
	$course_id = $_POST['course_id'] != '' ? $_POST['course_id'] : 'NULL';
	if ($_POST['due_date'] != '') {
		$due_date = $_POST['due_date'];

		list($month, $date, $year) = explode(".", $due_date);

		$due_date = "'20".$year."-".$month."-".$date."'";
	} else {
		$due_date = 'NULL';
	}

	$query  = "INSERT INTO todo (description, course_id, due_date) ";
	$query .= "VALUES ('$description', $course_id, $due_date)";

	$result = mysqli_query($db, $query);
	echo $result;

	$_POST['result'] = $result;
?>
