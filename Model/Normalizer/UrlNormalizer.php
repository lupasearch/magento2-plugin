<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Normalizer;

use function preg_replace;

class UrlNormalizer implements UrlNormalizerInterface
{
    private const URL_REGEX = '/^(http(s)*:\/\/[^\/]*?\.?([^\/.]+)\.[^\/.]{2,}(?::\d+)?\/)/';

    public function normalize(string $url): string
    {
        return preg_replace(self::URL_REGEX, '/', $url);
    }
}
