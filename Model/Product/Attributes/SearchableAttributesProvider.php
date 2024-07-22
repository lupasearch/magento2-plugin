<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Attributes;

use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\ContainsProductCodeAttributeMapInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\SearchableAttributesProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\ResourceModel\Product\Attribute as AttributeResource;

use function array_filter;
use function array_flip;

class SearchableAttributesProvider implements SearchableAttributesProviderInterface
{
    private ContainsProductCodeAttributeMapInterface $containsProductCodeAttributeMap;

    /**
     * @var string[]|null
     */
    private ?array $containsProductCodeAttributeIds = null;

    /**
     * @var int[]|null
     */
    private ?array $searchWeightMapByAttributeCode = null;

    private AttributeResource $attributeResource;

    public function __construct(
        ContainsProductCodeAttributeMapInterface $containsProductCodeAttributeMap,
        AttributeResource $attributeResource
    ) {
        $this->containsProductCodeAttributeMap = $containsProductCodeAttributeMap;
        $this->attributeResource = $attributeResource;
    }

    public function getSearchWeight(string $attributeCode): int
    {
        return $this->getSearchWeightMapByAttributeCode()[$attributeCode] ?? 0;
    }

    /**
     * @inheritDoc
     */
    public function getList(): array
    {
        $attributes = $this->getSystemAttributes();

        foreach ($this->attributeResource->getAllSearchWeights() as $weight) {
            $attributes[SearchableAttributesProviderInterface::ATTRIBUTE_PREFIX . $weight] = $weight;
        }

        return $attributes;
    }

    /**
     * @inheritDoc
     */
    public function getAttributeCodes(): array
    {
        $list = [];

        foreach ($this->attributeResource->fetchAllSearchableAttributes() as $data) {
            $code = $data['attribute_code'] ?? '';
            $list[$code] = SearchableAttributesProviderInterface::ATTRIBUTE_PREFIX . $code;
        }

        return $list;
    }

    /**
     * @return array<string, int>
     */
    protected function getSystemAttributes(): array
    {
        $attributes = [];

        foreach ($this->getContainsProductCodeAttributeIds() as $realAttributeCode => $attributeCode) {
            $attributes[$attributeCode] = $this->getSearchWeight($realAttributeCode);
        }

        return array_filter($attributes);
    }

    /**
     * @return string[]
     */
    protected function getContainsProductCodeAttributeIds(): array
    {
        if (null !== $this->containsProductCodeAttributeIds) {
            return $this->containsProductCodeAttributeIds;
        }

        $this->containsProductCodeAttributeIds = array_flip($this->containsProductCodeAttributeMap->getList());

        return $this->containsProductCodeAttributeIds;
    }

    /**
     * @return int[]
     */
    private function getSearchWeightMapByAttributeCode(): array
    {
        if (null !== $this->searchWeightMapByAttributeCode) {
            return $this->searchWeightMapByAttributeCode;
        }

        foreach ($this->attributeResource->fetchAllSearchableAttributes() as $data) {
            $this->searchWeightMapByAttributeCode[($data['attribute_code'] ?? '')] = (int)($data['search_weight'] ?? 0);
        }

        return $this->searchWeightMapByAttributeCode;
    }
}
