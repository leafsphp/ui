<?php

require __DIR__ . "/../src/UI.php";

use Leaf\UI;

$styles = UI::Style([
    "body" => [
        "background" => "yellow",
        "border" => "20px solid black",
        "padding" => "50px"
    ],
    "p" => "
        border: 10px solid black;
        border-radius: 20px;
        padding: 20px;
    ",
    "@media only screen and (max-width: 500px)" => [
        "body" => [
            "background" => "green !important"
        ]
    ]
]);

$data = UI::createElement("p", "Hello");

UI::render(UI::merge($styles, $data));
