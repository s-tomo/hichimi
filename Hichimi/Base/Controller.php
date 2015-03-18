<?php
namespace Hichimi\Base;


use Hichimi\Core\Filter;
use Hichimi\Core\Validator;
use Hichimi\Util\Dot;
use Hichimi\Util\Request;

abstract class Controller
{
    protected $params = [];

    final function run($action, array $argv = [])
    {
        if (!method_exists($this, $action))
            throw new \Exception("method {$action} is not implemented in " . get_class($this));
        $ref = new \ReflectionClass($this);
        $doc = $ref->getDocComment();
        $this->_filter($doc, $argv);
        $ref = new \ReflectionMethod($this, $action);
        $doc = $ref->getDocComment();
        $this->_filter($doc, $argv);
        $argv[] = $this->_validation($doc);
        $response_type = preg_match("/@response(.*)/", $doc, $match) ? trim($match[1]) : null;
        $response = $this->$action(...$argv);
        return [$response, $response_type];
    }

    final private function _validation($doc)
    {
        if (!preg_match("/@input([^¥*]*)/", $doc, $match))
            return [];
        $params = explode(",", trim($match[1]));
        $inp = [];
        foreach ($params as $param) {
            $rules = Dot::get($this->params, "{$param}.validation");
            if (!is_null($rules))
                Validator::validate($param, $rules);
            $inp[$param] = Request::get($param);
        }
        return $inp;
    }

    final private function _filter($doc, array $param)
    {
        if (!preg_match_all("/@filter([^¥*]*)/", $doc, $matches))
            return;
        foreach ($matches[1] as $match) {
            $filter = explode(":", trim($match));
            $argv = [];
            if (count($filter) === 2) {
                foreach (explode(",", $filter[1]) as $_arg) {
                    $_arg = trim($_arg);
                    if (substr($_arg, 0, 1) === "$") {
                        $name = substr($_arg, 1);
                        if (ctype_digit($name)) {
                            $argv[] = $param[(int)$name];
                        } else if (property_exists($this, $name)) {
                            $argv[] = $this->$name;
                        }
                    } else {
                        $argv[] = $_arg;
                    }
                }
            }
            Filter::run($filter[0], $argv);
        }
    }
}