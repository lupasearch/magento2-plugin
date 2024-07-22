<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Aggregation\Bucket;

use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\OrderedMapInterface;

class RangeValuesBuilder implements ValuesBuilderInterface
{
    /**
     * @inheritDoc
     */
    public function build(OrderedMapInterface $facet): array
    {
        $values = [];

        foreach ($facet->get('items') ?? [] as $item) {
            // need filter with count > 0 (no configurations for that in LupaSearch)
            if (!$item instanceof OrderedMapInterface || 0 === $item->get('count')) {
                continue;
            }

            $value = ($item->get('from') ?? '*') . '_' . ($item->get('to') ?? '*');
            $values[$value] = [
                'value' => $value,
                'count' => $item->get('count') ?? 0,
            ];
        }

        return $values;
    }
}
