<?php
chdir(dirname(__DIR__));
require 'vendor/autoload.php';

use DTS\eBaySDK\Taxonomy\Services\TaxonomyService;
use DTS\eBaySDK\Taxonomy\Types\GetACategorySubtreeRestRequest;
use rollun\datastore\DataStore\Interfaces\DataStoresInterface;

require_once 'config/env_configurator.php';
/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';
\rollun\dic\InsideConstruct::setContainer($container);

$service = new TaxonomyService([
    'apiVersion' => 'v1_beta',
    'globalId' => 'EBAY-US',
    'authorization' => 'v^1.1#i^1#p^1#I^3#r^0#f^0#t^H4sIAAAAAAAAAOVXaWwUVRzvdtsiRz24qSQuwxGvmX0zs7PHyK5sD6AJPegW5FKc4007dnZmMu9t2yUNNo0hKEgghC9GtIpWrBIgiEGoCKhNxGg8E/kgsd80FRMTj5BA4pvdbdlWwlmExP2ymf/7v//7/X7/Y+aBzpLxD29auunvUs+4wu5O0Fno8bATwfiS4kfu9haWFReAPAdPd+e8zqIu788LkZQ0bLEBItsyEfS1Jw0TiRljlEo5pmhJSEeiKSUhErEiJuI1y0SOAaLtWNhSLIPyVVdGKS0CocILIYWVI0HIS8RqDsVstKKUEI4AWZWFEB8GwYimknWEUrDaRFgycZTiABumAUuDQCMLREEQOYEJctwayrcSOki3TOLCACqWgStm9jp5WK8MVUIIOpgEoWLV8cWJunh1ZVVt40J/XqxYTocElnAKjXyqsFToWykZKXjlY1DGW0ykFAUiRPlj2RNGBhXjQ2BuAH5WahlIIU0TuAgHAkowMCZSLracpISvjMO16CqtZVxFaGIdp6+mKFFDfgYqOPdUS0JUV/rcv+UpydA1HTpRqqo8vjpeX0/Fyi0njSqlVlp20lJLm+XQ9Q2VtCaooYCmhDVaEBRBCbNs7qBstJzMo06qsExVd0VDvloLl0OCGo7WJpCnDXGqM+ucuIZdRPl+3JCGLPHzD2UxhZtNN68wSYTwZR6vnoHh3Rg7upzCcDjC6IWMRFFKsm1dpUYvZmoxVz7tKEo1Y2yLfn9bWxvTxjOW0+TnAGD9q2qWJZRmmCTN2J50ez3rr199A61nqCiQ7ES6iNM2wdJOapUAMJuoGBcMBUKhnO4jYcVGW/9lyOPsH9kRY9UhKhBYiQeqwMtyICyExqJDYrki9bs4oCyl6aTktEBsG5ICaYXUWSoJHV0VeUHj+LAGaZXMOToQ0TRaFtQgzWoQAghlWYmE/0+Ncq2lnlAsG9Zbhq6kx6Tgx6zYeUetlxycTkDDIIZrrfrLkkQuyVtOz+3166LoxkAkiGTrjFvbjGIl/ZZEhpprWp9BfVO8dfI+vKOSSghmmepq9kXGZOgyqFVhHIislEPe4UydO9cbrRZoki7BjmUY0FnJ3pQSYzfRb9M0vywrxdCJjOvvNGbXOSZvsLYlfBtZF3V51l6GOSuwAgjyPCfcFLeKTF4b0//B0LquxC61EIbqLfgA8Y+8DsUKMj+2y3MYdHkOkhsV8IP57Fwwp8S7osg7qQzpGDK6pDFIbzLJV74DmRaYtiXdKSzxrJ194O31eRew7ifBzOEr2HgvOzHvPgZmX1opZu+ZUcqGAQsCLBAETlgD5l5aLWKnF02lVy050k+fumi0/dlRM3feAzNeadgBSoedPJ7iAlIZBQeOHv2wlv/+yJHCJz45kajaeB9+7fP46Z/Onmz93fyF697jffT+F4ytNef+Or29b/fLF/e3JLXq0t73TpmJ/vMfa1urCn9bdLD8zHNdew89e9euOnvnrvePbel9HeOXBmclzynwwL7ZPcs/e/PF3fMXba6fvGDn4w+VbNj21eCFzRsHNk1p7Zi2aoq958dDPV/2/PA1HzKat82693Bgecd31MUVZRPemLy6Z0P3YN+SFYPTd9ETzjxV5pXDDYOfTpr8IPziePu3fZWwd++C50vPBgcmpKuaPrrQu2PdieMNfW/BV7/Zsz++fe35pWdK37X0cbP6fx14urt/zswP1u3r+GPa1JPz1y175+Sxx9qnDnRm0/cPgfQx6xoPAAA=',
    'credentials' => [
        'appId' => 'BorysDav-bryakwor-PRD-f5d74fc8f-55c5c811',
    ],
    'httpOptions' => [
        'verify' => false,
    ],
    'sandbox' => false,
    'verify' => false,
    'compressResponse' => true,
    'scope' => 'https://api.ebay.com/oauth/api_scope',
]);
$values = [
    'category_id' => '6000',
    'category_tree_id' => '0',
];
$request = new GetACategorySubtreeRestRequest($values);
$response = $service->getACategorySubtree($request);
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