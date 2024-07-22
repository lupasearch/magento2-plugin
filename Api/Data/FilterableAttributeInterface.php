<?php

namespace LupaSearch\LupaSearchPlugin\Api\Data;

interface FilterableAttributeInterface
{
    /**
     * @return string
     */
    public function getValue(): string;

    /**
     * @return string
     */
    public function getLabel(): string;

    /**
     * @return bool
     */
    public function getUseInSearch(): bool;

    /**
     * @return bool
     */
    public function getWithResults(): bool;
}
