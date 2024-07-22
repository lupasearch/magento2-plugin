<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Queries\SearchQuery\Configuration;

use Magento\Framework\Search\Request\FilterInterface;

interface FilterValueProviderInterface
{
    /**
     * @return string[]
     */
    public function get(FilterInterface $reference): array;
}
