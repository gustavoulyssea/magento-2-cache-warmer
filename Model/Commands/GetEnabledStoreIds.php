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

namespace GustavoUlyssea\CacheWarmer\Model\Commands;

use GustavoUlyssea\CacheWarmer\Api\CacheWarmerConfigInterface;
use GustavoUlyssea\CacheWarmer\Api\Commands\GetEnabledStoreIdsInterface;
use Magento\Store\Api\StoreRepositoryInterface;

class GetEnabledStoreIds implements GetEnabledStoreIdsInterface
{
    /**
     * @param StoreRepositoryInterface $storeRepository
     * @param CacheWarmerConfigInterface $cacheWarmerConfig
     */
    public function __construct(
        private readonly StoreRepositoryInterface $storeRepository,
        private readonly CacheWarmerConfigInterface $cacheWarmerConfig
    ) {
    }

    /**
     * Get enabled store ids
     *
     * @return array
     */
    public function get(): array
    {
        $storeIds = [];
        foreach ($this->storeRepository->getList() as $store) {
            if (!$store->getId()) {
                continue;
            }
            if ($this->cacheWarmerConfig->isEnabled((int)$store->getId())) {
                $storeIds[] = $store->getId();
            }
        }
        return $storeIds;
    }
}
