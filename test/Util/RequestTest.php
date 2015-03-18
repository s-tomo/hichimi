<?php

namespace test\Util;


class RequestTest extends \PHPUnit_Framework_TestCase {
    /**
     * @dataProvider values
     */
    public function test_sanitize($raw, $expected) {
        $ref = new \ReflectionClass("Hichimi\\Util\\Request");
        $met = $ref->getMethod("sanitize");
        $met->setAccessible(true);
        $actual = $met->invokeArgs(null, [$raw]);
        $this->assertEquals($expected, $actual);
    }
    public function values() {
        return [
            ["hogehoge", "hogehoge"],
            [" foo", "foo"],
            ["<script type=\"text/javascript\"></script>", "&lt;script type=&quot;text/javascript&quot;&gt;&lt;/script&gt;"],
            [["test", "'delete from hoge;"], ["test", "&#039;delete from hoge;"]],
            [["id"=>["5    ","6 "], "foo"], ["id"=>["5","6"], "foo"]]
        ];
    }
    public function test_init() {
        $_REQUEST = [];
    }
}
