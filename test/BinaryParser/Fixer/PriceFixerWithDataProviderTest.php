<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 13.11.2017
 * Time: 12:43
 */

namespace rollun\test\BinaryParser\Fixer;

use PHPUnit\Framework\TestCase;
use rollun\CatalogTools\Converter\PriceToNumber;
use rollun\CatalogTools\Converter\WrongTypeException;

class PriceFixerWithDataProviderTest extends TestCase
{
    /** @var PriceToNumber */
    protected $object;

    protected function setUp()
    {
        parent::setUp();
        chdir(dirname(__DIR__, 3));
        require 'vendor/autoload.php';
        require_once 'config/env_configurator.php';

        /** @var \Interop\Container\ContainerInterface $container */
        $container = require 'config/container.php';
        \rollun\dic\InsideConstruct::setContainer($container);
        $this->object = new PriceToNumber();
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


    public function providerExceptionSet()
    {
        return [
            [WrongTypeException::class, "asdsdffg"],
            [WrongTypeException::class, "zzzzzzzzzzzz"],
            [WrongTypeException::class, "1212asds3dffg"],
        ];
    }

    /**
     * @dataProvider providerExceptionSet
     * @param $exceptionType
     * @param $value
     */
    public function testException($exceptionType, $value)
    {
        $this->expectException($exceptionType);
        $this->object->__invoke($value);
    }
}

