<?php

require "../vendor/autoload.php";

use \Hichimi\App;

$_REQUEST["_uri"] = "js/test.js";
$_REQUEST["age"] = "23";
$_SERVER["REDIRECT_METHOD"] = "GET";

App::load([
    "Controller" => "dir"
]);

App::register()
    ->filter("test", function ($type) {
        echo "{$type} filter!!<br>";
    })
    ->abort(404, function () {
        return "ページが見つかりません。";
    })
    ->exception("Exception", function(Exception $e) {
        return $e->getMessage();
    })
;

$router = App::route();

$router
    ->resource("greet", "HelloWorldController")
    ->get(":name", "index", ["name" => "string"])
    ->get("", "error")
;
$router->statics('css', 'static/css');
$router->statics('js', 'static/js');

$router->get("count/:id", function ($id) {
    return $id;
}, ["id" => "int"]);

App::run();
