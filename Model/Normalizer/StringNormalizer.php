<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Normalizer;

class StringNormalizer implements StringNormalizerInterface
{
    public function normalize(string $value): string
    {
        return empty($value) ? $value : mb_substr($value, 0, self::MAX_CHARACTERS, self::DEFAULT_CHARSET);
    }
}
