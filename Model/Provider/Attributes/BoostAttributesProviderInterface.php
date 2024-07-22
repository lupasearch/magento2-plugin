<?php

namespace LupaSearch\LupaSearchPlugin\Model\Provider\Attributes;

use LupaSearch\LupaSearchPlugin\Model\Provider\AttributesProviderInterface;

interface BoostAttributesProviderInterface extends AttributesProviderInterface
{
    public const ATTRIBUTE_PREFIX = 'bf_';

    public const DEFAULT_COEFFICIENT = 1.0;

    public function getCoefficient(string $attributeCode): float;

    /**
     * @return float[]
     */
    public function getCoefficients(): array;
}
