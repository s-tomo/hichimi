<?php

namespace Hichimi\Util;

class Session
{
    static function clear()
    {
        $_SESSION = [];
    }

    static function has($name)
    {
        return !is_null(Dot::get($_SESSION, $name));
    }

    static function get($name, $def = null)
    {
        return Dot::get($_SESSION, $name, $def);
    }

    static function &set($name, $value = null)
    {
        return Dot::set($_SESSION, $name, $value);
    }
}