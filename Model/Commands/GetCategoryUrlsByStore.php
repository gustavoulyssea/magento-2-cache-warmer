<?php

declare(strict_types=1);

namespace GustavoUlyssea\CacheWarmer\Model\Commands;

use GustavoUlyssea\CacheWarmer\Api\Commands\GetCategoryUrlsByStoreInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator;
use Magento\Framework\UrlFactory;
use Magento\Store\Api\StoreRepositoryInterface;

class GetCategoryUrlsByStore implements GetCategoryUrlsByStoreInterface
{
    public function __construct(
        private readonly CollectionFactory $categoryCollectionFactory
    ) {
    }

    public function get(int $storeId): array
    {
        /** @var Collection $categoryCollection */
        $categoryCollection = $this->categoryCollectionFactory->create();
        $categoryCollection->setStoreId($storeId);
        $categoryCollection->addUrlRewriteToResult();
        $categoryCollection->addIsActiveFilter();

        $urls = [];
        /** @var Category $category */
        foreach ($categoryCollection->getItems() as $category) {
            $urls[] = $category->getUrl();
        }
        return $urls;
    }
}
