<?php

namespace LupaSearch\LupaSearchPlugin\Model\Normalizer;

interface UrlNormalizerInterface
{
    public function normalize(string $url): string;
}
