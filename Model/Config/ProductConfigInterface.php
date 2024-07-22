<?php

namespace LupaSearch\LupaSearchPlugin\Model\Config;

interface ProductConfigInterface
{
    public function isFilterInStock(?int $storeId = null): bool;
}
