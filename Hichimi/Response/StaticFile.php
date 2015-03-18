<?php


namespace Hichimi\Response;


use Hichimi\Base\Response;

class StaticFile extends Response
{
    protected $file = "";
    private $types = [
        "txt" => ["text/plain"],
        "html" => ["text/html", "charset=UTF-8"],
        "pdf" => ["application/pdf"],
        "xml" => ["application/xml"],
        "js" => ["application/x-javascript"],
        "css" => ["text/css"],
        "jpg" => ["image/jpeg"],
        "jpeg" => ["image/jpeg"],
        "png" => ["image/png"],
        "gif" => ["image/gif"],
    ];

    public static function make($file)
    {
        if (!is_file($file))
            throw new \Exception();
        $ins = new self();
        $ins->file = $file;
        return $ins->type(...$ins->getType());
    }

    private function getType()
    {
        $extension = mb_strtolower(pathinfo($this->file, PATHINFO_EXTENSION));
        if (isset($this->types[$extension]))
            return $this->types[$extension];
        else
            return ["application/octet-stream"];
    }

    public function renderer()
    {
        echo readfile($this->file);
    }
}