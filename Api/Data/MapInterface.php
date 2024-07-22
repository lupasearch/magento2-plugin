<?php

namespace LupaSearch\LupaSearchPlugin\Api\Data;

interface MapInterface
{
    /**
     * @return string
     */
    public function getValue(): string;

    /**
     * @return string
     */
    public function getLabel(): string;
}
