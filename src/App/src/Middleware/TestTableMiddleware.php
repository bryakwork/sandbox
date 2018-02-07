<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 18.01.2018
 * Time: 10:58
 */

namespace rollun\app\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use rollun\actionrender\Renderer\Html\HtmlParamResolver;

class TestTableMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $params = $request->getQueryParams();
        $params = array_merge([
            'title' => 'Dashboard',
            'main_table' => [
                // можно все передать через параметры урла
                'url' => '/api/datastore/test_csv',
                'title' => 'test',
                'options' => [],
            ],
        ], $params);
        $request = $request->withAttribute('responseData', $params);
        $request = $request->withAttribute(HtmlParamResolver::KEY_ATTRIBUTE_TEMPLATE_NAME, 'app-table::table-wInput');
        $response = $delegate->process($request);

        return $response;
    }
}