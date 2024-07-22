<?php

namespace LupaSearch\LupaSearchPlugin\Model\Adapter\Index;

use Magento\Framework\Search\RequestInterface;
use Magento\Search\Model\QueryInterface;

interface IndexProviderInterface
{
    public function getId(int $storeId): string;

    public function getIdByRequest(RequestInterface $request): string;

    public function getSuggestionId(int $storeId): string;

    public function getSuggestionIdByQuery(QueryInterface $query): string;
}
