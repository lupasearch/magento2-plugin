<?php

namespace LupaSearch\LupaSearchPlugin\Model\Config\Index;

interface ProductConfigInterface
{
    public function isHashCheckEnabled(int $storeId): bool;

    public function getAttributeMaxProductSize(): int;

    public function isZeroPriceEnabled(): bool;
}
