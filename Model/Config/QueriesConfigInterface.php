<?php

namespace LupaSearch\LupaSearchPlugin\Model\Config;

interface QueriesConfigInterface
{
    public function getProduct(?int $scopeCode = 0): ?string;

    public function getProductCatalog(?int $scopeCode = 0): ?string;

    public function getProductSearchBox(?int $scopeCode = 0): ?string;

    public function getProductSuggestion(?int $scopeCode = 0): ?string;

    public function getCategory(?int $scopeCode = 0): ?string;

    public function setProduct(string $query, ?int $scopeId = 0): void;

    public function setProductCatalog(string $query, ?int $scopeId = 0): void;

    public function setProductSearchBox(string $query, ?int $scopeId = 0): void;

    public function setProductSuggestion(string $query, ?int $scopeId = 0): void;

    public function setCategory(string $query, ?int $scopeId = 0): void;

    public function getBoostFunctionCoefficient(?int $scopeCode = 0): float;
}
