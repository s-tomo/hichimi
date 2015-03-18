<?php
/**
 * @license Hichimi
 * (c) 2015 s-tomo
 * License: MIT
 */
namespace Hichimi;

use Hichimi\Base\Controller;
use Hichimi\Base\Response;
use Hichimi\Core\Exception;
use Hichimi\Response\Html;
use Hichimi\Unite\Route;
use Hichimi\Unite\Register;
use Hichimi\Core\Router;
use Hichimi\Core\Action;
use Hichimi\Abort\HichimiAbort;
use Hichimi\Util\Request;
use Hichimi\Util\Dot;
use Hichimi\Util\URL;

class App
{
    /**
     * @var Route
     */
    private static $route;
    /**
     * @var Register
     */
    private static $register;

    /**
     * @param array $paths
     * @throws \Exception
     */
    public static function load(array $paths)
    {
        foreach ($paths as $path => $mode) {
            switch ($mode) {
                case "file":
                    require_once $path;
                    break;
                case "dir":
                    $path .= substr($path, -1) === "/" ? "" : "/";
                    self::load_dir($path);
                    break;
                default:
                    throw new \Exception("");
            }
        }
    }

    private static function load_dir($dirname)
    {
        if ($dir = opendir($dirname)) {
            while (($name = readdir($dir)) !== false) {
                if ($name == "." || $name == "..") {
                    continue;
                }
                $path = $dirname . $name;
                if (is_file($path)) {
                    if (substr($name, -3) === "php") {
                        require_once $path;
                    }
                } else if (is_dir($path)) {
                    self::load_dir($path . "/");
                }
            }
            closedir($dir);
        }
    }

    /**
     * @return Route
     */
    public static function route()
    {
        if (!self::$route instanceof Route)
            self::$route = new Route();
        return self::$route;
    }

    /**
     * @return Register
     */
    public static function register()
    {
        if (!self::$register instanceof Register)
            self::$register = new Register();
        return self::$register;
    }

    public static function run()
    {
        ob_start();
        self::load([dirname(__FILE__) . "/Init" => "dir"]);
        $uri = Request::get("_uri");
        $method = strtolower(Dot::get($_SERVER, "REQUEST_METHOD", "GET"));
        new URL();
        try {
            $res = self::response(...self::dispatch(...self::search($uri, $method)));
        } catch (HichimiAbort $e) {
            $res = self::response($e->getResponse(), null)
                ->status($e->getStatus());
        } catch (\Exception $e) {
            $res = self::response(Exception::check($e), null)
                ->status(500);
        } finally {
            $tmp = ob_get_clean();
            if (isset($res) and $res instanceof Response)
                $res->run($tmp);
            else {
                Html::make($tmp)
                    ->status(500)
                    ->run();
            }
        }
    }

    private static function search($uri, $method)
    {
        return Router::search($uri, $method);
    }

    private static function dispatch($action, array $argv)
    {
        if (is_array($action)) {
            list($c, $a) = $action;
            return self::controller($c, $a, $argv);
        } else if ($action instanceof Action) {
            return self::action($action, $argv);
        } else {
            throw new \Exception();
        }
    }

    /**
     * @param Action $action
     * @param array $argv
     * @return Response
     */
    private static function action(Action $action, array $argv)
    {
        $ob = new runAction();
        $cl = $ob->getClosure();
        $cl = $cl->bindTo($action, "Hichimi\\Core\\Action");
        return $cl($argv);
    }

    private static function controller($controller, $action, array $argv)
    {
        if (!class_exists($controller))
            throw new \Exception("unknown Controller");
        $ins = new $controller();
        if (!$ins instanceof Controller)
            throw new \Exception("invalid Controller");
        return $ins->run($action, $argv);
    }

    /**
     * @param $response
     * @param $type
     * @return Response
     * @throws \Exception
     */
    private static function response($response, $type)
    {
        $class = ucfirst($type);
        if ($response instanceof Response) {
            if (!is_null($type) and get_class($response) !== $class)
                throw new \Exception("invalid response");
            return $response;
        } else {
            switch ($class) {
                case null:
                    $class = "Html";
                case "Html":
                case "Json":
                case "Text":
                    $class = "Hichimi\\Response\\{$class}";
                    break;
                default:
                    if (!class_exists("{$class}"))
                        throw new \Exception("unknown response type {$type}");
            }
            return $class::make($response);
        }
    }
}

class runAction
{
    public function getClosure()
    {
        return function ($argv) {
            return $this->run($argv);
        };
    }
}