<?php

namespace LupaSearch\LupaSearchPlugin\Model\Filter;

use Exception;

interface DataFilterInterface
{
    /**
     * @param array<array<string|int|float|array<string>>> $data
     * @return array<array<string|int|float|array<string>>>
     * @throws Exception
     */
    public function filter(array $data, int $storeId): array;
}
