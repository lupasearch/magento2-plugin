<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model;

use LupaSearch\LupaSearchPlugin\Model\QueryBuilder\QueryBuilderInterface;
use Magento\Framework\Exception\NotFoundException;

class QueryBuildersPool implements QueryBuildersPoolInterface
{
    /**
     * @var QueryBuilderInterface[]
     */
    protected $builders;

    /**
     * @param QueryBuilderInterface[] $builders
     */
    public function __construct(array $builders)
    {
        $this->builders = $builders;
    }

    public function getByType(string $type): QueryBuilderInterface
    {
        if (!isset($type, $this->builders[$type])) {
            throw new NotFoundException(__('Query builder is not found for given type %1', $type));
        }

        return $this->builders[$type];
    }
}
