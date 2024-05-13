<?php

declare(strict_types=1);

namespace GustavoUlyssea\CacheWarmer\Api\Commands;

interface GetCategoryUrlsByStoreInterface
{
    /**
     * Get category urls by store id
     *
     * @param int $storeId
     * @return array
     */
    public function get(int $storeId): array;
}
