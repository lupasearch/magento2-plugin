<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Adapter;

interface SearchEnginePoolInterface
{
    public function get(string $type): ?SearchEngineAdapterInterface;

    /**
     * @return SearchEngineAdapterInterface[]
     */
    public function getAll(): array;
}
