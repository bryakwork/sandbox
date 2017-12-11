<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 16.11.2017
 * Time: 19:28
 */

namespace rollun\CatalogTools\Converter;


use Interop\Container\ContainerInterface;
use rollun\datastore\DataStore\Interfaces\DataStoresInterface;
use rollun\datastore\Rql\RqlQuery;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;


class ConverterAbstractFactory implements AbstractFactoryInterface
{
    const KEY = ConverterAbstractFactory::class;
    const KEY_ORIGIN_DS = "originDataStore";
    const KEY_DESTINATION_DS = "destinationDataStore";
    const KEY_QUERY = "query";
    const KEY_FILTERS = "filters";
    const KEY_FILTER_CLASS_NAME = "filterClassName";
    const KEY_FILTER_PARAMS = "filterParams";


    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return DataStoreConverter
     * @throws WrongTypeException
     *
     * Config example:
     * ConverterAbstractFactory::class => [
     *  "CoolConverter" => [
     *      ConverterAbstractFactory::KEY_ORIGIN_DS => "datastore1",
     *      ConverterAbstractFactory::KEY_DESTINATION_DS => "datastore2",
     *      ConverterAbstractFactory::KEY_QUERY => "and(lt(age,5),eq(name,testName0))&limit(10)",
     *      ConverterAbstractFactory::KEY_FILTERS => [
     *          "MSRP" => ["ConverterAbstractFactory::KEY_FILTER" => PriceFixer::class, ConverterAbstractFactory::KEY_FILTER_PARAMS = ["paramName" => "paramValue"]],
     *          "WEIGHT" => ["ConverterAbstractFactory::KEY_FILTER" => WeightFilter::class,],
     *          ]
     *      ]
     *  ],
     */

    function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $params = $config[static::KEY][$requestedName];
        $query = (isset($params[static::KEY_QUERY]) ? new RqlQuery($params[static::KEY_QUERY]) : new RqlQuery());
        $filters = (isset($params[static::KEY_FILTERS]) ? $params[static::KEY_FILTERS] : []);
        $origin = $container->get($params[static::KEY_ORIGIN_DS]);
        $destination = (isset($params[static::KEY_FILTERS]) ? $container->get($params[static::KEY_DESTINATION_DS]) : $container->get($params[static::KEY_ORIGIN_DS]));
        if (is_a($origin, DataStoresInterface::class, true) &&
            is_a($destination, DataStoresInterface::class, true) &&
            is_a($query, RqlQuery::class, true) &&
            is_array($filters)
        ) {
            return new DataStoreConverter($origin, $destination, $query, $filters);
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