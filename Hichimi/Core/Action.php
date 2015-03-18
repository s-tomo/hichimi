<?php

namespace Hichimi\Core;

use Hichimi\Util\Request;

final class Action
{
    private $filters = [];
    private $validators = [];
    private $action;
    private $response_type = null;

    function __construct(\Closure $action)
    {
        $this->action = $action;
    }

    public function filter($name, array $argv = [])
    {
        $this->filters[$name] = $argv;
        return $this;
    }

    public function filter_many(array $filters)
    {
        foreach ($filters as $name => $argv) {
            $this->filter($name, $argv);
        }
        return $this;
    }

    /**
     * @param $name
     * @param array $rules
     * @return $this
     */
    public function validator($name, array $rules)
    {
        $this->validators[$name] = $rules;
        return $this;
    }

    public function validator_many(array $validation)
    {
        foreach ($validation as $name => $rules) {
            $this->validator($name, $rules);
        }
        return $this;
    }

    public function response($type)
    {
        $this->response_type = $type;
    }

    private function run(array $param = [])
    {
        foreach ($this->filters as $filter => $argv) {
            Filter::run($filter, $argv);
        }
        $inp = [];
        foreach ($this->validators as $name => $rules) {
            Validator::validate($name, $rules);
            $inp[$name] = Request::get($name);
        }
        $param[] = $inp;
        $action = $this->action;
        $response = $action(...$param);
        return [$response, $this->response_type];
    }
}