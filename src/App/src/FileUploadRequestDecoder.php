<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 12.02.2018
 * Time: 12:45
 */

namespace rollun\app;


use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class FileUploadRequestDecoder implements MiddlewareInterface
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
        $fileObjects = $request->getUploadedFiles();
        $request = $request->withAttribute('uploadedFiles', $fileObjects);
        $response = $delegate->process($request);
        return $response;
    }
}