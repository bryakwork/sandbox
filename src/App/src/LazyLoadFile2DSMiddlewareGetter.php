<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 05.02.2018
 * Time: 16:02
 */

namespace rollun\app;

use Psr\Http\Message\ServerRequestInterface;
use rollun\actionrender\Interfaces\LazyLoadMiddlewareGetterInterface;
use rollun\actionrender\LazyLoadMiddlewareGetter\Attribute;
use rollun\app\Factories\File2DSMiddlewarefactory;

class LazyLoadFile2DSMiddlewareGetter extends Attribute
{
    /**
     * @var string
     */
    protected $attributeName;


    public function __construct($attributeName = "resourceName")
    {
        parent::__construct($attributeName);
    }

    /**
     * @param ServerRequestInterface $request
     * @return array
     */
    public function getLazyLoadMiddlewares(ServerRequestInterface $request)
    {
        $serviceName = $request->getAttribute($this->attributeName);
        $result = [
            LazyLoadMiddlewareGetterInterface::KEY_FACTORY_CLASS => File2DSMiddlewarefactory::class,
            LazyLoadMiddlewareGetterInterface::KEY_REQUEST_NAME => $serviceName,
            LazyLoadMiddlewareGetterInterface::KEY_OPTIONS => []
        ];
        return [
            $result
        ];
    }
}