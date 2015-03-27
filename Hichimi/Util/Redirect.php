<?php

namespace Hichimi\Util;

use Hichimi\Abort\HttpAbort;
use Hichimi\Core\Wrapper;
use Hichimi\Base\Controller;

final class Redirect
{
    static function to($controller, $action, array $params = [])
    {
        if (!class_exists($controller))
            throw new \Exception("unknown Controller");
        $ins = new $controller();
        if (!$ins instanceof Controller)
            throw new \Exception("invalid Controller");
        return $ins->run($action, $params);
    }

    static function abort($code, ...$argv)
    {
        throw new HttpAbort($code, ...$argv);
    }

    static function referer()
    {
        if ($ref = Dot::get($_SERVER, "HTTP_REFERER")) {
            self::uri($ref);
        }
        return false;
    }

    static function uri($uri, array $params = null)
    {
        if (mb_strtolower(substr($uri, 0, 1)) !== '/' && !preg_match('@\w+://.*@', $uri))
            $uri = URL::full($uri);
        if (!is_null($params)) {
            $qs = [];
            foreach ($params as $key => $value)
                $qs[] = "{$key}={$value}";
            $uri .= "?" . implode("&", $qs);
        }
        header("Location: {$uri}");
        exit;
    }

    static function wrap($method = "get", array $parameter = [])
    {
        $wrapper = new Wrapper(...func_get_args());
        return $wrapper->run();
    }
}
