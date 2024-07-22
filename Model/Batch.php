<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model;

class Batch implements BatchInterface
{
    /**
     * @var int[]
     */
    private $ids = [];

    /**
     * @var int
     */
    private $storeId = 0;

    /**
     * @inheritDoc
     */
    public function getIds(): array
    {
        return $this->ids;
    }

    /**
     * @inheritDoc
     */
    public function setIds(array $ids): void
    {
        $this->ids = $ids;
    }

    public function getStoreId(): int
    {
        return $this->storeId;
    }

    public function setStoreId(int $id): void
    {
        $this->storeId = $id;
    }
}
