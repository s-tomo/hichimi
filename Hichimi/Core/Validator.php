<?php

namespace Hichimi\Core;

use Hichimi\Abort\ValidationAbort;
use Hichimi\Util\Request;

final class Validator
{
    private static $rules = [];

    static function set($name, \Closure $rule)
    {
        self::$rules[$name] = $rule;
    }

    static function validate($name, array $rules)
    {

        $value = Request::get($name);
        if (is_null($value) or (is_string($value) and trim($value) != "")) {
            if (in_array("required", $rules))
                throw new ValidationAbort($name, "required");
        } else {
            foreach ($rules as $rule) {
                if ($rule === "required")
                    continue;
                $rule = explode(":", $rule);
                if (!isset(self::$rules[$rule[0]]))
                    throw new \Exception("validation rule {$rule[0]} is not defined.");
                $validation = self::$rules[$rule[0]];
                $argv = [];
                if (count($rule) === 2) {
                    foreach (explode(",", $rule[1]) as $arg) {
                        $argv[] = trim($arg);
                    }
                }
                if (!is_null($value) and !$validation($value, ...$argv))
                    throw new ValidationAbort($name, $rule[0]);
            }
        }
    }
}
