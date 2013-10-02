<?php

class Course {

	public $id = "";
	public $department = "";
	public $number = "";
	public $name = "";
	public $abbreviation = "";

	function __construct($id,
								$department,
								$number,
								$name,
							  	$abbreviation )
	{
		$this->id = $id;
		$this->department = $department;
		$this->number = $number;
		$this->name = $name;
		$this->abbreviation = strtolower($abbreviation);
	}
}

?>
