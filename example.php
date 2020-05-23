<?php
require "./src/UI.php";
require "./src/UI/Template.php";
require "./src/UI/WynterCSS.php";

// $ui = new Leaf\UI;

// $ui::render((
// 	$ui::body([
// 		$ui::h2("This is h2")
// 	])
// ));

// $ui = new \Leaf\UI\Template;

// $arr = [
// 	["name" => "Michael"],
// 	["name" => "Seth"],
// 	["name" => "Mychi"],
// 	["name" => "Darko"],
// 	["name" => "Duodu"]
// ];

// $c = 3;

// $ui::render((
// 	$ui::_template("Title Here", [
// 		$ui::_row([
// 			$ui::loop($arr, function($value) use($ui, $c) {
// 				return (
// 					$ui::div([], [
// 						$ui::h2($value["name"]),
// 						$ui::p("Something Really Interesting"),
// 						$ui::if($c == 2, (
// 							$ui::div([], "This is an if statement")
// 						), $ui::unless($c == 3, (
// 							$ui::div([], "This is an else if block")
// 						), (
// 							$ui::div([], "This is the else block")
// 						))),
// 						$ui::button("Click Me!!")
// 					])
// 				);
// 			}),
// 		]),
// 	])
// ));

$ui = new Leaf\UI\WynterCSS;

$html = $ui::body([
	$ui::_container([
		$ui::h2("Avatar"),
		$ui::_avatar("", "MD", ["size" => "md", "presence" => "away", "badge" => "700"]),
		$ui::hr(),
		$ui::h2("Badge"),
		$ui::_badge("8000", [], "Notifications"),
		$ui::hr(),
		$ui::h2("Breadcrumb"),
		$ui::_breadcrumb([
			"profile" => "/profile", 
			"settings" => "/profile/settings",
			"security" => "/profile/settings/security"
		]),
		$ui::hr(),
		$ui::h2("Buttons"),
		$ui::_button("Wynter Button", ["wyn:loading" => "true"]),
		$ui::_button("Primary Button", ["variant" => "primary"]),
		$ui::_button("Link Button", ["variant" => "link"]),
		$ui::_btnGroup([
			$ui::_button("Button 1"),
			$ui::_button("Button 2"),
			$ui::_button("Button 3"),
			$ui::_button("Button 4"),
		]),
		$ui::_fab(["icon" => "people"]),
		$ui::hr(),
		$ui::h2("Cards"),
		$ui::_card([], [
			$ui::_container([
				$ui::div([], "Something Interesting")
			])
		]),
		$ui::_xCard(["style" => "width: 300px"], [
			"image" => "./1.jpg",
			"title" => "Something",
			"subtitle" => "Subtitle",
			"body" => "Lorem ipsum, dolor sit amet consectetur adipisicing elit. Doloribus ratione cupiditate officia animi itaque esse saepe et officiis possimus dolorem, a architecto facere veritatis adipisci sed eos aliquam alias vitae.",
			"footer" => $ui::div([], [
				$ui::_button("Button", ["variant" => "primary"])
			]),
		]),
		$ui::hr(),
		$ui::h2("Chips"),
		$ui::_chip("Something"),
		$ui::_chip("Food", [
			$ui::a(["href" => "#", "wyn:btn-clear" => "", "aria-label" => "Close", "role" => "button"])
		]),
		$ui::_carousel([
			$ui::_carouselItem("", "", $ui::img("./1.jpg", ["class" => "img-responsive rounded", "style" => "width: 100%;height: 100% !important;"])),
			$ui::_carouselItem("", "", $ui::img("./2.jpg", ["class" => "img-responsive rounded", "style" => "width: 100%;height: 100% !important;"])),
			$ui::_carouselItem("", "", $ui::img("./1.jpg", ["class" => "img-responsive rounded", "style" => "width: 100%;height: 100% !important;"])),
			$ui::_carouselItem("", "", $ui::img("./2.jpg", ["class" => "img-responsive rounded", "style" => "width: 100%;height: 100% !important;"])),
		]),
		$ui::hr(),
		$ui::br(),
		$ui::br(),
	])
]);

$ui::render($html);
