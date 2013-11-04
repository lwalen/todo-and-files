<?php

require_once "common.php";

$todoItems = queryItems();

$courses = queryClasses();

$no_date = true;

$previous_date = null;

function week($date) {
	if ($date == "") {
		return null;
	}

	$date = explode(".", $date);
	$date = mktime(0, 0, 0, $date[0], $date[1], $date[2]);
	return (int)date('W', $date);
}

deleteOldComplete();

if ($todoItems) {

	foreach ($todoItems as $item) {
		$id = $item->id;
		$description = $item->description;
		$complete = $item->complete;
		$course = $item->course;
		$department = $item->department;
		$abbreviation = $item->abbreviation;
		$due_date = $item->due_date;

		if ($previous_date != null
		    && (week($previous_date) < week($due_date)
		    || week($due_date) == null)) {
			echo "<hr>\n";			
		}

		$modifiers = [];
		if ($complete) {
			$modifiers[] = 'complete';
		}

		if ($due_date && $due_date < date("m.d.y")) {
			$modifiers[] = 'past_due';
		}
?>
<div id='item_<?= $id ?>' class='item <?= join($modifiers, " ") ?>'>
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
		$previous_date = $due_date;
	}
} else {
?>
	<p class='no_content'>Nothing to do</p>
<?php
}
?>
