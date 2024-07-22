<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionBuilder\SortBuilder;

use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionBuilder\SortBuilderInterface;
use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionParametersInterface;

class Relevance implements SortBuilderInterface
{
    /**
     * @description Relevance field code
     */
    public const FIELD_RELEVANCE = '_relevance';

    /**
     * @inheritDoc
     * @phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.FoundInImplementedInterface
     */
    public function build(OptionParametersInterface $parameters): array
    {
        return ['field' => self::FIELD_RELEVANCE, 'order' => OptionParametersInterface::DESC];
    }
}
