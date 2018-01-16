<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 10.01.2018
 * Time: 19:24
 */

namespace rollun\ebayTools;


use DTS\eBaySDK\OAuth\Services\OAuthService;
use DTS\eBaySDK\Taxonomy\Services\TaxonomyService;
use DTS\eBaySDK\Taxonomy\Types\GetACategorySubtreeRestRequest;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class CategoryExporterFactory implements FactoryInterface
{

    const KEY = 'ebayCategoriesExporter';
    const KEY_OAUTH = 'oauth';
    const KEY_CATEGORY= 'category';
    const KEY_TAXONOMY = 'taxonomy';
    const KEY_RESULT_DS = 'resultDataStore';

    /**
     * Create an object
     * Config example:
     * return [
     *      CategoryExporterFactory::KEY => [
     *          'oauth' =>[
     *              'credentials' => [
     *                  'appId' => '111-aaa',
     *                  'certId' => '222-bbb',
     *                  'devId' => '333-ccc'
     *              ],
     *              'ruName' => '444-ddd',
     *          ],
     *          'taxonomy' => [
     *              'apiVersion' => 'v1_beta',
     *              'globalId' => 'EBAY-US',
     *              'authorization' => '',
     *              'scope' => 'https://api.ebay.com/oauth/api_scope',
     *           ],
     *          'category' => [
     *              'category_id' => '6000',
     *              'category_tree_id' => '0',
     *          ],
     *          'resultDataStore' => 'myDataStore',
     *      ]
     * ]
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $config = $config[CategoryExporterFactory::KEY];
        if (!(isset($config)&&
              isset($config[CategoryExporterFactory::KEY_OAUTH])&&
              isset($config[CategoryExporterFactory::KEY_CATEGORY])&&
              isset($config[CategoryExporterFactory::KEY_TAXONOMY])&&
              isset($config[CategoryExporterFactory::KEY_RESULT_DS]))
        )
        {
            throw new ServiceNotCreatedException("Invalid CategoryExporter config");
        }
        $config = $config[CategoryExporterFactory::KEY];
        $oauth = new OAuthService($config['oauth']);
        $transciever = new OAuthTransciever($oauth);
        $resultDataStore = $container->get($config['resultDataStore']);
        $taxonomy = new TaxonomyService($config['taxonomy']);
        $request = new GetACategorySubtreeRestRequest($config['category']);

        return new CategoryExporter($transciever, $resultDataStore, $taxonomy, $request);
    }
}