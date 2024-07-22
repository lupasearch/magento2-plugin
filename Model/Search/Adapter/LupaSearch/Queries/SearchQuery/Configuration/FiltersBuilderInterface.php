<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Queries\SearchQuery\Configuration;

use Magento\Framework\Search\Request\Query\Filter;

interface FiltersBuilderInterface
{
    /**
     * @param Filter[] $filters
     * @return array<string, array<string|float|bool|array<string>|null>>
     */
    public function build(array $filters): array;
}
