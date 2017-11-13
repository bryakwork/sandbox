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

class AnotherMiddleware implements MiddlewareInterface
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
        $params["s"] = "aaaaa!";
        $request->withAttribute("queryParams", $params);
        $response = $delegate->process($request);
        return $response;
    }
}