<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Queries;

use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\DocumentQueryInterface;
use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\DocumentQueryInterfaceFactory as DocumentQueryFactory;
use Magento\Framework\Search\RequestInterface;

class DocumentQueryBuilder implements DocumentQueryBuilderInterface
{
    private const LIMIT_MAX = 1000;

    private DocumentQueryFactory $documentQueryFactory;

    public function __construct(DocumentQueryFactory $documentQueryFactory)
    {
        $this->documentQueryFactory = $documentQueryFactory;
    }

    public function build(?RequestInterface $request = null): DocumentQueryInterface
    {
        $documentQuery = $this->documentQueryFactory->create();
        $documentQuery->setLimit(
            $request && $request->getSize() <= self::LIMIT_MAX ? (int)$request->getSize() : self::LIMIT_MAX
        );

        return $documentQuery;
    }
}
