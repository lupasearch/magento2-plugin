<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model;

use LupaSearch\LupaSearchPlugin\Api\LupaSearchMapInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\AttributeTypeProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\FilterableAttributesProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\CategoriesProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\ResourceModel\Eav as EavResource;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute as EavAttributeResource;
use Magento\Store\Model\StoreManagerInterface;

use function array_map;
use function strtoupper;

class LupaSearchMap implements LupaSearchMapInterface
{
    /**
     * @var FilterableAttributesProviderInterface
     */
    private $filterableAttributesProvider;

    /**
     * @var CategoriesProviderInterface
     */
    private $categoriesProvider;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var AttributeTypeProviderInterface
     */
    private $attributeTypeProvider;

    /**
     * @var EavResource
     */
    private $eavResource;

    public function __construct(
        FilterableAttributesProviderInterface $filterableAttributesProvider,
        CategoriesProviderInterface $categoriesProvider,
        StoreManagerInterface $storeManager,
        AttributeTypeProviderInterface $attributeTypeProvider,
        EavResource $eavResource
    ) {
        $this->filterableAttributesProvider = $filterableAttributesProvider;
        $this->categoriesProvider = $categoriesProvider;
        $this->storeManager = $storeManager;
        $this->attributeTypeProvider = $attributeTypeProvider;
        $this->eavResource = $eavResource;
    }

    /**
     * @inheritDoc
     */
    public function getCategories(): array
    {
        $storeId = (int)$this->storeManager->getStore()->getId();

        $list = $this->categoriesProvider->getIdNameMap($storeId);
        $result = [];

        foreach ($list as $value => $label) {
            $result[] = [
                'value' => $value,
                'label' => $label,
            ];
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getFilterableAttributes(): array
    {
        $list = $this->filterableAttributesProvider->getList();
        $attributeSetIds = $this->eavResource->getSetIdsByAttributeIds(array_map([$this, 'getAttributeId'], $list));
        $result = [];

        foreach ($list as $attributeId => $attribute) {
            $result[] = [
                'value' => $attributeId,
                'label' => $attribute->getStoreLabel(),
                'use_in_search' => (bool)$attribute->getIsFilterableInSearch(),
                'with_results' => 1 === (int)$attribute->getIsFilterable(),
                'position' => (int)$attribute->getPosition(),
                'type' => strtoupper($this->attributeTypeProvider->get($attribute)),
                'set_ids' => $attributeSetIds[(int)$attribute->getId()] ?? [],
            ];
        }

        return $result;
    }

    private function getAttributeId(EavAttributeResource $attribute): int
    {
        return (int)$attribute->getId();
    }
}
