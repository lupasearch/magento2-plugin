<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionBuilder\SortBuilder;

use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionBuilder\SortBuilderInterface;
use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionParametersInterface;

class Created implements SortBuilderInterface
{
    /**
     * @var string
     */
    private $newAttributeCode;

    /**
     * @var string
     */
    private $direction;

    public function __construct(
        string $newAttributeCode = 'created',
        string $direction = OptionParametersInterface::DESC
    ) {
        $this->newAttributeCode = $newAttributeCode;
        $this->direction = $direction;
    }

    /**
     * @inheritDoc
     * @phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.FoundInImplementedInterface
     */
    public function build(OptionParametersInterface $parameters): array
    {
        return ['field' => $this->newAttributeCode, 'order' => $this->direction];
    }
}
