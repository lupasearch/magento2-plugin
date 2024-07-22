<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Aggregation\Bucket;

use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\OrderedMapInterface;

interface ValuesBuilderInterface
{
    /**
     * @return array<array{value: string, count: int}>
     */
    public function build(OrderedMapInterface $facet): array;
}
