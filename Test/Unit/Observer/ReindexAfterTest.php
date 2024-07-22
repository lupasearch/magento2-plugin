<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Observer;

use LupaSearch\LupaSearchPlugin\Model\Provider\ProviderCacheInterface;
use LupaSearch\LupaSearchPlugin\Observer\ReindexAfter;
use Magento\Framework\Event\Observer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ReindexAfterTest extends TestCase
{
    /**
     * @var ReindexAfter
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
        $this->providerCache
            ->expects(self::once())
            ->method('flush');

        $this->object->execute($this->observer);
    }

    protected function setUp(): void
    {
        $this->providerCache = $this->createMock(ProviderCacheInterface::class);
        $this->observer = $this->createMock(Observer::class);
        $this->object = new ReindexAfter($this->providerCache);
    }
}
