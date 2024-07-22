<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Attributes;

use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\SortableDecimalAttributesProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\ProviderCacheInterface;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;

class SortableDecimalAttributesProvider implements
    ProviderCacheInterface,
    SortableDecimalAttributesProviderInterface
{
    private const DECIMAL_BACKEND_TYPE = 'decimal';

    /**
     * @var Attribute[][]
     */
    protected $attributeSetMap = [];

    private CollectionFactory $attributeCollectionFactory;

    private ProductCollectionFactory $productCollectionFactory;

    public function __construct(
        CollectionFactory $attributeCollectionFactory,
        ProductCollectionFactory $productCollectionFactory
    ) {
        $this->attributeCollectionFactory = $attributeCollectionFactory;
        $this->productCollectionFactory = $productCollectionFactory;
    }

    /**
     * @return Attribute[]
     */
    public function getByAttributeSetId(int $attributeSetId): array
    {
        if (isset($this->attributeSetMap[$attributeSetId])) {
            return $this->attributeSetMap[$attributeSetId];
        }

        $attributeCollection = $this->attributeCollectionFactory->create();
        $attributeCollection->addFieldToFilter('backend_type', ['eq' => self::DECIMAL_BACKEND_TYPE]);

        $attributeCollection->joinLeft(
            ['eea' => $attributeCollection->getTable('eav_entity_attribute')],
            'main_table.attribute_id = eea.attribute_id',
            []
        );

        $attributeCollection->addFieldToFilter('eea.attribute_set_id', ['eq' => $attributeSetId]);
        $attributeCollection->addFieldToFilter(Attribute::USED_FOR_SORT_BY, ['eq' => true]);

        $this->attributeSetMap[$attributeSetId] = $attributeCollection->getItems();

        return $this->attributeSetMap[$attributeSetId];
    }

    /**
     * @inheritDoc
     * @phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter
     */
    public function warmup(array $ids, ?int $storeId = null): void
    {
        $attributeSetIds = $this->getAttributeSetIdsByProductIds($ids, $storeId);

        foreach ($attributeSetIds as $attributeSetId) {
            $this->getByAttributeSetId($attributeSetId);
        }
    }

    public function flush(): void
    {
        $this->attributeSetMap = [];
    }

    /**
     * @param int[] $ids
     * @return int[]
     */
    private function getAttributeSetIdsByProductIds(array $ids, ?int $storeId = null): array
    {
        $productCollection = $this->productCollectionFactory->create();

        if ($storeId) {
            $productCollection->addStoreFilter($storeId);
        }

        $productCollection->addAttributeToFilter('entity_id', ['in' => $ids]);

        return $productCollection->getSetIds();
    }
}
