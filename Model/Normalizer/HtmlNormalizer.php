<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Normalizer;

use function html_entity_decode;
use function preg_replace;
use function strip_tags;
use function trim;

class HtmlNormalizer implements HtmlNormalizerInterface
{
    public function normalize(string $value): string
    {
        if (empty($value)) {
            return $value;
        }

        $value = $this->removeStyleElement($value);
        $value = $this->removeScriptElement($value);
        $value = strip_tags($value);
        $value = $this->removeNewLine($value);
        $value = preg_replace('/\s{2,}/', ' ', $value);
        $value = trim($value);
        // phpcs:ignore Magento2.Functions.DiscouragedFunction.Discouraged
        $value = html_entity_decode($value);

        return mb_substr($value, 0, self::MAX_CHARACTERS, self::DEFAULT_CHARSET);
    }

    private function removeNewLine(string $text): string
    {
        return preg_replace('/([\\r\\n]+)/s', ' ', $text);
    }

    private function removeStyleElement(string $text): string
    {
        return preg_replace('/(\<style.*\<\/style>)/s', '', $text);
    }

    private function removeScriptElement(string $text): string
    {
        return preg_replace('/(\<script.*\<\/script>)/s', '', $text);
    }
}
