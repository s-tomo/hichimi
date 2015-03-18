<?php
namespace Hichimi\Abort;

use Hichimi\Response\Json;
use Hichimi\Util\URL;

class ValidationAbort extends HichimiAbort
{
    protected $status = 400;
    /**
     * @var \Closure
     */
    protected static $action = null;
    private $name;
    private $validation;

    function __construct($name, $validation)
    {
        $this->name = $name;
        $this->validation = $validation;
        $this->makeResponse(self::$action);
    }

    static function action(\Closure $action)
    {
        self::$action = $action;
    }

    protected function response()
    {
        return Json::make([
            "type" => "validation",
            "url" => URL::full(),
            "name" => $this->name
        ]);
    }
}