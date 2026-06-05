<?php

namespace LupaSearch\LupaSearchPlugin\Model\Config\Index;

interface ProductConfigInterface
{
    public function getAttributeMaxProductSize(): int;

    public function isZeroPriceEnabled(): bool;
}
