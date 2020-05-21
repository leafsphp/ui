<?php
require "./src/UI.php";

$ui = new Leaf\UI;

$ui::render((
	$ui::body([
		$ui::h2("This is h2")
	])
));
