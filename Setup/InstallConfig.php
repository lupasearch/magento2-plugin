<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Setup;

use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Search\Setup\InstallConfigInterface;
use Magento\Setup\Model\SearchConfigOptionsList;

use function array_merge;
use function in_array;

class InstallConfig implements InstallConfigInterface
{
    private WriterInterface $configWriter;

    private EncryptorInterface $encryptor;

    /**
     * @var string[]
     */
    private array $searchConfigMapping = ConfigOptionsList::MAPPING_INPUT_KEY_TO_CONFIG_PATH;

    /**
     * @var string[]
     */
    private array $encryptionKeys = [
        ConfigOptionsList::INPUT_KEY_LUPASEARCH_API_KEY,
        ConfigOptionsList::INPUT_KEY_LUPASEARCH_PASSWORD,
    ];

    /**
     * @param string[] $searchConfigMapping
     * @param string[] $encryptionKeys
     */
    public function __construct(
        WriterInterface $configWriter,
        EncryptorInterface $encryptor,
        array $searchConfigMapping = [],
        array $encryptionKeys = []
    ) {
        $this->configWriter = $configWriter;
        $this->encryptor = $encryptor;
        $this->searchConfigMapping = array_merge(
            $this->searchConfigMapping,
            [SearchConfigOptionsList::INPUT_KEY_SEARCH_ENGINE => 'catalog/search/engine'],
            $searchConfigMapping
        );
        $this->encryptionKeys = array_merge($this->encryptionKeys, $encryptionKeys);
    }

    /**
     * @inheritDoc
     */
    public function configure(array $inputOptions): void
    {
        foreach ($inputOptions as $inputKey => $inputValue) {
            if (null === $inputValue || !isset($this->searchConfigMapping[$inputKey])) {
                continue;
            }

            $inputValue = in_array($inputKey, $this->encryptionKeys, true) ? $this->encryptor->encrypt(
                $inputValue
            ) : $inputValue;
            $this->configWriter->save($this->searchConfigMapping[$inputKey], $inputValue);
        }
    }
}
