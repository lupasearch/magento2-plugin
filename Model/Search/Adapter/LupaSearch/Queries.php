<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch;

use LupaSearch\LupaSearchPlugin\Model\Adapter\Index\IndexProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Queries\DocumentQueryBuilderInterface;
use LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Queries\SearchQueryBuilderInterface;
use LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Queries\SuggestionQueryBuilderInterface;
use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\DocumentQueryResponseInterface;
use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\SuggestionQueryResponseInterface;
use LupaSearch\LupaSearchPluginCore\Api\SearchQueriesApiInterfaceFactory;
use LupaSearch\LupaSearchPluginCore\Model\LupaClientFactoryInterface;
use Magento\Framework\Search\Request\Dimension;
use Magento\Framework\Search\RequestInterface;
use Magento\Search\Model\Query;
use Magento\Search\Model\QueryInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreDimensionProvider;

class Queries implements QueriesInterface
{
    private LupaClientFactoryInterface $lupaClientFactory;

    private SearchQueriesApiInterfaceFactory $searchQueriesApiFactory;

    private IndexProviderInterface $indexProvider;

    private SearchQueryBuilderInterface $searchQueryBuilder;

    private DocumentQueryBuilderInterface $documentQueryBuilder;

    private SuggestionQueryBuilderInterface $suggestionQueryBuilder;

    public function __construct(
        LupaClientFactoryInterface $lupaClientFactory,
        SearchQueriesApiInterfaceFactory $searchQueriesApiFactory,
        IndexProviderInterface $indexProvider,
        SearchQueryBuilderInterface $searchQueryBuilder,
        DocumentQueryBuilderInterface $documentQueryBuilder,
        SuggestionQueryBuilderInterface $suggestionQueryBuilder
    ) {
        $this->lupaClientFactory = $lupaClientFactory;
        $this->searchQueriesApiFactory = $searchQueriesApiFactory;
        $this->indexProvider = $indexProvider;
        $this->searchQueryBuilder = $searchQueryBuilder;
        $this->documentQueryBuilder = $documentQueryBuilder;
        $this->suggestionQueryBuilder = $suggestionQueryBuilder;
    }

    public function testDocument(RequestInterface $request): DocumentQueryResponseInterface
    {
        $storeId = $this->getStoreId($request);
        $client = $this->lupaClientFactory->create($storeId);

        return $this->searchQueriesApiFactory->create(['client' => $client])->testSearchQuery(
            $this->indexProvider->getIdByRequest($request),
            $this->searchQueryBuilder->build($request),
            $this->documentQueryBuilder->build($request),
        );
    }

    public function testSuggestion(QueryInterface $query): SuggestionQueryResponseInterface
    {
        $storeId = $query instanceof Query ? (int)$query->getStoreId() : Store::DISTRO_STORE_ID;
        $client = $this->lupaClientFactory->create($storeId);

        return $this->searchQueriesApiFactory->create(['client' => $client])->testSearchQuery(
            $this->indexProvider->getSuggestionIdByQuery($query),
            $this->searchQueryBuilder->build($query),
            $this->suggestionQueryBuilder->build($query),
        );
    }

    private function getStoreId(RequestInterface $request): int
    {
        $scope = $request->getDimensions()[StoreDimensionProvider::DIMENSION_NAME] ?? null;

        return $scope instanceof Dimension ? (int)$scope->getValue() : 0;
    }
}
