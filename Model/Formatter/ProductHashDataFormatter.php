<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Formatter;

use LupaSearch\LupaSearchPlugin\Model\ResourceModel\GetProductHashesByProductIds;

class ProductHashDataFormatter implements HashDataFormatterInterface
{
    /**
     * @param array<string> $data
     * @return array<array<int|string>>
     */
    public function format(array $data, int $storeId): array
    {
        $formattedHashes = [];

        foreach ($data as $productId => $hash) {
            $formattedHashes[] = [
                GetProductHashesByProductIds::COLUMN_PRODUCT_ID => $productId,
                GetProductHashesByProductIds::COLUMN_HASH => $hash,
                GetProductHashesByProductIds::COLUMN_STORE_ID => $storeId,
            ];
        }

        return $formattedHashes;
    }
}
