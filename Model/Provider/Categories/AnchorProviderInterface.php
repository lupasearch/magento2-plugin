<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Provider\Categories;

interface AnchorProviderInterface
{
    /**
     * @return array<int, array<int>>
     */
    public function getAll(): array;

    /**
     * @return int[]
     */
    public function getByCategoryId(int $id): array;

    /**
     * @param int[] $ids
     * @return int[]
     */
    public function getByCategoryIds(array $ids): array;
}
