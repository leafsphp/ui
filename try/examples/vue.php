<?php

require __DIR__ . "/../src/UI.php";

use Leaf\UI;

$scripts = UI::Script("https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js");
$vue = UI::Script(["
    var app = new Vue({
        el: '#app',
        data: {
            message: 'Hello Vue!'
        }
    })
"]);

$data = UI::createElement("div", [
    "id" => "app",
    "children" => UI::createElement("p", "{{ message }}")
]);

UI::render(UI::merge($data, $scripts, $vue));
