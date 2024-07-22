<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\QueryBuilder;

use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;

class FacetTypeProvider implements FacetTypeProviderInterface
{
    // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInImplementedInterface
    public function get(AbstractAttribute $attribute): string
    {
        if ('category' === $attribute->getAttributeCode()) {
            return self::HIERARCHY;
        }

        switch ($attribute->getBackendType()) {
            case 'decimal':
                return self::STATS;

            default:
                return self::TERMS;
        }
    }
}
