<?php

namespace LupaSearch\LupaSearchPlugin\Model\Validator;

use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;

interface AttributeValidatorInterface
{
    public function validate(AbstractAttribute $attribute): bool;
}
