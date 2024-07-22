<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Indexer\Action;

class RowsPoll implements RowsPollInterface
{
    /**
     * @var RowsInterface[]
     */
    private $pool;

    /**
     * @param RowsInterface[] $pool
     */
    public function __construct(array $pool = [])
    {
        $this->pool = $pool;
    }

    public function get(string $code): ?RowsInterface
    {
        $indexer = $this->pool[$code] ?? null;

        return $indexer instanceof RowsInterface ? $indexer : null;
    }
}
