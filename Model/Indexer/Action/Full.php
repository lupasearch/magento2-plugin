<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Indexer\Action;

class Full extends AbstractAction implements FullInterface
{
    public function execute(): void
    {
        foreach ($this->getActiveStoreIds() as $storeId) {
            $this->executeByStore($storeId);
        }
    }

    public function executeByStore(int $storeId): void
    {
        $ids = $this->getAllIds($storeId);

        foreach ($this->createChunks($ids) as $chunk) {
            $batch = $this->createBatch();
            $batch->setIds($chunk);
            $batch->setStoreId($storeId);

            $this->publish($this->getTopic(), $batch);
        }

        $this->cleanIndexes($storeId, $ids);
    }
}
