<?php


namespace test\Response;

use Hichimi\Response\Json;


class JsonTest extends \PHPUnit_Framework_TestCase
{
    public function testMake()
    {
        $res = Json::make([]);
        $this->assertEquals(['Content-type: application/json'], \PHPUnit_Framework_Assert::readAttribute($res, 'headers'));
    }

    /**
     * @dataProvider json
     */
    public function testRenderer($res, $actual)
    {
        ob_start();
        $res->renderer();
        $expected = ob_get_clean();
        $this->assertEquals($expected, $actual);
    }

    public function json()
    {
        return [
            [Json::make([]), '{}'],
            [Json::make([])->stderr('hogehoge'), '{"error":["hogehoge"]}'],
            [Json::make(['foo' => 'x', 'bar' => 'y']), '{"foo":"x","bar":"y"}']
        ];
    }
}
