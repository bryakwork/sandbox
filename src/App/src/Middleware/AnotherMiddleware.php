<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 01.11.2017
 * Time: 13:13
 */

namespace rollun\app\Middleware;


use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Session\Container;
use Zend\Session\SessionManager;

class AnotherMiddleware implements MiddlewareInterface
{

    /** @var  Container */
    protected $sessionContainer;

    /**
     * AnotherMiddleware constructor.
     * @param Container $sessionContainer
     */
    function __construct(Container $sessionContainer)
    {
        $this->sessionContainer = $sessionContainer;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $this->sessionContainer->username = 'John Ceena!';
        $params = $request->getQueryParams();
        $params["s"] = "aaaaa!";
        $request->withAttribute("queryParams", $params);
        $response = $delegate->process($request);
        return $response;
    }
}