<?php

namespace LupaSearch\LupaSearchPlugin\Model\Config\Index;

interface HashCheckConfigInterface
{
    public function isHashCheckEnabled(int $storeId): bool;
}