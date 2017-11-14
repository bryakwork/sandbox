<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 10.11.2017
 * Time: 17:09
 */

use rollun\BinaryParser\Converter\ArraySelector;

class ArrayTransformerTest extends PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        parent::setUp();
        $this->object = new ArraySelector();
    }

    public function testOne()
    {
        $array = [
        "price" => "3.33",
        "notPrice" => "imma not price",
        "price2" => "14.88",
        "aeaewsdsaf" => "fsadfa",
        "dafsdfse" => "wefref",
        "alsoPrice" => "1672.00",
        ];
        $keys = ["price", "price2", "alsoPrice"];
        $expected = ["price" => "3.33", "price2" => "14.88", "alsoPrice" => "1672.00",];

        $this->assertEquals($expected, $this->object->__invoke($array, $keys));
    }
    /**
     * @expectedException \rollun\BinaryParser\Converter\WrongTypeException
     */
    public function testTwo()
    {
        $array = "masleena";
        $keys = ["price", "price2", "alsoPrice"];
        $expected = ["price" => "3.33", "price2" => "14.88", "alsoPrice" => "1672.00",];
        $this->assertEquals($expected, $this->object->__invoke($array, $keys));
    }
}
