<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Adapter;

class QueriesManagementPool
{
    /**
     * @var QueriesManagementInterface[]
     */
    private $pool;

    /**
     * @param QueriesManagementInterface[] $pool
     */
    public function __construct(array $pool = [])
    {
        $this->pool = $pool;
    }

    public function get(string $type): ?QueriesManagementInterface
    {
        $management = $this->pool[$type] ?? null;

        return $management instanceof QueriesManagementInterface ? $management : null;
    }
}
