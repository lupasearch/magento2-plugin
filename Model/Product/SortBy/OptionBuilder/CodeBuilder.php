<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionBuilder;

use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionParametersInterface;

use function strtolower;

class CodeBuilder
{
    public function build(OptionParametersInterface $optionParameters): string
    {
        $code = $optionParameters->getCode();
        $code .= $optionParameters->getDirection() ? '_' : '';
        $code .= trim(strtolower($optionParameters->getDirection()));

        return $code;
    }
}
