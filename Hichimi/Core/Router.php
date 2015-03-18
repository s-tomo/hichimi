<?php

namespace Hichimi\Core;

use Hichimi\Util\Dot;
use Hichimi\Util\Redirect;

class Router
{
    private static $statics = [];
    private static $patterns = [];

    static function action($method, array $pattern, Action $action)
    {
        self::set(...func_get_args());
    }

    static function controller($method, array $pattern, array $controller)
    {
        self::set(...func_get_args());
    }

    static function set($method, array $patterns, $controller)
    {
        $len = count($patterns);
        $path = "{$len}." . implode(".", $patterns) . ".{$method}";
        if (Dot::get(self::$patterns, $path))
            throw new \Exception();
        Dot::set(self::$patterns, $path, $controller);
    }

    static function search($uri, $method)
    {
        $uri = self::explode($uri);
        $len = count($uri);
        $patterns = Dot::get(self::$patterns, $len, []);
        $argv = [];
        foreach ($uri as $word) {
            if (isset($patterns[$word])) {
                $patterns = $patterns[$word];
            } else {
                foreach (array_keys($patterns) as $key) {
                    if (substr($key, 0, 1) === "@" and preg_match($key . "@", $word)) {
                        $patterns = $patterns[$key];
                        $argv[] = $word;
                        continue 2;
                    }
                }
                Redirect::abort(404);
            }
        }
        if (!isset($patterns[$method]))
            Redirect::abort(405);
        return [$patterns[$method], $argv];
    }

    static function parse($uri, array $placeholder = null)
    {
        $patterns = self::explode($uri);
        if (is_null($placeholder))
            return $patterns;
        foreach ($placeholder as $key => $value)
            $placeholder[$key] = self::placeholder($value);
        foreach ($patterns as $i => $name) {
            if (substr($name, 0, 1) === ":") {
                if (isset($placeholder[$name]))
                    throw new \Exception();
                $val = $placeholder[substr($name, 1)];
                $_REQUEST[$name] = $val;
                $patterns[$i] = "@" . $val;
            } else
                $patterns[$i] = $name;
        }
        return $patterns;
    }

    private static function explode($uri)
    {
        if (substr($uri, -1) === "/")
            $uri = substr($uri, 0, -1);
        if (substr($uri, 0, 1) === "/")
            $uri = substr($uri, 1);
        return explode("/", $uri);
    }

    private static function placeholder($ps)
    {
        if (is_array($ps)) {
            return implode("|", $ps);
        }
        switch ($ps) {
            case "int":
                return "[0-9]+";
            case "string":
                return "\\w+";
            default:
                throw new \Exception();
        }
    }
}