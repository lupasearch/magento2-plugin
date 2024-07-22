<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Queries\SearchQuery\Configuration;

use Magento\Framework\Search\RequestInterface;

interface SortBuilderInterface
{
    /**
     * @return array<int, array<string, string>>
     */
    public function build(RequestInterface $request): array;
}
