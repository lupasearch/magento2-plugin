<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Indexer\Category;

use LupaSearch\LupaSearchPlugin\Model\Hydrator\CategoryHydratorInterface;
use LupaSearch\LupaSearchPlugin\Model\Indexer\DataGeneratorInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\CategoriesProviderInterface;

class DataGenerator implements DataGeneratorInterface
{
    /**
     * @var CategoryHydratorInterface
     */
    protected $hydrator;

    /**
     * @var CategoriesProviderInterface
     */
    protected $categoriesProvider;

    public function __construct(CategoryHydratorInterface $hydrator, CategoriesProviderInterface $categoriesProvider)
    {
        $this->hydrator = $hydrator;
        $this->categoriesProvider = $categoriesProvider;
    }

    /**
     * @inheritDoc
     */
    public function generate(array $ids, int $storeId): array
    {
        if (empty($ids)) {
            return [];
        }

        $data = [];
        $categories = $this->categoriesProvider->getByIds($ids, $storeId);

        foreach ($categories as $category) {
            if (!$category->getProductCount()) {
                continue;
            }

            $category->unsetData('request_path');
            $category->setStoreId($storeId);

            $data[(int)$category->getId()] = $this->hydrator->extract($category);
        }

        return $data;
    }
}
