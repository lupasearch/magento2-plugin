<?php

namespace LupaSearch\LupaSearchPlugin\Model\Indexer\Action;

interface RowsInterface
{
    /**
     * @param int[] $ids
     */
    public function execute(array $ids): void;

    /**
     * @param int $storeId
     * @param int[] $ids
     */
    public function executeByStore(int $storeId, array $ids): void;
}
