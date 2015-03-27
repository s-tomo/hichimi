<?php

namespace Hichimi\Core;

use Hichimi\Util\Dot;
use Hichimi\Util\Redirect;

class Router
{
    private static $statics = [];
    private static $dynamics = [];

    static function action($method, array $uri, Action $action)
    {
        self::set(...func_get_args());
    }

    static function controller($method, array $uri, array $controller)
    {
        self::set(...func_get_args());
    }

    /**
     * @param $method
     * @param array $uri
     * @param $controller
     * @throws \Exception
     */
    static function set($method, array $uri, $controller)
    {
        list($type, $path, $argv) = self::parse(...$uri);
        switch ($type) {
            case 'dynamic':
                Dot::set(self::$dynamics, "{$path}.{$method}", [$controller, $argv]);
                break;
            case 'static':
            default:
                Dot::set(self::$statics, "{$path}.{$method}", $controller);
                break;
        }
    }

    /**
     * @param $uri
     * @param $method
     * @return array
     * @throws \Hichimi\Abort\HttpAbort
     */
    static function search($uri, $method)
    {
        if (isset(self::$statics[$uri])) {
            $controller = Dot::get(self::$statics, "{$uri}.{$method}");
            if (is_null($controller))
                Redirect::abort(405);
            return [$controller, []];
        }
        foreach (self::$dynamics as $pattern => $inf) {
            $mat = [];
            if (preg_match("@{$pattern}@", $uri, $mat)) {
                if (!isset($inf[$method]))
                    Redirect::abort(405);
                list($controller, $_arg) = $inf[$method];
                return [$controller, array_slice($mat, 1)];
            }
        }
        Redirect::abort(404);
    }

    /**
     * @param $uri
     * @param array $placeholder
     * @return array
     * @throws \Exception
     */
    private static function parse($uri, array $placeholder = null)
    {
        if (is_null($placeholder) or count($placeholder) < 1)
            return ['static', $uri, []];
        $patterns = explode('/', self::trim($uri));
        $argv = [];
        foreach ($patterns as $i => $param) {
            if (substr($param, 0, 1) === ':') {
                $name = substr($param, 1);
                if (isset($placeholder[$name])) {
                    $type = $placeholder[$name];
                } else if (strpos($name, '@')) {
                    list($name, $type) = $name;
                } else {
                    throw new \Exception('');
                }
                $patterns[$i] = '(' . self::placeholder($type) . ')';
                $argv[] = $name;
            }
        }
        return ['dynamic', implode('/', $patterns), $argv];
    }

    /**
     * @param $uri
     * @return string
     */
    private static function trim($uri)
    {
        if (substr($uri, -1) === '/')
            $uri = substr($uri, 0, -1);
        if (substr($uri, 0, 1) === '/')
            $uri = substr($uri, 1);
        return $uri;
    }

    /**
     * @param $ps
     * @return string
     */
    private static function placeholder($ps)
    {
        if (is_array($ps)) {
            return implode('|', $ps);
        }
        switch ($ps) {
            case 'int':
                return '\d+';
            case 'string':
                return '\w+';
            case 'path':
                return '\S+';
            default:
                return '\w+';
        }
    }
}