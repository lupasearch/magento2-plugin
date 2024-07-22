<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch;

use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\DocumentQueryResponseInterface;
use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\SuggestionQueryResponseInterface;
use Magento\Framework\Search\RequestInterface;
use Magento\Search\Model\QueryInterface;

interface QueriesInterface
{
    public function testDocument(RequestInterface $request): DocumentQueryResponseInterface;

    public function testSuggestion(QueryInterface $query): SuggestionQueryResponseInterface;
}
