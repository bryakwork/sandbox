<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 17.11.2017
 * Time: 10:45
 */

namespace rollun\binaryParser;

use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use rollun\CatalogTools\Converter\DataStoreConverter;
use rollun\CatalogTools\Converter\ConverterAbstractFactory;
use rollun\datastore\DataStore\Interfaces\DataStoresInterface;
use rollun\datastore\Rql\RqlQuery;
use Zend\Filter\Word\CamelCaseToDash;
use Zend\Filter\Word\CamelCaseToUnderscore;

class ConverterAbstractFactoryTest extends TestCase
{
    protected $object;
    protected $config;
    protected $requestedName;
    protected $originDataStore;
    protected $destinationDataStore;
    protected $query;
    protected $container;
    protected $map;

    protected function setUp()
    {
        parent::setUp();
        chdir(dirname(__DIR__, 3));
        require 'vendor/autoload.php';
        require_once 'config/env_configurator.php';

        /** @var \Interop\Container\ContainerInterface $container */
        $container = require 'config/container.php';
        \rollun\dic\InsideConstruct::setContainer($container);
        $this->object = new ConverterAbstractFactory();
        $this->requestedName = "CoolConverter";
        $this->originDataStore = $this->createMock(DataStoresInterface::class);
        $this->destinationDataStore = $this->createMock(DataStoresInterface::class);
        $this->query = new RqlQuery();
        $this->config = [
            ConverterAbstractFactory::class => [
                "CoolConverter" => [
                    ConverterAbstractFactory::KEY_ORIGIN_DS => "datastore1",
                    ConverterAbstractFactory::KEY_DESTINATION_DS => "datastore2",
                    ConverterAbstractFactory::KEY_QUERY => $this->query,
                    ConverterAbstractFactory::KEY_FILTERS => [
                        "name" => ["filterClassName" => CamelCaseToDash::class, "filterParams" => [],],
                        "price" => ["filterClassName" => CamelCaseToUnderscore::class,],
                    ]
                ]
            ]
        ];
        $this->map = [
            ["config", $this->config],
            ["datastore1", $this->originDataStore],
            ["datastore2", $this->destinationDataStore],
        ];
        $this->container = $this->createMock(ContainerInterface::class);
        $this->container->method('get')->will($this->returnValueMap($this->map));
    }

    function testFactoryCanCreate()
    {
        $rightObject = new DataStoreConverter($this->originDataStore, $this->destinationDataStore, $this->query, $this->config[ConverterAbstractFactory::class]["CoolConverter"][ConverterAbstractFactory::KEY_FILTERS]);

        $this->assertEquals(true, $this->object->canCreate($this->container, $this->requestedName));
        $this->assertEquals($rightObject, $this->object->__invoke($this->container, $this->requestedName));
    }

    function testFactoryCanNotCreate()
    {
        $config2 = [];
        $map2 = $this->map;
        $map2[0] = ["config", $config2];
        $container2 = $this->createMock(ContainerInterface::class);
        $container2->method('get')->will($this->returnValueMap($map2));

        $this->assertEquals(false, $this->object->canCreate($container2, $this->requestedName));
    }

    /**
     * @expectedException \rollun\CatalogTools\Converter\WrongTypeException
     */
    function testWrongTypeException()
    {
        $notDatastore = $this->createMock(ContainerInterface::class);
        $map3 = $this->map;
        $map3[1] = ["datastore1", $notDatastore];
        $container3 = $this->createMock(ContainerInterface::class);
        $container3->method('get')->will($this->returnValueMap($map3));

        $this->object->__invoke($container3, $this->requestedName);
    }
}
