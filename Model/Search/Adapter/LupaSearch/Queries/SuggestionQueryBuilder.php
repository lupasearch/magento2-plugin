<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Queries;

use LupaSearch\LupaSearchPlugin\Model\Config\Queries\ProductConfigInterface;
use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\SuggestionQueryInterface;
use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\SuggestionQueryInterfaceFactory as SuggestionQueryFactory;
use Magento\Search\Model\QueryInterface;

class SuggestionQueryBuilder implements SuggestionQueryBuilderInterface
{
    private SuggestionQueryFactory $suggestionQueryFactory;

    private ProductConfigInterface $productConfig;

    public function __construct(SuggestionQueryFactory $suggestionQueryFactory, ProductConfigInterface $productConfig)
    {
        $this->suggestionQueryFactory = $suggestionQueryFactory;
        $this->productConfig = $productConfig;
    }

    public function build(QueryInterface $query): SuggestionQueryInterface
    {
        $suggestionQuery = $this->suggestionQueryFactory->create();
        $suggestionQuery->setSearchText($query->getQueryText());
        $suggestionQuery->setLimit($this->productConfig->getSearchSuggestionCount((int)$query->getStoreId()));

        return $suggestionQuery;
    }
}
