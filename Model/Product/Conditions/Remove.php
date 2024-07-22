<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Conditions;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Data\CollectionModifierInterface;

class Remove implements CollectionModifierInterface
{
    // phpcs:ignore Magento2.CodeAnalysis.EmptyBlock.DetectedFunction
    public function apply(AbstractDb $abstractCollection): void
    {
        // remove condition from modifier
    }
}
