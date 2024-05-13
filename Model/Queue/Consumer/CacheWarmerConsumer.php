<?php

declare(strict_types=1);

namespace GustavoUlyssea\CacheWarmer\Model\Queue\Consumer;

use GustavoUlyssea\CacheWarmer\Api\UrlWarmerInterface;

class CacheWarmerConsumer
{
    public function __construct(
        private readonly UrlWarmerInterface $urlWarmer
    ) {
    }

    /**
     * Process queue
     *
     * @param string $rawData
     * @return void
     */
    public function process(string $rawData): void
    {
        $this->urlWarmer->execute($rawData);
    }
}
