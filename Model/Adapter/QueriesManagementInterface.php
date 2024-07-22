<?php

namespace LupaSearch\LupaSearchPlugin\Model\Adapter;

use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\SearchQueryInterface;
use LupaSearch\Exceptions\ApiException;
use Magento\Framework\Exception\NotFoundException;

interface QueriesManagementInterface
{
    public const TYPE_PRODUCT_SUGGEST = 'product_suggest';
    public const TYPE_PRODUCT_SEARCH_BOX = 'product_search_box';
    public const TYPE_PRODUCT_CATALOG = 'product_catalog';
    public const TYPE_PRODUCT = 'product';
    public const TYPE_CATEGORY = 'category';

    /**
     * @throws NotFoundException
     * @throws ApiException
     */
    public function createQuery(SearchQueryInterface $searchQuery, int $storeId): string;

    /**
     * @return SearchQueryInterface[]
     * @throws ApiException
     */
    public function getAllQueries(string $type, int $storeId): array;

    /**
     * @throws NotFoundException
     * @throws ApiException
     */
    public function updateQuery(SearchQueryInterface $searchQuery, int $storeId): bool;
}
