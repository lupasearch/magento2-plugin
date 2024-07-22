<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product;

use LupaSearch\LupaSearchPlugin\Model\Config\QueriesConfigInterface;
use LupaSearch\LupaSearchPlugin\Model\QueryBuilder\QueryBuilderInterface;
use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\OrderedMap;
use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\OrderedMapInterfaceFactory as OrderedMapFactory;
use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\SearchQueryInterface;
use LupaSearch\LupaSearchPluginCore\Factories\QueryConfigurationFactoryInterface;
use LupaSearch\LupaSearchPluginCore\Factories\SearchQueryFactoryInterface;

class SuggestionsQueryBuilder implements QueryBuilderInterface
{
    private const FACET_MAX_LIMIT = 5;
    private const LIMIT = 5;

    private QueryConfigurationFactoryInterface $queryConfigurationFactory;

    private SearchQueryFactoryInterface $searchQueryFactory;

    private QueriesConfigInterface $queriesConfig;

    private OrderedMapFactory $orderedMapFactory;

    public function __construct(
        QueryConfigurationFactoryInterface $queryConfigurationFactory,
        SearchQueryFactoryInterface $searchQueryFactory,
        QueriesConfigInterface $queriesConfig,
        OrderedMapFactory $orderedMapFactory
    ) {
        $this->queryConfigurationFactory = $queryConfigurationFactory;
        $this->searchQueryFactory = $searchQueryFactory;
        $this->queriesConfig = $queriesConfig;
        $this->orderedMapFactory = $orderedMapFactory;
    }

    public function build(?SearchQueryInterface $searchQuery = null, ?int $storeId = 0): SearchQueryInterface
    {
        if (null === $searchQuery) {
            $searchQuery = $this->searchQueryFactory->create([]);
            $searchQuery->setConfiguration($this->queryConfigurationFactory->createSuggestionQueryConfiguration());

            return $searchQuery;
        }

        $configuration = $searchQuery->getConfiguration();
        $documentQueryKey = $this->queriesConfig->getProduct($storeId);

        if ($documentQueryKey) {
            $configuration->setDocumentQueryKey($documentQueryKey);
        }

        $configuration->setFacets($this->getFacets());
        $configuration->setLimit($this->getLimit());

        return $searchQuery;
    }

    /**
     * @return OrderedMap[]
     */
    private function getFacets(): array
    {
        $facet = $this->orderedMapFactory->create();
        $facet->add('key', 'categories');
        $facet->add('label', (string)__('in category'));
        $facet->add('limit', self::FACET_MAX_LIMIT);

        return [$facet];
    }

    private function getLimit(): int
    {
        return self::LIMIT;
    }
}
