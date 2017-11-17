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
     */
    function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $params = $config[static::KEY][$requestedName];
        $query = (isset($params[static::KEY_QUERY]) ?  $params[static::KEY_QUERY] : new Query());
        $filters = (isset($params[static::KEY_FILTERS]) ?  $params[static::KEY_FILTERS] : []);
        $origin = $container->get($params[static::KEY_ORIGIN_DS]);
        $destination = $container->get($params[static::KEY_DESTINATION_DS]);
        if (is_a($origin, DataStoresInterface::class, true) &&
            is_a($destination, DataStoresInterface::class, true) &&
            is_a($query, Query::class, true) &&
            is_array($filters)
        ) {
            return new Converter($origin, $destination,$query, $filters);
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