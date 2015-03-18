<?php

namespace Hichimi\Error;

class HichimiError
{
    /**
     * @var bool
     */
    static $debug = false;

    /**
     * @var array
     */
    static private $types = [
        1 => ["name" => "DuplicateRouting", "class" => "\\Exception"],
        10 => ["name" => "UnImplementedController", "class" => "\\Exception"],
        11 => ["name" => "IllegalController", "class" => "\\Exception"],
        20 => ["name" => "UnknownValidation", "class" => "\\Exception"],
        21 => ["name" => "IllegalValidation", "class" => "\\Exception"],
        22 => ["name" => "UnknownInput", "class" => "\\Exception"],
        30 => ["name" => "UnknownFilter", "class" => "\\Exception"],
        50 => ["name" => "WrapperDefinition", "class" => "\\Exception"],
    ];

    /**
     * @var array
     */
    static $log = [];

    /**
     * @param $code
     * @param $message
     * @throws \Exception
     */
    static function error($code, $message)
    {
        if(!isset(self::$types[$code]))
            throw new \InvalidArgumentException("unknown hichimi error number : {$code}");
        $name = self::$types[$code]["name"];
        if (self::$debug) {
            self::$log[] = [
                $code,
                $name,
                $message,
                debug_backtrace()
            ];
        } else {
            $exception = self::$types[$code]["class"];
            throw new $exception("{$name}:{$message}");
        }
    }
}