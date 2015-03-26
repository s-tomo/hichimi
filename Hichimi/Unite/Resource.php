<?php

namespace Hichimi\Unite;

use Hichimi\Core\Router;
use Hichimi\Util\URL;

class Resource
{
    private $root;
    private $placeholder = [];
    private $controller;

    function __construct(array $uri, $controller)
    {
        list($this->root, $this->placeholder) = $uri;
        $this->controller = $controller;
    }

    /**
     * @param $pattern
     * @param $controller_method
     * @param array $placeholder
     * @return Resource
     */
    public function get($pattern, $controller_method, array $placeholder = [])
    {
        return $this->register("get", ...func_get_args());
    }

    /**
     * @param $pattern
     * @param $controller_method
     * @param array $placeholder
     * @return Resource
     */
    public function post($pattern, $controller_method, array $placeholder = [])
    {
        return $this->register("post", ...func_get_args());
    }

    /**
     * @param $pattern
     * @param $controller_method
     * @param array $placeholder
     * @return Resource
     */
    public function put($pattern, $controller_method, array $placeholder = [])
    {
        return $this->register("put", ...func_get_args());
    }

    /**
     * @param $pattern
     * @param $controller_method
     * @param array $placeholder
     * @return Resource
     */
    public function delete($pattern, $controller_method, array $placeholder = [])
    {
        return $this->register("delete", ...func_get_args());
    }

    /**
     * @param $method
     * @param $pattern
     * @param $action
     * @param array $placeholder
     * @return Resource
     */
    private function register($method, $pattern, $action, array $placeholder = [])
    {
        $controller = [$this->controller, $action];
        $patterns = [URL::merge($this->root, $pattern), array_merge($this->placeholder, $placeholder)];
        Router::controller($method, $patterns, $controller);
        return $this;
    }
}