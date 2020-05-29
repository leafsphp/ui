<?php
require "./src/UI.php";
require "./src/UI/Template.php";

$ui = new \Leaf\UI\Template;

$arr = [
	["name" => "Michael"],
	["name" => "Seth"],
	["name" => "Mychi"],
	["name" => "Darko"],
	["name" => "Duodu"]
];

$c = 3;

$ui::render((
	$ui::_template("Title Here", [
		$ui::_row([
			$ui::loop($arr, function($value, $key) use($ui, $c) {
				return (
					$ui::div([], [
						$ui::h2($ui::small("Name: ").$value["name"]),
						$ui::p("Something Really Interesting"),
						$ui::if($c == 2, (
							$ui::div([], "This is an if statement")
						), $ui::unless($c == 3, (
							$ui::div([], "This is an else if block")
						), (
							$ui::div([], "This is the else block")
						))),
						$ui::button("Click Me!!")
					])
				);
			}),
		]),
		$ui::_row([
			$ui::input("text", "browsers", ["list" => "browsers"]),
			$ui::datalist("browsers", ["Firefox", "Chrome", "Safari"]),
			$ui::_datalist("text", "browsers2", "browsers2", ["Firefox", "Chrome", "Opera", "Safari"])
		]),
		$ui::_row([
			$ui::_fieldset("Gender", [
				$ui::input("radio", "gender", ["value" => "male", "label" => "Male"]),
				$ui::input("radio", "gender", ["value" => "female", "label" => "Female"]),
			])
		]),
		$ui::_select([
			"ferrari" => "Ferrari",
			"mercedes" => "Mercedes",
			"porsche" => "Porsche",
		]),
		$ui::select([
			$ui::_optgroup("Sports Cars", [
				"ferrari" => "Ferrari",
				"porsche" => "Porsche",
			]),
			$ui::_optgroup("Sports Cars", [
				"mercedes" => "Mercedes",
				"bently" => "Bently",
			])
		]),
		$ui::_row([
			$ui::meter("0.8", "80%"),
			$ui::meter("6", "6/10", ["min" => "0", "max" => "10"]),
			$ui::meter("80", "very high", ["low" => "60", "high" => "80", "max" => "100"]),
		]),
		$ui::keygen("key")
	])
));
