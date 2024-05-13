<?php

declare(strict_types=1);

namespace GustavoUlyssea\CacheWarmer\Model\Commands;

use GustavoUlyssea\CacheWarmer\Api\Commands\GetProductUrlsByStoreInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class GetProductUrlsByStore implements GetProductUrlsByStoreInterface
{
    /**
     * @param CollectionFactory $productCollectionFactory
     */
    public function __construct(
        private readonly CollectionFactory $productCollectionFactory,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function get(int $storeId): array
    {
        $urls = [];

        /** @var Collection $productCollection */
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addStoreFilter($storeId);

        /** @var Product $product */
        foreach ($productCollection->getItems() as $product) {
            $urls[] = $product->setStoreId($storeId)->getProductUrl();
        }
        return $urls;
    }
}
