<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Provider;

use function array_filter;

class ProviderCacheComposite implements ProviderCacheInterface
{
    /**
     * @var ProviderCacheInterface[]
     */
    private array $instances;

    /**
     * @param ProviderCacheInterface[] $instances
     */
    public function __construct(array $instances = [])
    {
        $this->instances = array_filter($instances);
    }

    /**
     * @param int[]|string[] $ids
     */
    public function warmup(array $ids, ?int $storeId = null): void
    {
        foreach ($this->instances as $instance) {
            if (!$instance instanceof ProviderCacheInterface) {
                continue;
            }

            $instance->warmup($ids, $storeId);
        }
    }

    public function flush(): void
    {
        foreach ($this->instances as $instance) {
            if (!$instance instanceof ProviderCacheInterface) {
                continue;
            }

            $instance->flush();
        }
    }
}
