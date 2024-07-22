<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Normalizer;

interface StringNormalizerInterface
{
    public const MAX_CHARACTERS = 1000;
    public const DEFAULT_CHARSET = 'utf-8';

    public function normalize(string $value): string;
}
