<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 16.11.2017
 * Time: 16:01
 */

use PHPUnit\Framework\TestCase;
use rollun\BinaryParser\Converter\Converter;
use rollun\BinaryParser\Converter\PriceFixer;
use rollun\datastore\DataStore\Interfaces\DataStoresInterface;
use Xiag\Rql\Parser\Query;
use Zend\Filter\Word\CamelCaseToDash;

class ConverterClassTest extends TestCase
{
    protected $datastore1;
    protected $datastore2;
    protected $query;
    protected $data1;


    protected function setUp()
    {
        parent::setUp();
        $this->datastore1 = $this->createMock(DataStoresInterface::class);
        $this->data1 = [0 => ["name" => "BwaBwaaa", "price" => "WahWahWah"], 1 => ["name" => "WahWahWah", "price" => "BwaBwaaa",]];
        $this->datastore1->method('query')->willReturn($this->data1);
        $this->datastore2 = $this->createMock(DataStoresInterface::class);
        $this->datastore2->method('create')->willReturn([0 => ["name" => "Bwa_Bwaaa", "price" => "23.05.2017"], 1 => ["name" => "Wah-Wah-Wah", "price" => 23.05],]);
        $this->query = $this->createMock(Query::class);

    }

    function testFiltering()
    {
        $filter = [
            "name" => ["filterClassName" => CamelCaseToDash::class, "filterParams" => []],
            "price" => ["filterClassName" => PriceFixer::class, ],
        ];

        $object = new Converter($this->datastore1, $this->datastore2, $this->query, $filter);
        $this->assertEquals(null, $object());
    }

    /**
     * @expectedException \rollun\BinaryParser\Converter\WrongTypeException
     */
    function testWrongTypeException()
    {
        $filter = [
            "name" => ["filterClassName" => "Zend\Filter\Word\NotCamelCaseToDash", "filterParams" => []],
            "price" => ["filterClassName" => "Zend\Filter\Word\CamelCaseToUnderscore", ],
        ];
        $object = new Converter($this->datastore1, $this->datastore2, $this->query, $filter);
        $this->assertEquals(null, $object());
    }

    /**
     * @expectedException \rollun\BinaryParser\Converter\FieldNotFoundException
     */
    function testFieldNotFoundException()
    {
        $filter = [
            "name" => ["filterClassName" => "Zend\Filter\Word\CamelCaseToDash", "filterParams" => []],
            "price" => ["filterClassName" => "Zend\Filter\Word\CamelCaseToUnderscore",],
        ];
        $data2 = [
            0 => ["name" => "BwaBwaaa", "price" => "WahWahWah"],
            1 => ["name" => "WahWahWah", "price" => "BwaBwaaa"],
            2 => ["strange" => "param", "not" => "right"],
        ];
        $datastore3 = $this->createMock(DataStoresInterface::class);
        $datastore3->method('query')->willReturn($data2);
        $object = new Converter($datastore3, $this->datastore2, $this->query, $filter);
        $this->assertEquals(null, $object());
    }
}
