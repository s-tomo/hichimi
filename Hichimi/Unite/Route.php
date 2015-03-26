<?php

namespace Hichimi\Unite;

use Hichimi\Core\Router;
use Hichimi\Core\Action;
use Hichimi\Response\StaticFile;
use Hichimi\Util\URL;

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
        Router::action($method, [$pattern, $placeholder], $action);
        return $action;
    }

    /**
     * @param $pattern
     * @param $controller
     * @param array $placeholder
     * @return Resource
     */
    function resource($pattern, $controller, array $placeholder = [])
    {
        return new Resource([$pattern, $placeholder], $controller);
    }

    /**
     * @param $pattern
     * @param $path
     * @return Action
     */
    function statics($pattern, $path)
    {
        $action = new Action(function($name) use ($path) {
            return StaticFile::make(URL::merge($path, $name));
        });
        Router::action('get', [$pattern.'/:path', ['path'=>'path']], $action);
        return $action;
    }
}