<?php

namespace LupaSearch\LupaSearchPlugin\Model\QueryBuilder;

use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\SearchQueryInterface;

interface QueryBuilderInterface
{
    public function build(?SearchQueryInterface $searchQuery = null, ?int $storeId = 0): SearchQueryInterface;
}
