<?php


namespace test\Response;

use Hichimi\Response\Text;

class TextTest extends \PHPUnit_Framework_TestCase
{
    public function testMake()
    {
        $res = Text::make('');
        $this->assertEquals(['Content-type: text/plain'], \PHPUnit_Framework_Assert::readAttribute($res, 'headers'));
    }

    /**
     * @dataProvider inputs
     * @expectedException \InvalidArgumentException
     * @param $inp
     */
    public function testMakeIllegalArguments($inp)
    {
        Text::make($inp);
    }

    public function inputs()
    {
        return [
            [[]],
            [function () {
            }],
            [34]
        ];
    }

    /**
     * @dataProvider text
     * @param $res
     * @param $actual
     */
    public function testRenderer($res, $actual)
    {
        ob_start();
        $res->renderer();
        $expected = ob_get_clean();
        $this->assertEquals($expected, $actual);
    }

    public function text()
    {
        return [
            [Text::make(''), ''],
            [Text::make('')->stderr('foo')->stderr('bar'), 'error:foo¥r¥n' . 'error:bar¥r¥n'],
            [Text::make('foo'), 'foo']
        ];
    }
}
