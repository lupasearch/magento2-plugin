<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Queries;

use LupaSearch\LupaSearchPlugin\Model\Config\Queries\ProductConfigInterface;
use LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Queries\SearchQuery\SearchQueryConfigurationBuilderInterface;
use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\QueryConfigurationInterface;
use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\SearchQueryInterface;
use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\SuggestionQueryConfigurationInterface;
use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\SuggestionQueryConfigurationInterfaceFactory;
use LupaSearch\LupaSearchPluginCore\Factories\SearchQueryFactoryInterface;
use Magento\Framework\Search\RequestInterface;
use Magento\Search\Model\QueryInterface;

class SearchQueryBuilder implements SearchQueryBuilderInterface
{
    private SearchQueryFactoryInterface $searchQueryFactory;

    private SuggestionQueryConfigurationInterfaceFactory $suggestionQueryConfigurationFactory;

    private ProductConfigInterface $productConfig;

    private SearchQueryConfigurationBuilderInterface $searchQueryConfigurationBuilder;

    public function __construct(
        SearchQueryFactoryInterface $searchQueryFactory,
        SuggestionQueryConfigurationInterfaceFactory $suggestionQueryConfigurationFactory,
        ProductConfigInterface $productConfig,
        SearchQueryConfigurationBuilderInterface $searchQueryConfigurationBuilder
    ) {
        $this->searchQueryFactory = $searchQueryFactory;
        $this->suggestionQueryConfigurationFactory = $suggestionQueryConfigurationFactory;
        $this->productConfig = $productConfig;
        $this->searchQueryConfigurationBuilder = $searchQueryConfigurationBuilder;
    }

    /**
     * @inheritDoc
     */
    public function build($request): SearchQueryInterface
    {
        $searchQuery = $this->searchQueryFactory->create([]);
        $searchQuery->setDescription('LupaSearch query');
        $searchQuery->setConfiguration($this->buildConfiguration($request));
        $searchQuery->setDebugMode(false);

        return $searchQuery;
    }

    /**
     * @param RequestInterface|QueryInterface $request
     */
    private function buildConfiguration($request): QueryConfigurationInterface
    {
        if ($request instanceof QueryInterface) {
            return $this->buildSuggestionConfiguration($request);
        }

        return $this->searchQueryConfigurationBuilder->build($request);
    }

    private function buildSuggestionConfiguration(QueryInterface $query): SuggestionQueryConfigurationInterface
    {
        $configuration = $this->suggestionQueryConfigurationFactory->create();
        $configuration->setLimit($this->productConfig->getSearchSuggestionCount((int)$query->getStoreId()));

        return $configuration;
    }
}
