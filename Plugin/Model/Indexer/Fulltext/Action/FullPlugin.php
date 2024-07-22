<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Plugin\Model\Indexer\Fulltext\Action;

use Generator;
use Magento\CatalogSearch\Model\Indexer\Fulltext\Action\Full as Subject;
use Magento\Framework\Search\EngineResolverInterface;

use function array_unique;

class FullPlugin
{
    private EngineResolverInterface $engineResolver;

    public function __construct(EngineResolverInterface $engineResolver)
    {
        $this->engineResolver = $engineResolver;
    }

    public function aroundRebuildStoreIndex(
        Subject $subject,
        callable $process,
        $storeId,
        $productIds = null
    ): Generator {
        if ('lupasearch' !== $this->engineResolver->getCurrentSearchEngine()) {
            return $process($storeId, $productIds);
        }

        $productIds = null === $productIds ? [] : array_unique($productIds);

        foreach ($productIds as $id) {
            yield $id;
        }

        return $productIds;
    }
}
