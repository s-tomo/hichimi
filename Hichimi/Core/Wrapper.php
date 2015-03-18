<?php
namespace Hichimi\Core;

class Wrapper
{
    /**
     * @var \Closure
     */
    private static $wrap = null;
    private $exit_flag = true;
    private $params = [];
    private $tmp = "";

    function __construct($method, array $params = [])
    {
        if (!self::$wrap instanceof \Closure)
            throw new \Exception();
        $method = strtoupper($method);
        foreach ($params as $name => $value) {
            switch ($method) {
                case "GET":
                    $_GET[$name] = $value;
                    break;
                case "POST":
                case "PUT":
                case "DELETE":
                    $_POST[$name] = $value;
                    break;
            }
            $_REQUEST[$name] = $value;
        }
        $_SERVER["REQUEST_METHOD"] = $method;
        $this->params = $params;
    }

    function __destruct()
    {
        if ($this->exit_flag === true)
            echo $this->tmp . ob_get_clean();
    }

    public static function init(\Closure $wrap)
    {
        self::$wrap = $wrap;
    }

    public function run()
    {
        $this->tmp = ob_get_clean();
        ob_start();
        $wrap = self::$wrap;
        $wrap($this->params);
        $this->exit_flag = false;
        $out = ob_get_clean();
        ob_start();
        echo $this->tmp;
        return $out;
    }

}