<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Queries;

use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\DocumentQueryInterface;
use Magento\Framework\Search\RequestInterface;

interface DocumentQueryBuilderInterface
{
    public function build(?RequestInterface $request = null): DocumentQueryInterface;
}
