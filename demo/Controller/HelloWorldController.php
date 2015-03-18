<?php

use Hichimi\Base\Controller;

/**
 * Class HelloWorldController
 *
 * @filter test:controller
 */
class HelloWorldController extends Controller {
    protected $params = [
        "age" => ["validation"=>["number", "required"]],
        "sex" => ["validation"=>["string"]]
    ];
    /**
     * @param $name
     * @return string
     *
     * @filter test:method
     * @input age,sex
     */
    public function index($name) {
        return "Hello World, {$name}!!";
    }
    public function error() {
        throw new Exception(\Hichimi\Util\URL::$root);
    }
}