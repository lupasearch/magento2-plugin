<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Cron;

use LupaSearch\LupaSearchPlugin\Model\QueriesGeneratorInterface;

class GenerateQueries
{
    /**
     * @var QueriesGeneratorInterface
     */
    private $queriesGenerator;

    public function __construct(QueriesGeneratorInterface $queriesGenerator)
    {
        $this->queriesGenerator = $queriesGenerator;
    }

    public function execute(): void
    {
        $this->queriesGenerator->generateAll();
    }
}
