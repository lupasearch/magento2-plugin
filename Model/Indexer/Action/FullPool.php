<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Indexer\Action;

class FullPool implements FullPoolInterface
{
    /**
     * @var FullInterface[]
     */
    private $pool;

    /**
     * @param FullInterface[] $pool
     */
    public function __construct(array $pool = [])
    {
        $this->pool = $pool;
    }

    public function get(string $code): ?FullInterface
    {
        $indexer = $this->pool[$code] ?? null;

        return $indexer instanceof FullInterface ? $indexer : null;
    }
}
