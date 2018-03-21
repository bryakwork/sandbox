<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 12.02.2018
 * Time: 12:56
 */

namespace rollun\app;


use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use rollun\callback\Callback\CallbackInterface;
use Zend\Diactoros\Response\EmptyResponse;

class FileHandlerMiddleware implements MiddlewareInterface
{

    /**
     * @var Callback $handler
     */
    protected $handler;

    public function __construct(CallbackInterface $handler)
    {
        $this->handler = $handler;
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
        $uploadedFiles = $request->getAttribute('uploadedFiles');
        $result = $this->handler->invoke($uploadedFiles);
        $request->withAttribute('responseData', $result)
                ->withAttribute(ResponseInterface::class, new EmptyResponse(200));
        $response = $delegate->process($request);
        return $response;
    }
}