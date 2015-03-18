<?php

namespace Hichimi\Abort;

use Hichimi\Response\Text;

final class HttpAbort extends HichimiAbort
{
    public static $actions = [
        400 => null,
        401 => null,
        403 => null,
        404 => null,
        405 => null,
        406 => null,
    ];

    function __construct($code, ...$argv)
    {
        $this->status = $code;
        $this->argv = $argv;
        $this->makeResponse(self::$actions[$code]);
    }

    static function action($code, \Closure $action)
    {
        self::$actions[$code] = $action;
    }

    protected function response()
    {
        $res = new Text();
        return $res->make($res->status[$this->status]);
    }
}