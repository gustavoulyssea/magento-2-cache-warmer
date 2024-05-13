<?php

/**
 * @author Gustavo Ulyssea - gustavo.ulyssea@gmail.com
 * @copyright Copyright (c) 2024 Gustavo Ulyssea (https://gum.net.br)
 * @package Gustavo Ulyssea Magento 2 Cache Warmer
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY Gustavo Ulyssea (https://gum.net.br). AND CONTRIBUTORS
 * ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED
 * TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED.  IN NO EVENT SHALL THE FOUNDATION OR CONTRIBUTORS
 * BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

declare(strict_types=1);

namespace GustavoUlyssea\CacheWarmer\Cron;

use GustavoUlyssea\CacheWarmer\Api\CacheWarmerConfigInterface;
use GustavoUlyssea\CacheWarmer\Api\Commands\GetCategoryUrlsByStoreInterface;
use GustavoUlyssea\CacheWarmer\Api\Commands\GetEnabledStoreIdsInterface;
use GustavoUlyssea\CacheWarmer\Api\Commands\GetProductUrlsByStoreInterface;
use Magento\Framework\MessageQueue\PublisherInterface;
use Psr\Log\LoggerInterface;

class QueueUrls
{
    public const MESSAGE_STARTING = 'Cache warmer starting...';
    public const MESSAGE_FINISHED = 'Cache warmer finished.';

    /**
     * @param GetEnabledStoreIdsInterface $getEnabledStoreIds
     * @param GetProductUrlsByStoreInterface $getProductUrlsByStore
     * @param GetCategoryUrlsByStoreInterface $getCategoryUrlsByStore
     * @param LoggerInterface $logger
     * @param PublisherInterface $publisher
     */
    public function __construct(
        private readonly GetEnabledStoreIdsInterface $getEnabledStoreIds,
        private readonly GetProductUrlsByStoreInterface $getProductUrlsByStore,
        private readonly GetCategoryUrlsByStoreInterface $getCategoryUrlsByStore,
        private readonly LoggerInterface $logger,
        private readonly PublisherInterface $publisher
    ) {
    }

    /**
     * Cron execution
     *
     * @return void
     */
    public function execute(): void
    {
        $this->logger->info(self::MESSAGE_STARTING);
        foreach ($this->getEnabledStoreIds->get() as $storeId) {
            foreach ($this->getCategoryUrlsByStore->get((int) $storeId) as $categoryUrl) {
                $this->publisher->publish(CacheWarmerConfigInterface::QUEUE_TOPIC_NAME, $categoryUrl);
            }
            foreach ($this->getProductUrlsByStore->get((int) $storeId) as $productUrl) {
                $this->publisher->publish(CacheWarmerConfigInterface::QUEUE_TOPIC_NAME, $productUrl);
            }
        }
        $this->logger->info(self::MESSAGE_FINISHED);
    }
}
