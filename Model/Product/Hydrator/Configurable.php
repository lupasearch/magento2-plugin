<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Hydrator;

use LupaSearch\LupaSearchPlugin\Model\Hydrator\ProductHydratorInterface;
use LupaSearch\LupaSearchPlugin\Model\Product\HydratorPool;
use LupaSearch\LupaSearchPlugin\Model\ResourceModel\Product\Configurable as ConfigurableResourceModel;
use Magento\Catalog\Model\Config;
use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableType;

use function array_merge_recursive;

class Configurable implements ProductHydratorInterface
{
    private const REQUIRED_ATTRIBUTES = [
        'name',
        'price',
        'weight',
        'image',
        'thumbnail',
        'media_gallery'
    ];

    private HydratorPool $hydratorPool;

    private Config $config;

    private ConfigurableResourceModel $configurableResourceModel;

    public function __construct(
        HydratorPool $hydratorPool,
        Config $config,
        ConfigurableResourceModel $configurableResourceModel
    ) {
        $this->hydratorPool = $hydratorPool;
        $this->config = $config;
        $this->configurableResourceModel = $configurableResourceModel;
    }

    /**
     * @inheritDoc
     */
    public function extract(Product $product): array
    {
        if (ConfigurableType::TYPE_CODE !== $product->getTypeId()) {
            return [];
        }

        $collection = $product->getTypeInstance()->getUsedProductCollection($product);
        $collection->setFlag('has_stock_status_filter', true);
        $collection->addAttributeToSelect($this->getAttributes($product));
        $collection->addFilterByRequiredOptions();
        $collection->setStoreId($product->getStoreId());
        $collection->addMediaGalleryData();
        $collection->addTierPriceData();

        $data = [];

        foreach ($collection as $simpleProduct) {
            foreach ($this->hydratorPool->getAll() as $hydrator) {
                $data[] = $hydrator->extract($simpleProduct);
            }
        }

        return $data ? array_merge_recursive(...$data) : [];
    }

    /**
     * @return string[]
     */
    private function getAttributes(Product $product): array
    {
        $attributeCodes = array_unique(
            array_merge(
                $this->config->getProductAttributes(),
                self::REQUIRED_ATTRIBUTES,
                $this->configurableResourceModel->getAttributeCodes((int)$product->getId()),
            )
        );
        $attributeCodes = array_flip($attributeCodes);
        unset($attributeCodes['status']);

        return array_flip($attributeCodes);
    }
}
