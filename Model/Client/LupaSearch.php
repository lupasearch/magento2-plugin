<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Client;

use LupaSearch\LupaSearchPlugin\Model\Adapter\Index\IndexProviderInterface;
use LupaSearch\LupaSearchPluginCore\Model\LupaClientFactoryInterface;
use LupaSearch\LupaClientInterface;
use Magento\AdvancedSearch\Model\Client\ClientInterface;
use Magento\Store\Model\StoreManagerInterface;
use Throwable;

class LupaSearch implements ClientInterface
{
    /**
     * @var mixed[]
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     */
    private array $options = [];

    private LupaClientFactoryInterface $lupaClientFactory;

    private StoreManagerInterface $storeManager;

    private IndexProviderInterface $indexProvider;

    /**
     * @var LupaClientInterface[]
     */
    private array $clients = [];

    /**
     * @param mixed[] $options
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     */
    public function __construct(
        LupaClientFactoryInterface $lupaClientFactory,
        StoreManagerInterface $storeManager,
        IndexProviderInterface $indexProvider,
        array $options = []
    ) {
        $this->lupaClientFactory = $lupaClientFactory;
        $this->storeManager = $storeManager;
        $this->indexProvider = $indexProvider;
        $this->options = $options;
    }

    public function testConnection(): bool
    {
        try {
            return $this->getClient()->getApiKey() ? $this->testApiKey() : $this->testJwtToken();
        } catch (Throwable $e) {
            return false;
        }
    }

    private function testApiKey(): bool
    {
        $indexId = $this->indexProvider->getId($this->getStoreId());
        $indices = $this->getClient()->send(LupaClientInterface::METHOD_GET, "/indices/$indexId", true);

        return isset($indices['id']);
    }

    private function testJwtToken(): bool
    {
        $userInfo = $this->getClient()->send(LupaClientInterface::METHOD_GET, '/users/me', true);

        return isset($userInfo['id']);
    }

    private function getStoreId(): int
    {
        return (int)$this->storeManager->getStore()->getId();
    }

    private function getClient(): LupaClientInterface
    {
        $storeId = $this->getStoreId();

        if (!isset($this->clients[$storeId])) {
            $this->clients[$storeId] = $this->lupaClientFactory->create();
        }

        return $this->clients[$storeId];
    }
}
