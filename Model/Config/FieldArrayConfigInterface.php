<?php

namespace LupaSearch\LupaSearchPlugin\Model\Config;

interface FieldArrayConfigInterface
{
    /**
     * @param string $path
     * @param string $column
     * @param int|null $scopeCode
     * @return string[]
     */
    public function getColumn(string $path, string $column, ?int $scopeCode = null): array;

    /**
     * @param string $path
     * @param string $column
     * @param string $key
     * @param int|null $scopeCode
     * @return string[]
     */
    public function getPairs(string $path, string $column, string $key, ?int $scopeCode = null): array;

    /**
     * @param string $path
     * @param int|null $scopeCode
     * @return string[]
     */
    public function getValue(string $path, ?int $scopeCode = null): array;
}
