<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 01.12.2017
 * Time: 16:20
 */

namespace rollun\App\Factories;


use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use rollun\App\Middleware\AnotherMiddleware;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\Container;
use Zend\Session\SessionManager;

class SessionAwareMiddlewareFactory implements FactoryInterface
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
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $sessionManager = $container->get(SessionManager::class);
        $sessionContainer = new Container("Default", $sessionManager);
        $middleware = new AnotherMiddleware($sessionContainer);
        return $middleware;
    }
}