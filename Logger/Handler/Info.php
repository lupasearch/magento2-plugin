<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Logger\Handler;

use Magento\Framework\Filesystem\DriverInterface;
use Magento\Framework\Logger\Handler\Base;
use Monolog\Logger;

use function date;
use function sprintf;

class Info extends Base
{
    private const FILENAME_FORMAT = 'var/log/LupaSearch/info/info-%s.log';
    private const DATE_FORMAT = 'Y-m-d';

    /**
     * @var int
     */
    protected $loggerType = Logger::INFO;

    public function __construct(DriverInterface $filesystem, ?string $filePath = null, ?string $fileName = null)
    {
        $fileName = sprintf(self::FILENAME_FORMAT, date(self::DATE_FORMAT));

        parent::__construct($filesystem, $filePath, $fileName);
    }
}
