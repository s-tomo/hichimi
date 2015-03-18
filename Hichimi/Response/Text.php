<?php
namespace Hichimi\Response;

use Hichimi\Base\Response;

class Text extends Response
{
    static function make($text)
    {
        if (!is_string($text))
            throw new \InvalidArgumentException("");
        $ins = new self();
        $ins->source = $text;
        return $ins->type("text/plain");
    }

    public function renderer()
    {
        if (count($this->error) > 0) {
            foreach ($this->error as $err)
                echo "error:{$err}¥r¥n";
        }
        echo $this->source;
    }
}