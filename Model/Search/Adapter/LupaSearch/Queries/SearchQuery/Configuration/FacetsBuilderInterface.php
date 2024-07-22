<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Queries\SearchQuery\Configuration;

use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\OrderedMapInterface;
use Magento\Framework\Search\RequestInterface;

interface FacetsBuilderInterface
{
    /**
     * @return OrderedMapInterface[]
     */
    public function build(RequestInterface $request): array;
}
