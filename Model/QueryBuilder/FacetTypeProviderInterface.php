<?php

namespace LupaSearch\LupaSearchPlugin\Model\QueryBuilder;

use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;

interface FacetTypeProviderInterface
{
    public const TERMS = 'terms';
    public const STATS = 'stats';
    public const RANGE = 'range';
    public const HIERARCHY = 'hierarchy';

    /**
     * @description Available types: terms, range, stats and hierarchy
     */
    public function get(AbstractAttribute $attribute): string;
}
