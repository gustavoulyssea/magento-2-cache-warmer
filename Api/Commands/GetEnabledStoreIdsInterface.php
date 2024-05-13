<?php

declare(strict_types=1);

namespace GustavoUlyssea\CacheWarmer\Api\Commands;

interface GetEnabledStoreIdsInterface
{
    /**
     * Get enabled store ids
     *
     * @return array
     */
    public function get(): array;
}
