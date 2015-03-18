<?php

namespace Hichimi\Util;

class Request
{
    static private $data = null;

    private static function init()
    {
        if (is_null(self::$data)) {
            self::$data = self::sanitize($_REQUEST);
            $req = [];
            parse_str(file_get_contents('php://input'), $req);
            foreach ($req as $key => $val) {
                self::$data[$key] = self::sanitize($val);
            }
        }
    }

    static function get($name, $def = null)
    {
        self::init();
        return Dot::get(self::$data, $name, $def);
    }

    private static function sanitize($value)
    {
        if (is_string($value)) {
            $value = htmlspecialchars(trim($value), ENT_QUOTES, "utf-8");
        } else if (is_array($value)) {
            foreach ($value as $key => $sub_value) {
                $value[$key] = self::sanitize($sub_value);
            }
        }
        return $value;
    }

}