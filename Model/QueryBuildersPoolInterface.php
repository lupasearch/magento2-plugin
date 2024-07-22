<?php

namespace LupaSearch\LupaSearchPlugin\Model;

use LupaSearch\LupaSearchPlugin\Model\QueryBuilder\QueryBuilderInterface;
use Magento\Framework\Exception\NotFoundException;

interface QueryBuildersPoolInterface
{
    /**
     * @throws NotFoundException
     */
    public function getByType(string $type): QueryBuilderInterface;
}
