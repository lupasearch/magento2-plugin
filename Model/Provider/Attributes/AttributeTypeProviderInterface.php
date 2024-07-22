<?php

namespace LupaSearch\LupaSearchPlugin\Model\Provider\Attributes;

use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;

interface AttributeTypeProviderInterface
{
    public const TYPE_BOOLEAN = 'boolean';
    public const TYPE_INT = 'int';
    public const TYPE_LONG = 'long';
    public const TYPE_FLOAT = 'float';
    public const TYPE_DOUBLE = 'double';
    public const TYPE_STRING = 'string';
    public const TYPE_TEXT = 'text';
    public const TYPE_DATE = 'date';

    public function getByCode(string $attributeCode): string;

    public function get(AbstractAttribute $attribute): string;
}
