<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Observer;

use LupaSearch\LupaSearchPlugin\Model\Provider\ProviderCacheInterface;
use LupaSearch\LupaSearchPlugin\Observer\ReindexBefore;
use Magento\Framework\Event\Observer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ReindexBeforeTest extends TestCase
{
    /**
     * @var ReindexBefore
     */
    private $object;

    /**
     * @var MockObject
     */
    private $providerCache;

    /**
     * @var MockObject
     */
    private $observer;

    public function testExecute(): void
    {
        $ids = [5, 8, 9, 3];
        $storeId = '5';

        $this->observer
            ->expects(self::exactly(2))
            ->method('getData')
            ->withConsecutive(
                ['ids'],
                ['store_id'],
            )
            ->willReturnOnConsecutiveCalls(
                $ids,
                $storeId,
            );

        $this->providerCache
            ->expects(self::once())
            ->method('warmup')
            ->with($ids, 5);

        $this->object->execute($this->observer);
    }

    protected function setUp(): void
    {
        $this->providerCache = $this->createMock(ProviderCacheInterface::class);
        $this->observer = $this->createMock(Observer::class);
        $this->object = new ReindexBefore($this->providerCache);
    }
}
