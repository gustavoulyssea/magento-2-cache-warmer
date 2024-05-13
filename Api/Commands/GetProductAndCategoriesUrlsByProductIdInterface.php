<?php

declare(strict_types=1);

namespace GustavoUlyssea\CacheWarmer\Api\Commands;

interface GetProductAndCategoriesUrlsByProductIdInterface
{
    /**
     * Get product and categories urls by product id
     *
     * @param int $productId
     * @param array $categoryIds
     * @return array
     */
    public function get(int $productId, array $categoryIds): array;
}
