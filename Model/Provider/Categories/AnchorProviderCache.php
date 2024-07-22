<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Provider\Categories;

use LupaSearch\LupaSearchPlugin\Model\Provider\ProviderCacheInterface;

use function array_flip;
use function array_intersect_key;
use function array_merge;
use function array_unique;
use function array_values;

class AnchorProviderCache implements AnchorProviderInterface, ProviderCacheInterface
{
    private AnchorProviderInterface $provider;

    /**
     * @var array<int, array<int>>|null
     */
    private ?array $cache = null;

    public function __construct(AnchorProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        if (null === $this->cache) {
            $this->cache = $this->provider->getAll();
        }

        return $this->cache;
    }

    /**
     * @inheritDoc
     */
    public function getByCategoryId(int $id): array
    {
        return $this->getAll()[$id] ?? [];
    }

    /**
     * @inheritDoc
     */
    public function getByCategoryIds(array $ids): array
    {
        if (!$ids) {
            return [];
        }

        $ids = array_intersect_key($this->getAll(), array_flip($ids));

        return $ids ? array_values(array_unique(array_merge(...$ids))) : [];
    }

    /**
     * @inheritDoc
     */
    // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInImplementedInterfaceAfterLastUsed
    public function warmup(array $ids, ?int $storeId = null): void
    {
        $this->getAll();
    }

    public function flush(): void
    {
        $this->cache = null;
    }
}
