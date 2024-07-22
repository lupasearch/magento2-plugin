<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Indexer;

interface DataGeneratorInterface
{
    /**
     * @param int[] $ids
     * @return array<string|int|float|array<string>>
     */
    public function generate(array $ids, int $storeId): array;
}
