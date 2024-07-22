<?php

namespace LupaSearch\LupaSearchPlugin\Model\Formatter;

interface HashDataFormatterInterface
{
    /**
     * @param array<string> $data
     * @return array<array<int|string>>
     */
    public function format(array $data, int $storeId): array;
}
