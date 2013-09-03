<?php

class Person {

	public $id = "";
	public $name = "";
	public $website = "";

	function __construct($id,
								$name,
							  	$website)
	{
		$this->id = $id;
		$this->name = $name;
		$this->website = $website;
	}
}

?>
