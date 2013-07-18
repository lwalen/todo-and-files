<?php

require_once "common.php";

$todoItems = queryItems();

$courses = queryClasses();

if ($todoItems) {

	foreach ($todoItems as $item) {

		$id = $item->id;
		$description = $item->description;
		$complete = $item->complete;
		$course = $item->course;
		$department = $item->department;
		$abbreviation = $item->abbreviation;
		$due_date = $item->due_date;
?>
<div id='item_<?= $id ?>' class='item<?= $complete ? ' complete' : '' ?>'>
	<input type='checkbox'<?= $complete ? " checked" : "" ?> />
	<div class='description'>
<?php 
echo preg_replace('@(http)?(s)?(://)?(([-\w]+\.)+([^\s]+)+[^,.\s])@', '<a href="http$2://$4">$1$2$3$4</a>', $description);

if ($abbreviation) {
	echo "<span class='course' title='$department $course'>$abbreviation</span>";
} 
?>
	</div>
	<div class='delete'></div>
	<div class='due_date'><?= $due_date ?></div>
</div>
<?php
	}

} else {
?>
	<p class='no_content'>Nothing to do</p>
<?php
}
?>
