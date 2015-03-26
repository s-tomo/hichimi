<?php

namespace test\Util;

use Hichimi\Util\URL;

class URLTest extends \PHPUnit_Framework_TestCase
{
    public function testRoot()
    {
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['HTTP_HOST'] = 'www.hogehoge.com';
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        new URL();
        $this->assertEquals('https://www.hogehoge.com/', URL::$root);
        $_SERVER['HTTPS'] = '';
        $_SERVER['HTTP_HOST'] = 'www.hogehoge.com';
        $_SERVER['SCRIPT_NAME'] = '/greek/hello/index.php';
        new URL();
        $this->assertEquals('http://www.hogehoge.com/greek/hello/', URL::$root);
    }

    public function testTrim()
    {
        $actual = URL::trim('//hoge/test/');
        $this->assertEquals('hoge/test', $actual);
    }

    public function testMerge()
    {
        $actual = URL::merge('/test/', '/greek/hello/');
        $this->assertEquals('test/greek/hello', $actual);
    }

    public function testFull()
    {
        URL::$root = 'http://www.hogehoge.com/';
        $url = URL::full('bar');
        $this->assertEquals('http://www.hogehoge.com/bar/', $url);
        $url = URL::full('///bar///');
        $this->assertEquals('http://www.hogehoge.com/bar/', $url);
        $url = URL::full('//////');
        $this->assertEquals('http://www.hogehoge.com/', $url);
        $_REQUEST['_uri'] = 'bar';
        $url = URL::full();
        $this->assertEquals('http://www.hogehoge.com/bar/', $url);
    }
}
