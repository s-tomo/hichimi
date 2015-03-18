<?php
namespace Hichimi\Core;

use Hichimi\Response\Html;

final class Exception
{
    private static $callbacks = [];

    static function set($name, \Closure $callback)
    {
        self::$callbacks[$name] = $callback;
    }

    static function callback(\Exception $e)
    {
        $class = get_class($e);
        $trace = $e->getTraceAsString();
        return Html::make(<<< eod
{$class}
{$e->getMessage()}
<pre>{$trace}</pre>
eod
        );
    }

    static function check(\Exception $exception)
    {
        $class = get_class($exception);
        foreach(self::$callbacks as $name => $callback){
            if(($class===$name or is_subclass_of($exception, $name)) and is_callable($callback)){
                $callback = self::$callbacks[$name];
                return $callback($exception);
            }
        }
        return self::callback($exception);
    }
}