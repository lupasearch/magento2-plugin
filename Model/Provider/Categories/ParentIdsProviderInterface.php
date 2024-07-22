<?php

namespace LupaSearch\LupaSearchPlugin\Model\Provider\Categories;

interface ParentIdsProviderInterface
{
    /**
     * @return int[][]
     */
    public function getAll(): array;

    /**
     * @param int $id
     * @return int[]
     */
    public function getById(int $id): array;

    /**
     * @param int[] $ids
     * @return int[][]
     */
    public function getByIds(array $ids): array;
}
