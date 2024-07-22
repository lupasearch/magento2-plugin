<?php

namespace LupaSearch\LupaSearchPlugin\Model\Normalizer;

interface DateNormalizerInterface
{
    public function normalize(string $date): string;
}
