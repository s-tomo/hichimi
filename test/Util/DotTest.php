<?php
namespace test\Util;


use Hichimi\Util\Dot;

class DotTest extends \PHPUnit_Framework_TestCase {
    public function test_get() {
        $data = [
          "a" => [ "b" => 6 ]
        ];
        $x = Dot::get($data, "a");
        $this->assertEquals(["b"=>6], $x, "get: existed array");
        $x = Dot::get($data, "a.b");
        $this->assertEquals(6, $x, "get: existed value");
        $x = Dot::get($data, "a.b", 9);
        $this->assertEquals(6, $x, "get: existed value and default value");
        $x = Dot::get($data, "c.b");
        $this->assertNull($x, "get: no existed value and no default value");
        $x = Dot::get($data, "c", 8);
        $this->assertEquals(8, $x, "get: no existed value and default value");
    }

    public function test_set() {
        $data = ["a"=>7];
        $res = Dot::set($data, "a", 6);
        $this->assertEquals(["a"=>6], $data, "set: value");
        $this->assertEquals(6, $res, "set: value response");
        $data = ["a"=>7];
        $res = Dot::set($data, "c.d", [4,5]);
        $this->assertEquals(["a"=>7, "c" => ["d" => [4,5]] ], $data, "set: array");
        $this->assertEquals([4,5], $res, "set: array");
        $data = ["a"=>7];
        Dot::set($data, "a.b", 6);
        $this->assertEquals(["a"=>["b"=>6]], $data, "set: value to array");
    }
}