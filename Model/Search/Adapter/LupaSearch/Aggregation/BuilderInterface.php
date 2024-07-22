<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Aggregation;

use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\DocumentQueryResponseInterface;
use Magento\Framework\Api\Search\AggregationInterface;
use Magento\Framework\Search\RequestInterface;

interface BuilderInterface
{
    public function build(
        DocumentQueryResponseInterface $queryResponse,
        RequestInterface $request
    ): AggregationInterface;
}
