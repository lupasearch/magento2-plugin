<?php

namespace LupaSearch\LupaSearchPlugin\Model\Provider;

interface ProviderCacheInterface
{
    /**
     * @param int[] $ids
     * @param int|null $storeId
     */
    public function warmup(array $ids, ?int $storeId = null): void;

    public function flush(): void;
}
