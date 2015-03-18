<?php
namespace Hichimi\Base;

use Hichimi\Util\Dot;

abstract class Response
{
    protected $headers = [];
    protected $source;
    protected $error = [];

    public $status = [
        400 => "Bad Request",
        401 => "Unauthorized",
        403 => "Forbidden",
        404 => "Not Found",
        405 => "Method Not Allowed",
        406 => "Not Acceptable",
        500 => "Internet server error"
    ];

    final protected function setHeader($arg1, $arg2 = null, $arg3 = null)
    {
        if (is_null($arg2)) {
            $this->headers[] = $arg1;
        } else if (is_null($arg3)) {
            $this->headers[] = "{$arg1}: {$arg2}";
        } else {
            $this->headers[] = "{$arg1}: {$arg2}; {$arg3}";
        }
    }

    final public function type($type, $charset = null)
    {
        $this->setHeader("Content-type", $type, is_null($charset) ? null : "charset={$charset}");
        return $this;
    }

    final public function length($len)
    {
        $this->setHeader("Content-length", $len);
        return $this;
    }

    final public function attachment($file)
    {
        $this->setHeader("Content-disposition", "attachment", "filename=\"{$file}\"");
        return $this;
    }

    final public function status($code)
    {
        $this->setHeader(Dot::get($_SERVER, "SERVER_PROTOCOL", "HTTP/1.0"), "{$code} {$this->status[$code]}", true, $code);
        return $this;
    }

    final public function stderr($message)
    {
        $this->error[] = $message;
        return $this;
    }

    abstract public function renderer();


    public function run()
    {
        foreach ($this->headers as $header) {
            header($header);
        }
        $this->renderer();
    }
}