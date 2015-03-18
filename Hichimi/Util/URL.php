<?php

namespace Hichimi\Util;


class URL
{
    public static $root = null;

    function __construct()
    {
        $protocol = Dot::get($_SERVER, "HTTPS") === "on" ? "https" : "http";
        $host = Dot::get($_SERVER, "HTTP_HOST");
        self::$root = "{$protocol}://{$host}/";
        $uri = preg_replace('@\w+\.php@', "", Dot::get($_SERVER, "SCRIPT_NAME", ""));
        self::$root = self::full($uri);
    }

    public static function full($uri=null)
    {
        if(is_null($uri))
            $uri = Request::get("_uri");
        else{
            while (substr($uri, 0, 1) === "/")
                $uri = substr($uri, 1);
            while (substr($uri, -1) === "/")
                $uri = substr($uri, 0, -1);
        }
        return self::$root . $uri . ( $uri ? "/" : "" );
    }
}