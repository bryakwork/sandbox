<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 16.11.2017
 * Time: 19:28
 */

namespace rollun\BinaryParser\Converter;


use Interop\Container\ContainerInterface;
use rollun\datastore\DataStore\Interfaces\DataStoresInterface;
use Xiag\Rql\Parser\Query;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;


class ConverterAbstractFactory implements AbstractFactoryInterface
{
    const KEY = ConverterAbstractFactory::class;
    const KEY_ORIGIN_DS = "originDataStore";
    const KEY_DESTINATION_DS = "destinationDataStore";
    const KEY_QUERY = "query";
    const KEY_FILTERS = "filters";


    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return Converter
     * @throws WrongTypeException
     *
     * Config example:
     * ConverterAbstractFactory::class => [
     *  "CoolConverter" => [
     *      ConverterAbstractFactory::KEY_ORIGIN_DS => "datastore1",
     *      ConverterAbstractFactory::KEY_DESTINATION_DS => "datastore2",
     *      ConverterAbstractFactory::KEY_QUERY => new RqlQuery(),
     *      ConverterAbstractFactory::KEY_FILTERS => [
     *          "MSRP" => ["filterName" => PriceFixer::class,],
     *          "WEIGHT" => ["filterName" => PriceFixer::class,],
     *          ]
     *      ]
     *  ],
     */

    function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $params = $config[static::KEY][$requestedName];
        $query = (isset($params[static::KEY_QUERY]) ? $params[static::KEY_QUERY] : new Query());
        $filters = (isset($params[static::KEY_FILTERS]) ? $params[static::KEY_FILTERS] : []);
        $origin = $container->get($params[static::KEY_ORIGIN_DS]);
        $destination = (isset($params[static::KEY_FILTERS]) ? $container->get($params[static::KEY_DESTINATION_DS]) : $container->get($params[static::KEY_ORIGIN_DS]));
        if (is_a($origin, DataStoresInterface::class, true) &&
            is_a($destination, DataStoresInterface::class, true) &&
            is_a($query, Query::class, true) &&
            is_array($filters)
        ) {
            return new Converter($origin, $destination, $query, $filters);
        }
        throw new WrongTypeException();
    }

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @return bool
     */
    function canCreate(ContainerInterface $container, $requestedName)
    {
        $config = $container->get('config');
        if (isset($config[static::KEY][$requestedName])) {
            return true;
        }
        return false;
    }
}