<?php

require_once "/srv/http/todo-and-files/public/common.php";

function queryItems() {

	$db = connectToDB();

	$itemList = [];

	$query  = "SELECT t.id, t.description, t.complete, t.course_id, ";
	$query .= "c.number AS course, c.department, c.abbreviation, ";
	$query .= "t.due_date ";
	$query .= "FROM todo AS t ";
	$query .= "LEFT JOIN courses AS c ON t.course_id = c.id ";
	$query .= "ORDER BY ";
	$query .= "  CASE WHEN t.due_date is null THEN 1 ELSE 0 END, ";
	$query .= "  t.due_date; ";

	$result = mysqli_query($db, $query);

	while($row = mysqli_fetch_array($result))
	{
		$item = new Item( $row['id'],
			$row['description'],
			$row['complete'],
			$row['course'],
			$row['department'],
			$row['abbreviation'],
			$row['due_date'] );

		$itemList[] = $item;			
	}

	if( !empty($itemList) ) return $itemList;
}

?>
