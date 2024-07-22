<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Queries;

use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\SuggestionQueryInterface;
use Magento\Search\Model\QueryInterface;

interface SuggestionQueryBuilderInterface
{
    public function build(QueryInterface $query): SuggestionQueryInterface;
}
