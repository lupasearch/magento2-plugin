<?php

namespace LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionBuilder;

use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionParametersInterface;

interface SortBuilderInterface
{
    /**
     * @param OptionParametersInterface $parameters
     * @return string[]
     */
    public function build(OptionParametersInterface $parameters): array;
}
