<?php

namespace LupaSearch\LupaSearchPlugin\Api;

interface LupaSearchMapInterface
{
    /**
     * @return \LupaSearch\LupaSearchPlugin\Api\Data\MapInterface[]
     */
    public function getCategories(): array;

    /**
     * @return \LupaSearch\LupaSearchPlugin\Api\Data\FilterableAttributeInterface[]
     */
    public function getFilterableAttributes(): array;
}
