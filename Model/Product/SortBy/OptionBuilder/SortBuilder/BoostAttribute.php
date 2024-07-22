<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionBuilder\SortBuilder;

use LupaSearch\LupaSearchPlugin\Model\Product\Attributes\BoostAttributeProvider;
use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionBuilder\SortBuilderInterface;
use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionParametersInterface;

class BoostAttribute implements SortBuilderInterface
{
    /**
     * @var BoostAttributeProvider
     */
    private $boostAttributeProvider;

    public function __construct(BoostAttributeProvider $boostAttributeProvider)
    {
        $this->boostAttributeProvider = $boostAttributeProvider;
    }

    /**
     * @inheritDoc
     * @phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.FoundInImplementedInterface
     */
    public function build(OptionParametersInterface $parameters): array
    {
        if (!$this->boostAttributeProvider->getLupaSearchId()) {
            return [];
        }

        return [
            'field' => $this->boostAttributeProvider->getLupaSearchId(),
            'order' => OptionParametersInterface::DESC,
        ];
    }
}
