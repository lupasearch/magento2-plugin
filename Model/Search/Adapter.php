<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Search;

use LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Aggregation\BuilderInterface as AggregationBuilderInterface;
use LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Document\BuilderInterface as DocumentBuilderInterface;
use LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\QueriesInterface;
use Magento\Framework\Search\AdapterInterface;
use Magento\Framework\Search\RequestInterface;
use Magento\Framework\Search\Response\QueryResponse;
use Magento\Framework\Search\Response\QueryResponseFactory;

class Adapter implements AdapterInterface
{
    private QueriesInterface $queries;

    private QueryResponseFactory $responseFactory;

    private AggregationBuilderInterface $aggregationBuilder;

    private DocumentBuilderInterface $documentBuilder;

    public function __construct(
        QueriesInterface $queries,
        QueryResponseFactory $responseFactory,
        AggregationBuilderInterface $aggregationBuilder,
        DocumentBuilderInterface $documentBuilder
    ) {
        $this->queries = $queries;
        $this->responseFactory = $responseFactory;
        $this->aggregationBuilder = $aggregationBuilder;
        $this->documentBuilder = $documentBuilder;
    }

    public function query(RequestInterface $request): QueryResponse
    {
        $response = $this->queries->testDocument($request);

        return $this->responseFactory->create(
            [
                'documents' => $this->documentBuilder->build($response),
                'aggregations' => $this->aggregationBuilder->build($response, $request),
                'total' => $response->getTotal(),
            ]
        );
    }
}
