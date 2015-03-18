<?php
namespace Hichimi\Response;

use Hichimi\Base\Response;

class Html extends Response
{
    static function make($html)
    {
        if (is_object($html))
            throw new \InvalidArgumentException();
        $ins = new self();
        $ins->source = $html;
        return $ins->type("text/html");
    }

    public function renderer()
    {
        if (count($this->error) > 0) {
            foreach ($this->error as $err)
                echo "<pre>{$err}</pre>";
        }
        echo $this->source;
    }
}