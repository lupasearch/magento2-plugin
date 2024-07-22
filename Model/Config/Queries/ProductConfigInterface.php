<?php

namespace LupaSearch\LupaSearchPlugin\Model\Config\Queries;

interface ProductConfigInterface
{
    public function isOutOfStockProductsAtTheEnd(?int $scopeCode = null): bool;

    /**
     * @return string[]
     */
    public function getBoostFields(): array;

    /**
     * @return array<string, int>
     */
    public function getBoostFieldCoefficients(): array;

    public function getCategoriesSearchWeight(): int;

    public function getSearchSuggestionCount(?int $scopeCode = null): int;

    public function isResultsCountForEachSuggestionEnabled(?int $scopeCode = null): bool;

    public function isSearchSuggestionsEnabled(?int $scopeCode = null): bool;
}
