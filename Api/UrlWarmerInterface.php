<?php

namespace GustavoUlyssea\CacheWarmer\Api;

interface UrlWarmerInterface
{
    public function execute(string $url): void;
}
