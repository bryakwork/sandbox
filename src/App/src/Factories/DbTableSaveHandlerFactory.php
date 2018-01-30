<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 01.12.2017
 * Time: 11:17
 */

namespace rollun\app\Factories;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SaveHandler\DbTableGateway;
use Zend\Session\SaveHandler\DbTableGatewayOptions;

class DbTableSaveHandlerFactory implements FactoryInterface
{
    const KEY = DbTableSaveHandlerFactory::class;
    const KEY_TABLEGATEWAY = "tableGateway";
    const KEY_TABLEGATEWAYOPTIONS = "tableGatewayOptions";

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return DbTableGateway
     *
     * Creates a DbTableGateway object using params from config
     * Config example:
     * return [
     *      DbTableSaveHandlerFactory::KEY => [
     *          SaveHandlerInterface::class => [
     *                 DbTableSaveHandlerFactory::KEY_TABLEGATEWAY => "myTableGatewayName",
     *                 DbTableSaveHandlerFactory::KEY_TABLEGATEWAYOPTIONS = "MyTableGatewayOptionsName"
     *           ]
     *      ]
     * ]
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');

        /** @var array $params
         * SaveHandler params from config array
         */
        $params = $config[DbTableSaveHandlerFactory::KEY];

        /** @var \Zend\Db\TableGateway\TableGateway $tableGateway
         * get tableGateway by name from params array
         */
        $tableGateway = $container->get($params[$requestedName][DbTableSaveHandlerFactory::KEY_TABLEGATEWAY]);

        /** @var DbTableGatewayOptions $dbTableOptions
         * get dbTableOptions by name from params
         * if not found, create new DbTableGatewayOptions object and use it instead
         */
        $dbTableOptions = (isset($params[$requestedName][DbTableSaveHandlerFactory::KEY_TABLEGATEWAYOPTIONS])?
            $params[$requestedName][DbTableSaveHandlerFactory::KEY_TABLEGATEWAYOPTIONS]: new DbTableGatewayOptions());

        /** @var DbTableGateway $saveHandler */
        $saveHandler = new DbTableGateway($tableGateway, $dbTableOptions);

        return $saveHandler;
    }

    function canCreate(ContainerInterface $container, $requestedName)
    {
        $config = $container->get('config');
        if (isset($config[DbTableSaveHandlerFactory::KEY][$requestedName])) {
            return true;
        }
        return false;
    }
}