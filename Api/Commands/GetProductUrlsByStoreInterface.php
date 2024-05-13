<?php

declare(strict_types=1);

namespace GustavoUlyssea\CacheWarmer\Api\Commands;

interface GetProductUrlsByStoreInterface
{
    /**
     * Get product urls by store
     *
     * @param int $storeId
     * @return array
     */
    public function get(int $storeId): array;
}
