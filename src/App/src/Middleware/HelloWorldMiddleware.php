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
        foreach ($params as $i => $j) {
            $response .= "parameter " . $i . " = " . $j . ";\n";
        }
        return new HtmlResponse($response);
    }
}