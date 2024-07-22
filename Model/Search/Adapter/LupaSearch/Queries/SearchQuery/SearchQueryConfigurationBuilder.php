<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Queries\SearchQuery;

use LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Queries\SearchQuery\Configuration\FacetsBuilderInterface;
use LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Queries\SearchQuery\Configuration\FiltersBuilderInterface;
use LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Queries\SearchQuery\Configuration\FilterValueProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Queries\SearchQuery\Configuration\SortBuilderInterface;
use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\SearchQueryConfigurationInterface;
use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\SearchQueryConfigurationInterfaceFactory;
use LupaSearch\LupaSearchPluginCore\Factories\OrderedMapFactory;
use Magento\Framework\Search\RequestInterface;

use function array_keys;
use function array_merge;

class SearchQueryConfigurationBuilder implements SearchQueryConfigurationBuilderInterface
{
    private SearchQueryConfigurationInterfaceFactory $searchQueryConfigurationFactory;

    private FilterValueProviderInterface $filterValueProvider;

    private SortBuilderInterface $sortBuilder;

    private FacetsBuilderInterface $facetsBuilder;

    private FiltersBuilderInterface $filtersBuilder;

    public function __construct(
        SearchQueryConfigurationInterfaceFactory $searchQueryConfigurationFactory,
        FilterValueProviderInterface $filterValueProvider,
        SortBuilderInterface $sortBuilder,
        FacetsBuilderInterface $facetsBuilder,
        FiltersBuilderInterface $filtersBuilder
    ) {
        $this->searchQueryConfigurationFactory = $searchQueryConfigurationFactory;
        $this->filterValueProvider = $filterValueProvider;
        $this->sortBuilder = $sortBuilder;
        $this->facetsBuilder = $facetsBuilder;
        $this->filtersBuilder = $filtersBuilder;
    }

    public function build(RequestInterface $request): SearchQueryConfigurationInterface
    {
        $filters = $this->filtersBuilder->build($request->getQuery()->getMust());
        $exclusionFilters = $this->filtersBuilder->build($request->getQuery()->getMustNot());

        $configuration = $this->searchQueryConfigurationFactory->create();
        $configuration->setSelectFields(['id']);
        $configuration->setQueryFields(OrderedMapFactory::create(['id' => 10]));
        $configuration->setFilters(OrderedMapFactory::create($filters));
        $configuration->setExclusionFilters(OrderedMapFactory::create($exclusionFilters));
        $configuration->setFilterableFields(array_merge(array_keys($filters), array_keys($exclusionFilters)));
        $configuration->setFacets($this->facetsBuilder->build($request));
        $configuration->setOffset($request->getFrom());
        $configuration->setSort($this->sortBuilder->build($request));

        return $configuration;
    }
}
