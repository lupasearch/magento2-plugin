<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product;

use LupaSearch\LupaSearchPlugin\Model\Hydrator\ProductHydratorInterface;

use function ksort;

class HydratorPool
{
    /**
     * @var ProductHydratorInterface[]|null
     */
    private ?array $pool = null;

    /**
     * @var ProductHydratorInterface[]
     */
    private array $hydrators = [];

    /**
     * @param ProductHydratorInterface[] $hydrators
     */
    public function __construct(array $hydrators = [])
    {
        $this->hydrators = $hydrators;
    }

    /**
     * @return ProductHydratorInterface[]
     */
    public function getAll(): array
    {
        if (null !== $this->pool) {
            return $this->pool;
        }

        $this->pool = [];

        ksort($this->hydrators);

        foreach ($this->hydrators as $hydrator) {
            if (!$hydrator instanceof ProductHydratorInterface) {
                continue;
            }

            $this->pool[] = $hydrator;
        }

        return $this->pool;
    }
}
