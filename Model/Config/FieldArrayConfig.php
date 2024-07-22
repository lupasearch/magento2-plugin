<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Config;

use InvalidArgumentException;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface;

use function array_column;
use function is_array;

use const PHP_EOL;

class FieldArrayConfig implements FieldArrayConfigInterface
{
    protected ScopeConfigInterface $scopeConfig;

    private Json $json;

    private LoggerInterface $logger;

    public function __construct(ScopeConfigInterface $scopeConfig, Json $json, LoggerInterface $logger)
    {
        $this->scopeConfig = $scopeConfig;
        $this->json = $json;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function getColumn(string $path, string $column, ?int $scopeCode = null): array
    {
        $value = $this->getValue($path, $scopeCode);

        return array_column($value, $column);
    }

    /**
     * @inheritDoc
     */
    public function getPairs(string $path, string $column, string $key, ?int $scopeCode = null): array
    {
        $value = $this->getValue($path, $scopeCode);

        return array_column($value, $column, $key);
    }

    /**
     * @inheritDoc
     */
    public function getValue(string $path, ?int $scopeCode = null): array
    {
        try {
            $string = (string)$this->scopeConfig->getValue(
                $path,
                ScopeInterface::SCOPE_STORES,
                $scopeCode
            );

            if (empty($string)) {
                return [];
            }

            $value = $this->json->unserialize($string);

            return is_array($value) ? $value : [];
        } catch (InvalidArgumentException $e) {
            $this->logger->error($e->getMessage() . PHP_EOL . $e->getTraceAsString());

            return [];
        }
    }
}
