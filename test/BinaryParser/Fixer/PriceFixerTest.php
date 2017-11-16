<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 10.11.2017
 * Time: 12:08
 */

namespace rollun\test\BinaryParser\Fixer;

use PHPUnit\Framework\TestCase;
use rollun\BinaryParser\Converter\PriceFixer;

class PriceFixerTest extends TestCase
{
    /** @var PriceFixer  **/
    protected $object;

    protected function setUp()
    {
        parent::setUp();
        $this->object = new PriceFixer();
    }

    public function testDate()
    {
        $this->assertEquals(12.03, $this->object->__invoke("12.03.2017"));
        $this->assertEquals(5.12, $this->object->__invoke("5.12.2017"));
    }

    public function testFloat()
    {
        $this->assertEquals(5.50, $this->object->__invoke("5,50"));
        $this->assertEquals(105.50, $this->object->__invoke("105,50"));
    }

    public function testInt()
    {
        $this->assertEquals(9.00, $this->object->__invoke("9"));
        $this->assertEquals(103.00, $this->object->__invoke("103"));
    }

    /**
     * @expectedException \rollun\BinaryParser\Converter\WrongTypeException
     */

    public function testWrongType()
    {
        $this->object->__invoke("addsd");
        $this->object->__invoke('aeaeae');
    }

    public function providerStringSet()
    {
        return array(
            array(
                12.03, "12.03.2017"
            ),
            array(
                5.12, "5.12.2017"
            ),
            array(
                5.50, "5,50"
            ),
            array(
                105.50, "105,50"
            ),
            array(
                9.00, "9"
            ),
            array(
                103.00, "103"
            ),
        );
    }

    /**
     * @dataProvider providerStringSet
     * @param $expected
     * @param $input
     */
    public function testRegular($expected, $input)
    {
        $this->assertEquals($expected, $this->object->__invoke($input));
    }

    /**
     * @expectedException \rollun\BinaryParser\Converter\WrongTypeException
     */
    public function testException()
    {
        $this->object->__invoke("addsd");
        $this->object->__invoke('aeaeae');
    }
}
//}
