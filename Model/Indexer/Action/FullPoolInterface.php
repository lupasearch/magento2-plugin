<?php

namespace LupaSearch\LupaSearchPlugin\Model\Indexer\Action;

interface FullPoolInterface
{
    public function get(string $code): ?FullInterface;
}
