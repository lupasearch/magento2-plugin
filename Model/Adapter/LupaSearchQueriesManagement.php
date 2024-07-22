<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Adapter;

use LupaSearch\LupaSearchPlugin\Model\Config\QueriesConfigInterface;
use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\SearchQueryConfigurationInterface;
use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\SearchQueryInterface;
use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\SuggestionQueryConfigurationInterface;
use LupaSearch\Exceptions\ApiException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Store\Model\StoreManagerInterface;
use Throwable;

class LupaSearchQueriesManagement implements QueriesManagementInterface
{
    /**
     * @var SearchEngineAdapterInterface
     */
    protected $searchIndexAdapter;

    /**
     * @var QueriesConfigInterface
     */
    protected $queriesConfig;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(
        SearchEngineAdapterInterface $searchEngineAdapter,
        QueriesConfigInterface $queriesConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->searchIndexAdapter = $searchEngineAdapter;
        $this->queriesConfig = $queriesConfig;
        $this->storeManager = $storeManager;
    }

    public function createQuery(SearchQueryInterface $searchQuery, int $storeId): string
    {
        $this->searchIndexAdapter->setStoreId($storeId);
        $indexId = $this->getIndexId($searchQuery);

        if (!$indexId) {
            throw new NotFoundException(__('Index ID for Store: %1 not found', $storeId));
        }

        try {
            $searchQuery = $this->searchIndexAdapter->getSearchQueriesApi()->createSearchQuery($indexId, $searchQuery);
        } catch (Throwable $e) {
            throw new ApiException($e->getMessage());
        }

        return $searchQuery->getQueryKey();
    }

    public function updateQuery(SearchQueryInterface $searchQuery, int $storeId): bool
    {
        try {
            $indexId = $this->getIndexId($searchQuery);
            $this->searchIndexAdapter->setStoreId($storeId);
            $this->searchIndexAdapter
                ->getSearchQueriesApi()
                ->updateSearchQuery($indexId, $searchQuery);
        } catch (Throwable $e) {
            throw new ApiException($e->getMessage());
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getAllQueries(string $type, int $storeId): array
    {
        $this->searchIndexAdapter->setStoreId($storeId);
        $indexId = $this->getIndexIdByType($type);

        if (!$indexId) {
            return [];
        }

        $queries = $this->searchIndexAdapter->getSearchQueriesApi()->getSearchQueries($indexId);

        return $queries ? $queries->getData() : [];
    }

    private function getIndexIdByType(string $type): string
    {
        if (str_contains($type, 'suggest')) {
            return $this->searchIndexAdapter->getSuggestionIndexId();
        }

        return $this->searchIndexAdapter->getIndexId();
    }

    private function getIndexId(SearchQueryInterface $searchQuery): string
    {
        $configuration = $searchQuery->getConfiguration();

        switch (true) {
            case $configuration instanceof SearchQueryConfigurationInterface:
                return $this->searchIndexAdapter->getIndexId();

            case $configuration instanceof SuggestionQueryConfigurationInterface:
                return $this->searchIndexAdapter->getSuggestionIndexId();

            default:
                return '';
        }
    }
}
