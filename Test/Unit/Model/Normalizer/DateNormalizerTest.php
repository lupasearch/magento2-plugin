<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Model\Normalizer;

use LupaSearch\LupaSearchPlugin\Model\Normalizer\DateNormalizer;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DateNormalizerTest extends TestCase
{
    /**
     * @var DateNormalizer
     */
    private $object;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var TimezoneInterface|MockObject
     */
    private $localeDate;

    /**
     * @var string
     */
    private $originalTimezone;

    public function testNormalize(): void
    {
        $this->localeDate
            ->expects(self::once())
            ->method('date')
            ->with(1586343100)
            ->willReturn(
                new \DateTime('@1586343100'),
            );

        $this->assertEquals('2020-04-08T10:51:40Z', $this->object->normalize('2020-04-08 10:51:40'));
    }

    protected function tearDown(): void
    {
        date_default_timezone_set($this->originalTimezone);
    }

    protected function setUp(): void
    {
        $this->localeDate = $this->createMock(TimezoneInterface::class);
        $this->originalTimezone = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $this->dateTime = new DateTime($this->localeDate);
        $this->object = new DateNormalizer($this->dateTime);
    }
}
