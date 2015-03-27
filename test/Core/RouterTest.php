<?php


namespace test\Core;

use Hichimi\Core\Router;
use Hichimi\Abort\HttpAbort;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    public function testTrim()
    {
        $trim = self::getMethod('trim');
        $res = $trim->invokeArgs(null, ['/test/hoge/']);
        $this->assertEquals('test/hoge', $res);
    }

    /**
     * @param $method
     * @param array $uri
     * @param $func
     * @param $exp_type
     * @param $exp_pattern
     * @param array $exp_argv
     * @internal param array $expected
     * @dataProvider uriSample
     */
    public function testParse($method, array $uri, $func, $exp_type, $exp_pattern, $exp_argv = [])
    {
        $parse = self::getMethod('parse');
        list($type, $pattern, $argv) = $parse->invokeArgs(null, $uri);
        $this->assertEquals($exp_type, $type);
        $this->assertEquals($exp_pattern, $pattern);
        $this->assertEquals($exp_argv, $argv);
    }

    /**
     * @param $method
     * @param array $uri
     * @param $func
     * @param $exp_type
     * @param $exp_pattern
     * @param array $exp_argv
     * @dataProvider uriSample
     */
    public function testSet($method, array $uri, $func, $exp_type, $exp_pattern, $exp_argv = [])
    {
        Router::set($method, $uri, $func);
        if ($exp_type == 'static') {
            $statics = self::getPropertyValue('statics');
            $f = $statics[$uri[0]][$method];
        } else {
            $dynamics = self::getPropertyValue('dynamics');
            list($f, $argv) = $dynamics[$exp_pattern][$method];
            $this->assertEquals($exp_argv, $argv);
        }
        $this->assertEquals($func(), $f());
    }

    public function uriSample()
    {
        return [
            ['get', ['test/greek/hello'], function () {
                return 'greek';
            }, 'static', 'test/greek/hello'],
            [
                'get',
                ['js/:script_name', ['script_name' => 'path']],
                function () {
                    return 'js';
                },
                'dynamic',
                'js/(\S+)',
                ['script_name']
            ],
            [
                'get',
                ['class/:num/student/:name', ['num' => 'int', 'name' => 'string']],
                function () {
                    return 'student';
                },
                'dynamic',
                'class/(\d+)/student/(\w+)',
                ['num', 'name']
            ]
        ];
    }

    /**
     * @dataProvider sampleUri
     * @param $method
     * @param $url
     * @param $mes
     * @param array $e_argv
     * @internal param array $argv
     */
    public function testSearch($method, $url, $mes, $e_argv = [])
    {
        list($func, $argv) = Router::search($url, $method);
        $this->assertEquals($mes, $func());
        $this->assertTrue($e_argv === $argv);
    }

    public function sampleUri()
    {
        return [
            ['get', 'test/greek/hello', 'greek'],
            ['get', 'js/super/main.js', 'js', ['super/main.js']],
            ['get', 'class/4/student/John', 'student', ['4', 'John']],
        ];
    }

    /**
     * @param $method
     * @param $url
     * @param $code
     * @dataProvider illegalUri
     */
    public function testError($method, $url, $code)
    {
        try {
            Router::search($url, $method);
        } catch (HttpAbort $e) {
            $this->assertEquals($code, $e->getStatus());
        }
    }

    public function illegalUri()
    {
        return [
            ['post', 'test/greek/hello', 405],
            ['get', 'jsx/test.js', 404],
            ['get', 'class/test/student/John', 404]
        ];
    }

    public static function getPropertyValue($name)
    {
        $ref = new \ReflectionClass('Hichimi\Core\Router');
        $prop = $ref->getProperty($name);
        $prop->setAccessible(true);
        return $prop->getValue();
    }

    public static function getMethod($name)
    {
        $ref = new \ReflectionClass('Hichimi\Core\Router');
        $method = $ref->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
}
