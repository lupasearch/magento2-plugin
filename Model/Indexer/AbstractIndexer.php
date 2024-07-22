<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Indexer;

use LupaSearch\LupaSearchPlugin\Model\Indexer\Action\FullInterface;
use LupaSearch\LupaSearchPlugin\Model\Indexer\Action\RowsInterface;
use Magento\Framework\Indexer\ActionInterface;
use Magento\Framework\Mview\ActionInterface as MviewActionInterface;

use function is_array;

abstract class AbstractIndexer implements ActionInterface, MviewActionInterface
{
    /**
     * @var FullInterface
     */
    private $full;

    /**
     * @var RowsInterface
     */
    private $rows;

    public function __construct(FullInterface $full, RowsInterface $rows)
    {
        $this->full = $full;
        $this->rows = $rows;
    }

    /**
     * @inheritDoc
     * @phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingAnyTypeHint
     */
    public function execute($ids): void
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $this->executeList($ids);
    }

    public function executeRow($id): void
    {
        $this->executeList([$id]);
    }

    /**
     * @inheritDoc
     */
    public function executeList(array $ids): void
    {
        $this->rows->execute($ids);
    }

    public function executeFull(): void
    {
        $this->full->execute();
    }
}
