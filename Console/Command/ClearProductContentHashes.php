<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Console\Command;

use LupaSearch\LupaSearchPlugin\Model\ResourceModel\TruncateProductHashes;
use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearProductContentHashes extends Command
{
    private TruncateProductHashes $truncateProductHashes;

    public function __construct(TruncateProductHashes $truncateProductHashes, ?string $name = null)
    {
        $this->truncateProductHashes = $truncateProductHashes;

        parent::__construct($name);
    }

    public function configure(): void
    {
        $this->setName('lupasearch:lupasearch:clear-product-content-hashes');
        $this->setDescription('Clears product content hashes to reset last export content state.');

        parent::configure();
    }

    // phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClassAfterLastUsed
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->truncateProductHashes->execute();

        return Cli::RETURN_SUCCESS;
    }
}
