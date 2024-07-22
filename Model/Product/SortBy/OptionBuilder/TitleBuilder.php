<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionBuilder;

use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionParametersInterface;

use function sprintf;
use function strtoupper;

class TitleBuilder
{
    private const ASC_SYMBOL = '↓';

    private const DESC_SYMBOL = '↑';

    /**
     * @var string
     */
    protected $ascAlphabet = 'A-Z';

    /**
     * @var string
     */
    protected $descAlphabet = 'Z-A';

    /**
     * @var string
     */
    protected $ascText = 'ascending';

    /**
     * @var string
     */
    protected $descText = 'descending';

    /**
     * @var string[]
     */
    private $alphabetSortAttributes;

    /**
     * @var bool
     */
    private $useSymbol;

    /**
     * @param string[] $alphabetSortAttributes
     */
    public function __construct(bool $useSymbol, array $alphabetSortAttributes = [])
    {
        $this->alphabetSortAttributes = $alphabetSortAttributes;
        $this->useSymbol = $useSymbol;
    }

    public function build(OptionParametersInterface $optionParameters): string
    {
        $title = __($optionParameters->getLabel()) . ' ';
        $title .= $this->getDirection($optionParameters->getCode(), strtoupper($optionParameters->getDirection()));

        return $title;
    }

    private function getDirection(string $attributeCode, string $direction): string
    {
        if (isset($this->alphabetSortAttributes[$attributeCode])) {
            return $this->formatAlphabetDirection($direction);
        }

        if ($this->useSymbol) {
            return $this->formatSymbolDirection($direction);
        }

        return $this->formatTextDirection($direction);
    }

    private function formatAlphabetDirection(string $direction): string
    {
        switch ($direction) {
            case OptionParametersInterface::DESC:
                return sprintf('(%s)', (string)__($this->descAlphabet));

            case OptionParametersInterface::ASC:
                return sprintf('(%s)', (string)__($this->ascAlphabet));

            default:
                return '';
        }
    }

    private function formatSymbolDirection(string $direction): string
    {
        switch ($direction) {
            case OptionParametersInterface::DESC:
                return self::DESC_SYMBOL;

            case OptionParametersInterface::ASC:
                return self::ASC_SYMBOL;

            default:
                return '';
        }
    }

    private function formatTextDirection(string $direction): string
    {
        switch ($direction) {
            case OptionParametersInterface::DESC:
                return sprintf('(%s)', (string)__($this->descText));

            case OptionParametersInterface::ASC:
                return sprintf('(%s)', (string)__($this->ascText));

            default:
                return '';
        }
    }
}
