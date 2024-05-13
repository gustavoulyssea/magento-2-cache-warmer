<?php

declare(strict_types=1);

namespace GustavoUlyssea\CacheWarmer\Model\Commands;

use GustavoUlyssea\CacheWarmer\Api\Commands\GetEnabledStoreIdsInterface;
use GustavoUlyssea\CacheWarmer\Api\Commands\GetProductAndCategoriesUrlsByProductIdInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Category\Collection as CategoryCollection;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;

class GetProductAndCategoriesUrlsByProductId implements GetProductAndCategoriesUrlsByProductIdInterface
{
    /**
     * @param ProductCollectionFactory $productCollectionFactory
     * @param GetEnabledStoreIdsInterface $getEnabledStoreIds
     * @param CollectionFactory $categoryCollectionFactory
     */
    public function __construct(
        private readonly ProductCollectionFactory $productCollectionFactory,
        private readonly GetEnabledStoreIdsInterface $getEnabledStoreIds,
        private readonly CollectionFactory $categoryCollectionFactory,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function get(int $productId, array $categoryIds): array
    {
        $urls = [];
        foreach ($this->getEnabledStoreIds->get() as $storeId) {
            if (!$storeId) {
                continue;
            }
            $urls = array_merge(
                $urls,
                $this->getProductUrlsByStoreId($productId, $storeId),
                $this->getCategoryUrlsByStoreId($storeId, $categoryIds)
            );
        }
        return $urls;
    }

    /**
     * Get product urls by store id
     *
     * @param int $productId
     * @param int $storeId
     * @return array
     */
    public function getProductUrlsByStoreId(int $productId, int $storeId): array
    {
        $urls = [];
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addFieldToFilter('entity_id', ['eq' => $productId]);
        $productCollection->addStoreFilter($storeId);

        /** @var Product $product */
        foreach ($productCollection->getItems() as $product) {
            $urls[] = $product->setStoreId($storeId)->getProductUrl();
        }
        return $urls;
    }

    /**
     * Get category urls by store id
     *
     * @param int $storeId
     * @param array $categoryIds
     * @return array
     */
    public function getCategoryUrlsByStoreId(int $storeId, array $categoryIds): array
    {
        /** @var CategoryCollection $categoryCollection */
        $categoryCollection = $this->categoryCollectionFactory->create();
        $categoryCollection->setStoreId($storeId);
        $categoryCollection->addUrlRewriteToResult();
        $categoryCollection->addIsActiveFilter();

        $urls = [];
        /** @var Category $category */
        foreach ($categoryCollection->getItems() as $category) {
            if (in_array($category->getId(), $categoryIds)) {
                $urls[] = $category->getUrl();
            }
        }
        return $urls;
    }
}
