<?php

namespace Hichimi\Util;

class Dot
{
    static function get(array $arr, $keys, $def = null)
    {
        $keys = explode(".", $keys);
        foreach ($keys as $key) {
            if (!isset($arr[$key]))
                return $def;
            $arr = $arr[$key];
        }
        return $arr;
    }

    static function &set(array &$arr, $keys, $value = null)
    {
        $keys = explode(".", $keys);
        foreach ($keys as $key) {
            if(!is_array($arr))
                $arr = [ $key => [] ];
            else if (!isset($arr[$key]))
                $arr[$key] = [];
            $arr =& $arr[$key];
        }
        if (!is_null($value))
            $arr = $value;
        return $arr;
    }
}