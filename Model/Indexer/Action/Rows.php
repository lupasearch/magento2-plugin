<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Indexer\Action;

class Rows extends AbstractAction implements RowsInterface
{
    /**
     * @inheritDoc
     */
    public function execute(array $ids): void
    {
        foreach ($this->getActiveStoreIds() as $storeId) {
            $this->executeByStore($storeId, $ids);
        }
    }

    /**
     * @inheritDoc
     */
    public function executeByStore(int $storeId, array $ids): void
    {
        foreach ($this->createChunks($ids) as $chunk) {
            $batch = $this->createBatch();
            $batch->setIds($chunk);
            $batch->setStoreId($storeId);

            $this->publish($this->getTopic(), $batch);
        }
    }
}
