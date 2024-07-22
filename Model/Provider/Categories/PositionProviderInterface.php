<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Provider\Categories;

interface PositionProviderInterface
{
    /**
     * @param int $id
     * @return array<int, int>
     */
    public function getByProductId(int $id): array;

    /**
     * @param int[] $ids
     * @return array<int, array<int, int>>
     */
    public function getByProductIds(array $ids): array;
}
