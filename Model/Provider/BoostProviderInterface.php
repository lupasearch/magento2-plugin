<?php

namespace LupaSearch\LupaSearchPlugin\Model\Provider;

interface BoostProviderInterface
{
    /**
     * @return array<array<string>>
     */
    public function getBoosts(): array;

    /**
     * @return string[]
     */
    public function getQueryFields(): array;

    /**
     * @return float[]
     */
    public function getBoostFields(): array;
}
