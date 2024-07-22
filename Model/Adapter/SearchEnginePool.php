<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Adapter;

use LupaSearch\LupaSearchPlugin\Model\Adapter\Index\IndexProviderInterface;

use function array_filter;

class SearchEnginePool implements SearchEnginePoolInterface
{
    /**
     * @var SearchEngineAdapterInterface[]
     */
    private array $searchEngines;

    /**
     * @param SearchEngineAdapterInterface[] $searchEngines
     */
    public function __construct(array $searchEngines = [])
    {
        $this->searchEngines = $searchEngines;
    }

    public function get(string $type): ?SearchEngineAdapterInterface
    {
        $searchEngine = $this->searchEngines[$type] ?? null;

        return $searchEngine instanceof IndexProviderInterface ? $searchEngine : null;
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        return array_filter($this->searchEngines, static function ($searchEngine): bool {
            return $searchEngine instanceof SearchEngineAdapterInterface;
        });
    }
}
