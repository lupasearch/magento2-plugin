<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Model\Provider;

use LupaSearch\LupaSearchPlugin\Model\Provider\ProviderCacheComposite;
use LupaSearch\LupaSearchPlugin\Model\Provider\ProviderCacheInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProviderCacheCompositeTest extends TestCase
{
    /**
     * @var ProviderCacheComposite
     */
    private $object;

    /**
     * @var MockObject[]
     */
    private $instances = [];

    public function testWarmup(): void
    {
        $ids = [1, 5];
        $storeId = 1;

        $this->instances['test1']
            ->expects(self::once())
            ->method('warmup')
            ->with($ids, $storeId);

        $this->instances['test3']
            ->expects(self::once())
            ->method('warmup')
            ->with($ids, $storeId);

        $this->object->warmup($ids, $storeId);
    }

    public function testFlush(): void
    {
        $this->instances['test1']
            ->expects(self::once())
            ->method('flush');

        $this->instances['test3']
            ->expects(self::once())
            ->method('flush');

        $this->object->flush();
    }

    protected function setUp(): void
    {
        $this->instances['test1'] = $this->createMock(ProviderCacheInterface::class);
        $this->instances['test2'] = null;
        $this->instances['test3'] = $this->createMock(ProviderCacheInterface::class);
        $this->instances['test4'] = 'Test';

        $this->object = new ProviderCacheComposite($this->instances);
    }
}
