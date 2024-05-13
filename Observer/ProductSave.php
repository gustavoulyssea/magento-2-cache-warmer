<?php

declare(strict_types=1);

namespace GustavoUlyssea\CacheWarmer\Observer;

use GustavoUlyssea\CacheWarmer\Api\CacheWarmerConfigInterface;
use GustavoUlyssea\CacheWarmer\Api\Commands\GetProductAndCategoriesUrlsByProductIdInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\MessageQueue\PublisherInterface;

class ProductSave implements ObserverInterface
{
    /**
     * @param GetProductAndCategoriesUrlsByProductIdInterface $getProductAndCategoriesUrlsByProductId
     * @param PublisherInterface $publisher
     */
    public function __construct(
        private readonly GetProductAndCategoriesUrlsByProductIdInterface $getProductAndCategoriesUrlsByProductId,
        private readonly PublisherInterface $publisher
    ) {
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer): void
    {
        $productId = $observer->getProduct()->getEntityId();
        $categoryIds = $observer->getProduct()->getCategoryIds();

        foreach ($this->getProductAndCategoriesUrlsByProductId->get($productId, $categoryIds) as $url) {
            $this->publisher->publish(CacheWarmerConfigInterface::QUEUE_TOPIC_NAME, $url);
        }
    }
}
