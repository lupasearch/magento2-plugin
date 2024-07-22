<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Queries\SearchQuery;

use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\SearchQueryConfigurationInterface;
use Magento\Framework\Search\RequestInterface;

interface SearchQueryConfigurationBuilderInterface
{
    public function build(RequestInterface $request): SearchQueryConfigurationInterface;
}
