<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Queries\SearchQuery\Configuration;

use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\AttributeMapperInterface;

class FiltersBuilder implements FiltersBuilderInterface
{
    public FilterValueProviderInterface $filterValueProvider;

    private AttributeMapperInterface $attributeMapper;

    public function __construct(
        AttributeMapperInterface $attributeMapper,
        FilterValueProviderInterface $filterValueProvider
    ) {
        $this->attributeMapper = $attributeMapper;
        $this->filterValueProvider = $filterValueProvider;
    }

    /**
     * @inheritDoc
     */
    public function build(array $filters): array
    {
        $result = [];

        foreach ($filters as $filter) {
            $reference = $filter->getReference();
            $field = $this->attributeMapper->getField($reference->getField());
            $result[$field] = $this->filterValueProvider->get($reference);
        }

        return $result;
    }
}
