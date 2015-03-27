<?php
namespace test\Core;


use Hichimi\Core\Exception;

class ExceptionTest extends \PHPUnit_Framework_TestCase
{
    function test_set()
    {
        $test = function () {
        };
        Exception::set('test', $test);
        $ref = new \ReflectionClass('Hichimi\Core\Exception');
        $callbacks = $ref->getProperty('callbacks');
        $callbacks->setAccessible(true);
        $this->assertEquals(['test' => $test], $callbacks->getValue(null));
    }

    function test_callback()
    {
        $res = Exception::callback(new \Exception());
        $this->assertEquals('Hichimi\Response\Html', get_class($res));
    }

    function test_check()
    {
        Exception::set('InvalidArgumentException', function (\InvalidArgumentException $e) {
            return 'N:' . get_class($e);
        });
        Exception::set('test\Core\TestException', function (\Exception $e) {
            return 'E:' . get_class($e);
        });
        $exception = Exception::check(new TestException());
        $this->assertEquals('E:test\Core\TestException', $exception, 'exception match');
        $exception = Exception::check(new \InvalidArgumentException());
        $this->assertEquals('N:InvalidArgumentException', $exception, 'invalid argument exception match');
        $exception = Exception::check(new SubTestException());
        $this->assertEquals('E:test\Core\SubTestException', $exception, 'exception instance');
        $res = Exception::check(new \Exception('hoge'));
        $this->assertEquals(Exception::callback(new \Exception('hoge')), $res);
    }
}

class TestException extends \Exception
{

}

class SubTestException extends TestException
{

}
