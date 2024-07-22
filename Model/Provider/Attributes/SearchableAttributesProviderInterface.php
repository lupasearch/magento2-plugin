<?php

namespace LupaSearch\LupaSearchPlugin\Model\Provider\Attributes;

interface SearchableAttributesProviderInterface
{
    public const ATTRIBUTE_PREFIX = 'sp_';

    /**
     * @return array<string, int>
     */
    public function getList(): array;

    /**
     * @return array<string, string>
     */
    public function getAttributeCodes(): array;

    public function getSearchWeight(string $attributeCode): int;
}
