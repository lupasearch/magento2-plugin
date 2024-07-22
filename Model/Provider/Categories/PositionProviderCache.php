<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Provider\Categories;

use LupaSearch\LupaSearchPlugin\Model\Provider\ProviderCacheInterface;

use function array_flip;
use function array_intersect_key;

class PositionProviderCache implements PositionProviderInterface, ProviderCacheInterface
{
    private PositionProviderInterface $positionProvider;

    /**
     * @var array<int, array<int, int>>
     */
    private array $cache = [];

    public function __construct(PositionProviderInterface $positionProvider)
    {
        $this->positionProvider = $positionProvider;
    }

    /**
     * @inheritDoc
     */
    public function getByProductId(int $id): array
    {
        return $this->getByProductIds([$id])[$id] ?? [];
    }

    /**
     * @inheritDoc
     */
    public function getByProductIds(array $ids): array
    {
        return array_intersect_key($this->cache, array_flip($ids));
    }

    /**
     * @inheritDoc
     */
    // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInImplementedInterfaceAfterLastUsed
    public function warmup(array $ids, ?int $storeId = null): void
    {
        $this->cache = $this->positionProvider->getByProductIds($ids);
    }

    public function flush(): void
    {
        $this->cache = [];
    }
}
