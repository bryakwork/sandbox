<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 05.02.2018
 * Time: 18:54
 */

namespace rollun\app;


use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use rollun\datastore\Rql\RqlParser;

class File2DSRequestDecoder implements MiddlewareInterface
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
        $rqlQueryStringWithXdebug = $request->getUri()->getQuery();
        $rqlQueryString = rtrim($rqlQueryStringWithXdebug, '&XDEBUG_SESSION_START=netbeans-xdebug');
        $rqlQueryObject = RqlParser::rqlDecode($rqlQueryString);
        $request = $request->withAttribute('rqlQueryObject', $rqlQueryObject);

        $response = $delegate->process($request);
        return $response;
    }
}