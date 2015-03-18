<?php

namespace Hichimi\Unite;

use Hichimi\Abort\HttpAbort;
use Hichimi\Abort\ValidationAbort;
use Hichimi\Core\Exception;
use Hichimi\Core\Filter;
use Hichimi\Core\Validator;
use Hichimi\Core\Wrapper;

class Register
{

    /**
     * @param $name
     * @param callable $filter
     * @return $this
     */
    public function filter($name, \Closure $filter)
    {
        Filter::set(...func_get_args());
        return $this;
    }

    /**
     * @param $name
     * @param callable $rule
     * @return $this
     */
    public function validation($name, \Closure $rule)
    {
        Validator::set(...func_get_args());
        return $this;
    }

    /**
     * @param $code
     * @param callable $action
     * @return $this
     * @throws \Exception
     */
    public function abort($code, \Closure $action)
    {
        if ($code === "validation")
            ValidationAbort::action($action);
        elseif (array_key_exists($code, HttpAbort::$actions))
            HttpAbort::action($code, $action);
        else
            throw new \Exception("undefined status code {$code}");
        return $this;
    }

    public function exception($class, \Closure $action)
    {
        Exception::set($class, $action);
        return $this;
    }

    /**
     * @param callable $wrap
     * @return $this
     */
    public function wrap(\Closure $wrap)
    {
        Wrapper::init($wrap);
        return $this;
    }
}