<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product;

use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\BoostAttributesProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\SearchableAttributesProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\BoostProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\BoostsPool;

class BoostProvider implements BoostProviderInterface
{
    /**
     * @var BoostsPool
     */
    private $boostsPool;

    /**
     * @var BoostAttributesProviderInterface
     */
    private $boostAttributesProvider;

    /**
     * @var SearchableAttributesProviderInterface
     */
    private $searchableAttributesProvider;

    public function __construct(
        BoostsPool $boostsPool,
        BoostAttributesProviderInterface $boostAttributesProvider,
        SearchableAttributesProviderInterface $searchableAttributesProvider
    ) {
        $this->boostsPool = $boostsPool;
        $this->boostAttributesProvider = $boostAttributesProvider;
        $this->searchableAttributesProvider = $searchableAttributesProvider;
    }

    /**
     * @inheritDoc
     */
    public function getBoosts(): array
    {
        $fields = [];

        foreach ($this->boostsPool->getAll() as $fieldName => $fieldBoost) {
            $fieldBoost[$fieldName] = $fieldBoost->getKeywords();
        }

        return $fields;
    }

    /**
     * @inheritDoc
     */
    public function getQueryFields(): array
    {
        return $this->searchableAttributesProvider->getList();
    }

    /**
     * @inheritDoc
     */
    public function getBoostFields(): array
    {
        return $this->boostAttributesProvider->getCoefficients();
    }
}
