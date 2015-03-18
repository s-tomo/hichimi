<?php


namespace test\Response;

use Hichimi\Response\StaticFile;

class StaticFileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $path
     * @param $type
     * @throws \Exception
     * @dataProvider files
     */
    public function testMake($path, $type)
    {
        $res = StaticFile::make(dirname(__FILE__) . "/" . $path);
        $this->assertEquals(["Content-type: {$type}"], \PHPUnit_Framework_Assert::readAttribute($res, "headers"));
    }

    /**
     * @expectedException \Exception
     */
    public function testMakeUnexistFile()
    {
        StaticFile::make("foo/bar.php");
    }

    /**
     * @param $path
     * @param $type
     * @param $value
     * @dataProvider files
     */
    public function renderer($path, $type, $value)
    {
        ob_start();
        StaticFile::make(dirname(__FILE__) . "/" . $path)->renderer();
        $expected = ob_get_clean();
        $this->assertEquals($expected, $value);
    }

    public function files()
    {
        return [
            [
                "static/test.js",
                "application/x-javascript",
                "alert('test');"
            ],
            [
                "static/test.css",
                "text/css",
                "* {
    margin: 0;
}"
            ]
        ];
    }
}
