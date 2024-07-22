<?php

namespace LupaSearch\LupaSearchPlugin\Model\Indexer\Action;

interface RowsPollInterface
{
    public function get(string $code): ?RowsInterface;
}
