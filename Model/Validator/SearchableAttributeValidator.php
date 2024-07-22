<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Validator;

use Magento\Catalog\Api\Data\EavAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;

class SearchableAttributeValidator implements AttributeValidatorInterface
{
    public function validate(AbstractAttribute $attribute): bool
    {
        return $attribute->getData(EavAttributeInterface::IS_SEARCHABLE)
            || $attribute->getData(EavAttributeInterface::IS_VISIBLE_IN_ADVANCED_SEARCH);
    }
}
