<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Attributes;

use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionBuilder\SortBuilder\Relevance;
use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\AttributeMapperInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\FilterableAttributesProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\SearchableAttributesProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\SystemAttributeMapInterface;

use function array_flip;

class AttributeMapper implements AttributeMapperInterface
{
    private FilterableAttributesProviderInterface $filterableAttributesProvider;

    private SystemAttributeMapInterface $systemAttributeMap;

    private SearchableAttributesProviderInterface $searchableAttributesProvider;

    /**
     * @var string[]|null
     */
    private ?array $fieldCache = null;

    public function __construct(
        FilterableAttributesProviderInterface $filterableAttributesProvider,
        SystemAttributeMapInterface $systemAttributeMap,
        SearchableAttributesProviderInterface $searchableAttributesProvider
    ) {
        $this->filterableAttributesProvider = $filterableAttributesProvider;
        $this->systemAttributeMap = $systemAttributeMap;
        $this->searchableAttributesProvider = $searchableAttributesProvider;
    }

    public function getField(string $attributeCode): string
    {
        $this->init();

        return $this->fieldCache[$attributeCode] ?? $attributeCode;
    }

    private function init(): void
    {
        if (null !== $this->fieldCache) {
            return;
        }

        $this->fieldCache = array_merge(
            $this->searchableAttributesProvider->getAttributeCodes(),
            $this->filterableAttributesProvider->getAttributeCodes(),
            array_flip($this->systemAttributeMap->getList()),
            [
                '_id' => 'id',
                'entity_id' => 'id',
                'relevance' => Relevance::FIELD_RELEVANCE,
            ],
        );
    }
}
