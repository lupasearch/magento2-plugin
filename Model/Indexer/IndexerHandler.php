<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Indexer;

use LupaSearch\LupaSearchPlugin\Model\Indexer\Action\FullInterface;
use LupaSearch\LupaSearchPlugin\Model\Indexer\Action\RowsInterface;
use Magento\Framework\Indexer\Dimension;
use Magento\Framework\Indexer\SaveHandler\IndexerInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreDimensionProvider;
use Traversable;

use function iterator_to_array;

class IndexerHandler implements IndexerInterface
{
    private RowsInterface $rows;

    private FullInterface $full;

    public function __construct(RowsInterface $rows, FullInterface $full)
    {
        $this->rows = $rows;
        $this->full = $full;
    }

    /**
     * @inheritDoc
     */
    public function saveIndex($dimensions, Traversable $documents): void
    {
        $storeId = $this->getStoreId($dimensions);
        $productIds = iterator_to_array($documents);

        if ($productIds) {
            $this->rows->executeByStore($storeId, $productIds);

            return;
        }

        $this->full->executeByStore($storeId);
    }

    /**
     * @inheritDoc
     */
    public function deleteIndex($dimensions, Traversable $documents): void
    {
        // Do nothing
        return;
    }

    /**
     * @inheritDoc
     */
    public function cleanIndex($dimensions): void
    {
        // Do nothing
        return;
    }

    /**
     * @inheritDoc
     */
    public function isAvailable($dimensions = []): bool
    {
        return true;
    }

    private function getStoreId(?array $dimensions = []): int
    {
        if (!$dimensions) {
            return Store::DISTRO_STORE_ID;
        }

        $scope = $dimensions[StoreDimensionProvider::DIMENSION_NAME] ?? null;
        $scope = $scope instanceof Dimension ? $scope : $this->findScope($dimensions);

        return $scope instanceof Dimension ? (int)$scope->getValue() : Store::DISTRO_STORE_ID;
    }

    /**
     * @param Dimension[] $dimensions
     */
    private function findScope(array $dimensions): ?Dimension
    {
        foreach ($dimensions as $dimension) {
            if ($dimension instanceof Dimension && StoreDimensionProvider::DIMENSION_NAME === $dimension->getName()) {
                return $dimension;
            }
        }

        return null;
    }
}
