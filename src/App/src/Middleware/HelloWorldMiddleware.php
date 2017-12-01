<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 01.11.2017
 * Time: 11:11
 */

namespace rollun\app\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Session\Container;
use Zend\Session\SessionManager;

class HelloWorldMiddleware implements MiddlewareInterface
{

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
        $params = $request->getQueryParams();
        $response = "You sent: ";
        foreach ($params as $key => $value) {
            $response .= "parameter " . $key . " = " . $value . ";\n";
        }
        $user_session = new Container('user');
        $response .= $user_session->username;
        return new HtmlResponse($response);
    }
}