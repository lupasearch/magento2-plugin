<?php

namespace LupaSearch\LupaSearchPlugin\Model\Product\SortBy;

interface OptionParametersInterface
{
    public const ASC = 'ASC';
    public const DESC = 'DESC';

    public function getLabel(): string;

    public function getCode(): string;

    public function getDirection(): string;

    public function setDirection(string $direction): void;
}
