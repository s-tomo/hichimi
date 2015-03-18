<?php

namespace Hichimi\Unite;

use Hichimi\Core\Router;

class Resource
{
    private $root;
    private $controller;

    function __construct(array $root, $controller)
    {
        $this->root = $root;
        $this->controller = $controller;
    }

    /**
     * @param $pattern
     * @param $controller_method
     * @param array $placeholder
     * @return Resource
     */
    public function get($pattern, $controller_method, array $placeholder = null)
    {
        return $this->register("get", ...func_get_args());
    }

    /**
     * @param $pattern
     * @param $controller_method
     * @param array $placeholder
     * @return Resource
     */
    public function post($pattern, $controller_method, array $placeholder = null)
    {
        return $this->register("post", ...func_get_args());
    }

    /**
     * @param $pattern
     * @param $controller_method
     * @param array $placeholder
     * @return Resource
     */
    public function put($pattern, $controller_method, array $placeholder = null)
    {
        return $this->register("put", ...func_get_args());
    }

    /**
     * @param $pattern
     * @param $controller_method
     * @param array $placeholder
     * @return Resource
     */
    public function delete($pattern, $controller_method, array $placeholder = null)
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
    private function register($method, $pattern, $action, array $placeholder = null)
    {
        $controller = [$this->controller, $action];
        $patterns = $pattern ? array_merge($this->root, Router::parse($pattern, $placeholder)) : $this->root;
        Router::controller($method, $patterns, $controller);
        return $this;
    }
}