<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Provider\Attributes;

interface AttributeMapperInterface
{
    public function getField(string $attributeCode): string;
}
