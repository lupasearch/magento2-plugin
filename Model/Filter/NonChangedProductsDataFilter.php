<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Filter;

use LupaSearch\LupaSearchPlugin\Model\Config\Index\ProductConfigInterface;
use LupaSearch\LupaSearchPlugin\Model\Formatter\HashDataFormatterInterface;
use LupaSearch\LupaSearchPlugin\Model\ResourceModel\GetProductHashesByProductIds;
use LupaSearch\LupaSearchPlugin\Model\ResourceModel\UpdateProductHashes;
use Exception;
use InvalidArgumentException;
use Magento\Framework\Serialize\SerializerInterface;

use function array_keys;
use function md5;

class NonChangedProductsDataFilter implements DataFilterInterface
{
    private GetProductHashesByProductIds $getProductHashesByProductIds;

    private UpdateProductHashes $updateProductHashes;

    private HashDataFormatterInterface $formatter;

    private SerializerInterface $serializer;

    private ProductConfigInterface $productConfig;

    public function __construct(
        GetProductHashesByProductIds $getProductHashesByProductIds,
        UpdateProductHashes $updateProductHashes,
        HashDataFormatterInterface $formatter,
        SerializerInterface $serializer,
        ProductConfigInterface $productConfig
    ) {
        $this->getProductHashesByProductIds = $getProductHashesByProductIds;
        $this->updateProductHashes = $updateProductHashes;
        $this->formatter = $formatter;
        $this->serializer = $serializer;
        $this->productConfig = $productConfig;
    }

    /**
     * @param array<array<string|int|float|array<string>>> $data
     * @return array<array<string|int|float|array<string>>>
     * @throws Exception
     */
    public function filter(array $data, int $storeId): array
    {
        $ids = array_keys($data);

        if (!$ids || !$this->productConfig->isHashCheckEnabled($storeId)) {
            return $data;
        }

        $newHashes = $this->getHashesFromData($data);
        $oldHashes = $this->getHashesByIds($ids, $storeId);

        foreach ($newHashes as $id => $hash) {
            if (!isset($oldHashes[$id]) || $oldHashes[$id] !== $hash) {
                continue;
            }

            unset($data[$id], $newHashes[$id]);
        }

        if (!$newHashes) {
            return $data;
        }

        $hashesForUpdating = $this->formatter->format($newHashes, $storeId);

        $this->updateProductHashes->execute($hashesForUpdating);

        return $data;
    }

    /**
     * @param array<array<string|int|float|array<string>>> $result
     * @return array<string>
     * @throws InvalidArgumentException
     */
    private function getHashesFromData(array $result): array
    {
        $hashes = [];

        foreach ($result as $id => $product) {
            // phpcs:ignore
            $hashes[$id] = md5($this->serializer->serialize($product));
        }

        return $hashes;
    }

    /**
     * @param array<int> $productIds
     * @return array<string>
     * @throws Exception
     */
    private function getHashesByIds(array $productIds, int $storeId): array
    {
        return $this->getProductHashesByProductIds->execute($productIds, $storeId);
    }
}
