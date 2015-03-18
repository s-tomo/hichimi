<?php
namespace Hichimi\Response;

use Hichimi\Base\Response;

class Json extends Response
{
    static function make(array $json)
    {
        $ins = new self();
        $ins->source = $json;
        return $ins->type("application/json");
    }

    public function renderer()
    {
        if (count($this->error) > 0)
            $this->source["error"] = $this->error;
        echo json_encode(count($this->source) > 0 ? $this->source : new \ArrayObject());
    }
}