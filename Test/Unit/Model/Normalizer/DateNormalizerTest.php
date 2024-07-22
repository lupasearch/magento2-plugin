<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Model\Normalizer;

use LupaSearch\LupaSearchPlugin\Model\Normalizer\DateNormalizer;
use DateTimeZone;
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
     * @var DateTimeZone
     */
    private $timeZone;

    public function testNormalize(): void
    {
        $this->localeDate
            ->expects(self::any())
            ->method('date')
            ->with(1586368300)
            ->willReturn(
                (new \DateTime('', $this->timeZone))->setTimestamp(1586368300),
            );

        $this->assertEquals('2020-04-08T10:51:40Z', $this->object->normalize('2020-04-08 10:51:40'));
    }

    protected function setUp(): void
    {
        $this->localeDate = $this->createMock(TimezoneInterface::class);
        $this->timeZone = new DateTimeZone('UTC');
        $this->dateTime = new DateTime($this->localeDate);
        $this->object = new DateNormalizer($this->dateTime);
    }
}
