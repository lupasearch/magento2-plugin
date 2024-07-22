<?php

namespace LupaSearch\LupaSearchPlugin\Model\Indexer;

use LupaSearch\Exceptions\BadResponseException;

interface PartialIndexerInterface
{
    /**
     * @param int[] $ids
     * @param int $storeId
     * @throws BadResponseException
     */
    public function reindex(array $ids, int $storeId): void;
}
