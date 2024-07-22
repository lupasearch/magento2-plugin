<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\CatalogInventory\Model\Configuration;
use Magento\Customer\Model\Group;
use Magento\Framework\Data\CollectionModifier;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

class CollectionBuilder
{
    /**
     * @var CollectionFactory
     */
    protected $productsCollectionFactory;

    /**
     * @var CollectionModifier
     */
    private $collectionModifier;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        CollectionFactory $productCollectionFactory,
        CollectionModifier $collectionModifier,
        StoreManagerInterface $storeManager
    ) {
        $this->productsCollectionFactory = $productCollectionFactory;
        $this->collectionModifier = $collectionModifier;
        $this->storeManager = $storeManager;
    }

    public function build(int $storeId): Collection
    {
        $collection = $this->productsCollectionFactory->create();
        $collection->setStoreId($storeId);
        $collection->addStoreFilter($storeId);
        $collection
            ->addPriceData(Group::NOT_LOGGED_IN_ID, $this->getWebsiteId($storeId));
        $collection->addAttributeToSelect('*');

        $this->collectionModifier->apply($collection);

        $collection->getSelect()->group('e.entity_id');

        return $collection;
    }

    private function getWebsiteId(int $storeId): int
    {
        try {
            return (int)$this->storeManager->getStore($storeId)->getWebsiteId();
        } catch (NoSuchEntityException $exception) {
            return Configuration::DEFAULT_WEBSITE_ID;
        }
    }
}
