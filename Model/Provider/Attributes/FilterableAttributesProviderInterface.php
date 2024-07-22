<?php

namespace LupaSearch\LupaSearchPlugin\Model\Provider\Attributes;

use LupaSearch\LupaSearchPlugin\Model\Provider\AttributesProviderInterface;

interface FilterableAttributesProviderInterface extends AttributesProviderInterface
{
    public const ATTRIBUTE_PREFIX = 'ep_';

    /**
     * @return array<string, string>
     */
    public function getAttributeCodes(): array;
}
