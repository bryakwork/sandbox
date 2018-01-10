<?php
chdir(dirname(__DIR__));
require 'vendor/autoload.php';

use DTS\eBaySDK\OAuth\Services\OAuthService;
use DTS\eBaySDK\Taxonomy\Services\TaxonomyService;
use DTS\eBaySDK\Taxonomy\Types\GetACategorySubtreeRestRequest;
use rollun\datastore\DataStore\Interfaces\DataStoresInterface;
use rollun\ebayTools\CategoryExporter;

require_once 'config/env_configurator.php';
/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';
\rollun\dic\InsideConstruct::setContainer($container);

$categoryGetter = $container->get(CategoryExporter::class);
$categoryGetter();
return;

$oauth = new OAuthService([
    'credentials' => [
        'appId' => 'BorysDav-bryakwor-PRD-f5d74fc8f-55c5c811',
        'certId' => 'PRD-5d74fc8fb3cb-fee5-4821-bcf7-bbd1',
        'devId' => 'daadae6-c13c-494d-9be7-5bfa6c4c73ab'
    ],
    'ruName' => 'Borys_Davydovsk-BorysDav-bryakw-vsuvpeb'
]);

$response = $oauth->getAppToken();
$response = $response->toArray();
$token = $response['access_token'];

$taxonomy = new TaxonomyService([
    'apiVersion' => 'v1_beta',
    'globalId' => 'EBAY-US',
    'authorization' => '',
    'scope' => 'https://api.ebay.com/oauth/api_scope',
]);
$config = $taxonomy->getConfig();
$config['authorization'] = $token;
$taxonomy->setConfig($config);
$values = [
    'category_id' => '6000',
    'category_tree_id' => '0',
];
$request = new GetACategorySubtreeRestRequest($values);
$response = $taxonomy->getACategorySubtree($request);
$response = $response->toArray();
$category = $response['categorySubtreeNode'];

$resultDataStore = $container->get('ebayCategories');
processCategoryTree($category, $resultDataStore);

function processCategoryTree($category, DataStoresInterface $resultDataStore, $parentId = 0)
{
    $processedCategory = [
        'id' => $category['category']['categoryId'],
        'name' => $category['category']['categoryName'],
        'level' => $category['categoryTreeNodeLevel'],
        'parent' => $parentId,
        'isLeaf' => (isset($category['leafCategoryTreeNode']) ? true : false)
    ];
    $resultDataStore->create($processedCategory, true);
    if (!isset($category['leafCategoryTreeNode'])) {
        foreach ($category['childCategoryTreeNodes'] as $node) {
            processCategoryTree($node, $resultDataStore, $category['category']['categoryId']);
        }
    }
}