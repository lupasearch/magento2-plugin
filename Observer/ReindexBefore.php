<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Observer;

use LupaSearch\LupaSearchPlugin\Model\Provider\ProviderCacheInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ReindexBefore implements ObserverInterface
{
    private ProviderCacheInterface $providerCache;

    public function __construct(ProviderCacheInterface $providerCache)
    {
        $this->providerCache = $providerCache;
    }

    public function execute(Observer $observer): void
    {
        $ids = $observer->getData('ids');
        $storeId = (int)$observer->getData('store_id');

        $this->providerCache->warmup($ids, $storeId);
    }
}
