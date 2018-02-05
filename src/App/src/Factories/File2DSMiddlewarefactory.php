<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 05.02.2018
 * Time: 16:10
 */

namespace rollun\app\Factories;


use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use rollun\app\Middleware\File2DSMiddleware;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class File2DSMiddlewarefactory implements FactoryInterface
{

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     * @throws File2DSException
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $resourceName = $requestedName;
        if (!$container->has($resourceName)) {
            throw new File2DSException(
                'Can\'t make File2DSMiddleware for resource: ' . $resourceName
            );
        }
        $dataStore = $container->get($resourceName);
        $file2ds = new File2DSMiddleware($dataStore);
        return $file2ds;
    }
}