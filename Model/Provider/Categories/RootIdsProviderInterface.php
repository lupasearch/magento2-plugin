<?php

namespace LupaSearch\LupaSearchPlugin\Model\Provider\Categories;

interface RootIdsProviderInterface
{
    /**
     * @return int[]
     */
    public function get(): array;
}
