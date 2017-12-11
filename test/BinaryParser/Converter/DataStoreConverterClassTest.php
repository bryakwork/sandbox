<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 16.11.2017
 * Time: 16:01
 */

use PHPUnit\Framework\TestCase;
use rollun\CatalogTools\Converter\DataStoreConverter;
use rollun\CatalogTools\Converter\PriceToNumber;
use rollun\datastore\DataStore\Interfaces\DataStoresInterface;
use rollun\datastore\DataStore\Memory;
use rollun\datastore\Rql\RqlQuery;
use Xiag\Rql\Parser\Query;
use Zend\Filter\Word\CamelCaseToDash;

class DataStoreConverterClassTest extends TestCase
{
    protected $datastore1;
    protected $datastore2;
    protected $query;
    protected $data1;
    protected $container;
    protected $config;

    protected function setUp()
    {
        parent::setUp();
        chdir(dirname(__DIR__, 3));
        require 'vendor/autoload.php';
        require_once 'config/env_configurator.php';

        /** @var \Interop\Container\ContainerInterface $container */
        $container = require 'config/container.php';
        \rollun\dic\InsideConstruct::setContainer($container);
        $this->datastore1 = $this->createMock(DataStoresInterface::class);
        $this->data1 = [0 => ["name" => "BwaBwaaa", "price" => "23.05.2017"], 1 => ["name" => "WahWahWah", "price" => "14,88",]];
        $this->datastore1->method('query')->willReturn($this->data1);
        $this->datastore2 = new Memory();
        $this->query = $this->createMock(Query::class);

    }

    function testFiltering()
    {
        $filter = [
            "name" => ["filterClassName" => CamelCaseToDash::class, "filterParams" => []],
            "price" => ["filterClassName" => PriceToNumber::class, ],
        ];
        $rightResult = [0 => ["id" => 0, "name" => "Bwa-Bwaaa", "price" => 23.05], 1 => ["id" => 1, "name" => "Wah-Wah-Wah", "price" => 14.88],];
        $object = new DataStoreConverter($this->datastore1, $this->datastore2, $this->query, $filter);
        $object();
        $this->assertTrue($this->datastore2->query(new RqlQuery()) == $rightResult);
    }

    /**
     * @expectedException \rollun\CatalogTools\Converter\WrongTypeException
     */
    function testWrongTypeException()
    {
        $filter = [
            "name" => ["filterClassName" => "Zend\Filter\Word\NotCamelCaseToDash", "filterParams" => []],
            "price" => ["filterClassName" => "Zend\Filter\Word\CamelCaseToUnderscore", ],
        ];
        $object = new DataStoreConverter($this->datastore1, $this->datastore2, $this->query, $filter);
        $this->assertEquals(null, $object());
    }
}
