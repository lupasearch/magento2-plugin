<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Indexer;

use LupaSearch\LupaSearchPlugin\Model\BatchInterface;
use InvalidArgumentException;
use LupaSearch\Exceptions\BadResponseException;
use Magento\Framework\App\Area;
use Magento\Framework\App\AreaList;
use Magento\Framework\MessageQueue\PublisherInterface;
use Magento\Store\Model\App\Emulation;
use Psr\Log\LoggerInterface;

class Consumer
{
    private PartialIndexerInterface $partialIndexer;

    private PublisherInterface $publisher;

    private Emulation $emulation;

    private AreaList $areaList;

    private LoggerInterface $logger;

    private int $translateId = 0;

    private string $topic;

    public function __construct(
        PartialIndexerInterface $partialIndexer,
        PublisherInterface $publisher,
        Emulation $emulation,
        AreaList $areaList,
        LoggerInterface $logger,
        string $topic
    ) {
        $this->partialIndexer = $partialIndexer;
        $this->publisher = $publisher;
        $this->emulation = $emulation;
        $this->areaList = $areaList;
        $this->logger = $logger;
        $this->topic = $topic;
    }

    public function process(BatchInterface $batch): void
    {
        try {
            $this->emulation->startEnvironmentEmulation($batch->getStoreId(), Area::AREA_FRONTEND, true);

            if ($this->translateId !== $batch->getStoreId()) {
                $this->areaList->getArea(Area::AREA_FRONTEND)->load(Area::PART_TRANSLATE);
                $this->translateId = $batch->getStoreId();
            }

            $this->partialIndexer->reindex($batch->getIds(), $batch->getStoreId());
            $this->emulation->stopEnvironmentEmulation();
        } catch (BadResponseException $exception) {
            $this->repeat($batch);
        }
    }

    private function repeat(BatchInterface $batch): void
    {
        try {
            $this->publisher->publish($this->topic, $batch);
        } catch (InvalidArgumentException $exception) {
            $this->logger->critical($exception->getMessage());
        }
    }
}
