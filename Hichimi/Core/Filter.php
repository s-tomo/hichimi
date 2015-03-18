<?php

namespace Hichimi\Core;

final class Filter
{
    private static $filters = [];

    static function set($name, \Closure $filter)
    {
        self::$filters[$name] = $filter;
    }

    static function run($name, array $arguments)
    {
        if (!isset(self::$filters[$name]))
            throw new \Exception("filter {$name} is not defined.");
        $filter = self::$filters[$name];
        $filter(...$arguments);
    }
}