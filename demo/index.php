<?php

require "../vendor/autoload.php";

use \Hichimi\App;

$_REQUEST["_uri"] = "greet/John";
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

$router->get("count/:id", function ($id) {
    return $id;
}, ["id" => "int"]);

App::run();
