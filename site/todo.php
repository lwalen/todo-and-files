<?php

require_once "common.php";

$todoItems = queryItems();

$courses = queryClasses();

if( $todoItems ) {

	foreach( $todoItems as $item ) {

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
	<div class='description'><?= "\n\t\t".$description ?>
<?php if( $abbreviation ) { ?>
<span class='course' title='<?= $department." ".$course ?>'><?= $abbreviation ?></span>
<?php	} ?>
	</div>
	<div class='delete'></div>
	<div class='due_date'><?= $due_date ? $due_date : '' ?></div>
</div>
<?php
	}

} else {
	echo "<p class='no_content'>Nothing to do</p>";
}
?>
