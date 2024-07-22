<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionBuilder\SortBuilder;

use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionBuilder\SortBuilderInterface;
use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionParametersInterface;

use function array_filter;
use function array_values;
use function ksort;

use const SORT_NUMERIC;

class Composite implements SortBuilderInterface
{
    /**
     * @var SortBuilderInterface[]
     */
    private $sortBuilders;

    /**
     * @param SortBuilderInterface[] $sortBuilders
     */
    public function __construct(array $sortBuilders = [])
    {
        $this->sortBuilders = array_filter($sortBuilders);
        ksort($this->sortBuilders, SORT_NUMERIC);
    }

    /**
     * @inheritDoc
     */
    public function build(OptionParametersInterface $parameters): array
    {
        $sort = [];

        foreach ($this->sortBuilders as $sortBuilder) {
            if (!$sortBuilder instanceof SortBuilderInterface) {
                continue;
            }

            $sort[] = $sortBuilder->build($parameters);
        }

        return array_values(array_filter($sort));
    }
}
