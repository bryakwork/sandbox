<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 03.01.2018
 * Time: 17:22
 */

namespace rollun\ebayTools;

use DTS\eBaySDK\Taxonomy\Services\TaxonomyService;
use DTS\eBaySDK\Taxonomy\Types\GetACategorySubtreeRestRequest;
use rollun\datastore\DataStore\Interfaces\DataStoresInterface;

class CategoryExporter
{
    /**
     * @var OAuthTransciever
     */
    protected $transciever;
    /**
     * @var GetACategorySubtreeRestRequest
     */
    protected $request;
    /**
     * @var DataStoresInterface
     */
    protected $resultDataStore;
    /**
     * @var TaxonomyService
     */
    protected $taxonomy;

    public function __construct(OAuthTransciever $transciever, DataStoresInterface $resultDataStore, TaxonomyService $taxonomy, GetACategorySubtreeRestRequest $request)
    {
        $this->transciever = $transciever;
        $this->resultDataStore = $resultDataStore;
        $this->taxonomy = $taxonomy;
        $this->request = $request;
    }

    protected function processCategoryTree($category, DataStoresInterface $resultDataStore, $parentId = 0)
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

    public function __invoke()
    {
        $token = $this->transciever->__invoke();
        $config = $this->taxonomy->getConfig();
        $config['authorization'] = $token;
        $this->taxonomy->setConfig($config);
        $response = $this->taxonomy->getACategorySubtree($this->request);
        $response = $response->toArray();
        $category = $response['categorySubtreeNode'];
        $this->processCategoryTree($category, $this->resultDataStore);
    }

}