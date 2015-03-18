<?php

namespace Hichimi\Abort;


abstract class HichimiAbort extends \Exception
{
    protected $status;
    protected $argv = [];
    protected $response;

    protected function makeResponse($action)
    {
        if (is_null($action))
            $this->response = $this->response(...$this->argv);
        else {
            $action = $action->bindTo($this);
            $this->response = $action(...$this->argv);
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    abstract protected function response();

    public function getResponse()
    {
        return $this->response;
    }
}