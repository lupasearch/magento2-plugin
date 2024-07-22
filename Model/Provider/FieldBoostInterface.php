<?php

namespace LupaSearch\LupaSearchPlugin\Model\Provider;

interface FieldBoostInterface
{
    /**
     * @return string[]
     */
    public function getKeywords(): array;
}
