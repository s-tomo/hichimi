<?php

namespace Hichimi\Unite;

use Hichimi\Core\Router;
use Hichimi\Core\Action;
use Hichimi\Response\StaticFile;

class Route
{
    /**
     * @param $pattern
     * @param callable $action
     * @param array $placeholder
     * @return Action
     */
    function get($pattern, \Closure $action, array $placeholder = null)
    {
        return $this->register("get", ...func_get_args());
    }

    /**
     * @param $pattern
     * @param callable $action
     * @param array $placeholder
     * @return Action
     */
    function post($pattern, \Closure $action, array $placeholder = null)
    {
        return $this->register("post", ...func_get_args());
    }

    /**
     * @param $pattern
     * @param callable $action
     * @param array $placeholder
     * @return Action
     */
    function put($pattern, \Closure $action, array $placeholder = null)
    {
        return $this->register("put", ...func_get_args());
    }

    /**
     * @param $pattern
     * @param callable $action
     * @param array $placeholder
     * @return Action
     */
    function delete($pattern, \Closure $action, array $placeholder = null)
    {
        return $this->register("delete", ...func_get_args());
    }

    /**
     * @param $method
     * @param $pattern
     * @param callable $action
     * @param array $placeholder
     * @return Action
     */
    private function register($method, $pattern, \Closure $action, array $placeholder = null)
    {
        $action = new Action($action);
        Router::action($method, Router::parse($pattern, $placeholder), $action);
        return $action;
    }

    /**
     * @param $pattern
     * @param $controller
     * @param array $placeholder
     * @return Resource
     */
    function resource($pattern, $controller, array $placeholder = null)
    {
        return new Resource(Router::parse($pattern, $placeholder), $controller);
    }

    function statics($pattern, $path)
    {
        $action = new Action(function() use ($path) {

        });
        return;
    }
}