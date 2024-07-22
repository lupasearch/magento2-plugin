<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionBuilder;

use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionParametersInterface as Parameters;

use function array_filter;
use function in_array;

class ValueBuilder
{
    /**
     * @var string[]
     */
    private $oppositeCodes = [];

    /**
     * @param string[] $oppositeCodes
     */
    public function __construct(array $oppositeCodes = [])
    {
        $this->oppositeCodes = array_filter($oppositeCodes);
    }

    /**
     * @return array{fields: string, order: string}
     */
    public function build(Parameters $parameters): array
    {
        return ['field' => $parameters->getCode(), 'order' => $this->getDirection($parameters)];
    }

    private function getDirection(Parameters $parameters): string
    {
        if (!in_array($parameters->getCode(), $this->oppositeCodes, true)) {
            return $parameters->getDirection();
        }

        return Parameters::ASC === $parameters->getDirection() ? Parameters::DESC : Parameters::ASC;
    }
}
