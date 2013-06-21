<?php

class Item {

	public $id = "";
	public $description = "";
	public $complete = "";
	public $course = "";
	public $department = "";
	public $abbreviation = "";
	public $due_date = "";

	function __construct($id,
								$description,
								$complete,
								$course,
								$department,
								$abbreviation,
								$due_date )
	{
		$months = array(
			'01' => 'Jan',
			'02' => 'Feb',
			'03' => 'Mar',
			'04' => 'Apr',
			'05' => 'May',
			'06' => 'Jun',
			'07' => 'Jul',
			'08' => 'Aug',
			'09' => 'Sep',
			'10' => 'Oct',
			'11' => 'Nov',
			'12' => 'Dec' );

		$this->id = $id;
		$this->description = $description;
		$this->complete = $complete;
		$this->course = $course;
		$this->department = $department;
		$this->abbreviation = $abbreviation;

		if( $due_date != "" ) {
			list( $year, $month, $date ) = explode( "-", $due_date);
			$year = substr( $year, 2 );
			$this->due_date = intval($month).".".$date.".".$year;
		} else {
			$this->due_date = $due_date;
		}
	}
}

?>
