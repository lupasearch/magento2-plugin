<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Normalizer;

use Magento\Framework\Stdlib\DateTime\DateTime;

class DateNormalizer implements DateNormalizerInterface
{
    /**
     * @var DateTime
     */
    private $dateTime;

    public function __construct(DateTime $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public function normalize(string $date): string
    {
        return $this->dateTime->gmtDate('Y-m-d\TH:i:s\Z', $date);
    }
}
