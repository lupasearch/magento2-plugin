<?php

namespace LupaSearch\LupaSearchPlugin\Model\Indexer\Action;

interface FullInterface
{
    public function execute(): void;

    public function executeByStore(int $storeId): void;
}
