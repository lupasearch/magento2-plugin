<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Queries;

use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\SearchQueryInterface;
use Magento\Framework\Search\RequestInterface;
use Magento\Search\Model\QueryInterface;

interface SearchQueryBuilderInterface
{
    /**
     * @param RequestInterface|QueryInterface $request
     */
    public function build($request): SearchQueryInterface;
}
