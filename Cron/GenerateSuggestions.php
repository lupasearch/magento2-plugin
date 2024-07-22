<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Cron;

use LupaSearch\LupaSearchPlugin\Model\SuggestionsGeneratorInterface;

class GenerateSuggestions
{
    private SuggestionsGeneratorInterface $suggestionsGenerator;

    public function __construct(SuggestionsGeneratorInterface $suggestionsGenerator)
    {
        $this->suggestionsGenerator = $suggestionsGenerator;
    }

    public function execute(): void
    {
        $this->suggestionsGenerator->generateAll();
    }
}
