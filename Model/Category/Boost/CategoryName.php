<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Category\Boost;

use LupaSearch\LupaSearchPlugin\Model\Provider\FieldBoostInterface;
use Magento\Catalog\Model\ResourceModel\Category\Collection as CategoryCollection;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\DB\Select;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

use const PHP_EOL;

class CategoryName implements FieldBoostInterface
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(CollectionFactory $collectionFactory, LoggerInterface $logger)
    {
        $this->collectionFactory = $collectionFactory;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function getKeywords(): array
    {
        $collection = $this->getCollection();

        if (null === $collection) {
            return [];
        }

        $connection = $collection->getConnection();
        $values = $connection->fetchPairs($collection->getSelect());

        if (empty($values)) {
            return [];
        }
    }

    protected function getCollection(): ?CategoryCollection
    {
        $collection = $this->collectionFactory->create();
        $collection->removeAllFieldsFromSelect();
        $collection->getSelect()->reset(Select::COLUMNS);

        try {
            $collection
                ->addAttributeToSelect('name', 'left')
                ->addAttributeToSelect('lupasearch_boost', 'left')
                ->addAttributeToFilter('lupasearch_boost', ['notnull' => true])
                ->addAttributeToFilter('lupasearch_boost', ['neq' => 0]);
        } catch (LocalizedException $exception) {
            $this->logger->error($exception->getMessage() . PHP_EOL . $exception->getTraceAsString());

            return null;
        }

        return $collection;
    }
}
