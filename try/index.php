<?php

require __DIR__ . "/../src/UI.php";

use Leaf\UI;

$scripts = UI::Script("https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js");
$vue = UI::Script(["
    var app = new Vue({
        el: '#app',
        data() {
            return {
                message: 'You loaded this page on ' + new Date().toLocaleString()
            };
        },
        methods: {
            alertSomething() {
                alert('something');
            },
        },
    });
"]);

$data = section(["id" => "app"], [
    h2("Vue + Leaf UI"),
    p("Hover your mouse over me!", [":title" => "message"]),
    button("Alert Something", ["@click" => "alertSomething"])
]);

UI::render(UI::merge($data, $scripts, $vue));
