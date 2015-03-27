<?php


namespace test\Response;

use Hichimi\Response\Html;

class HtmlTest extends \PHPUnit_Framework_TestCase
{
    public function testMake()
    {
        $res = Html::make('');
        $this->assertEquals(['Content-type: text/html'], \PHPUnit_Framework_Assert::readAttribute($res, 'headers'));
    }

    /**
     * @dataProvider inputs
     * @expectedException \InvalidArgumentException
     * @param $inp
     */
    public function testMakeIllegalArguments($inp)
    {
        Html::make($inp);
    }

    public function inputs()
    {
        return [
            [function () {
            }]
        ];
    }

    /**
     * @dataProvider html
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

    public function html()
    {
        return [
            [Html::make(''), ''],
            [Html::make('')->stderr('foo')->stderr('bar'), '<pre>foo</pre><pre>bar</pre>'],
            [Html::make('foo'), 'foo']
        ];
    }

}
