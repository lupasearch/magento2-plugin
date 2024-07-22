<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model;

use Magento\Framework\MessageQueue\MergedMessageInterfaceFactory;
use Magento\Framework\MessageQueue\MergerInterface;

class Merger implements MergerInterface
{
    /**
     * @var MergedMessageInterfaceFactory
     */
    private $mergedMessageFactory;

    public function __construct(MergedMessageInterfaceFactory $mergedMessageFactory)
    {
        $this->mergedMessageFactory = $mergedMessageFactory;
    }

    /**
     * @inheritDoc
     */
    public function merge(array $messages): array
    {
        $result = [];

        foreach ($messages as $topicName => $topicMessages) {
            foreach ($topicMessages as $messageId => $message) {
                $mergedMessage = $this->mergedMessageFactory->create(
                    [
                        'mergedMessage' => $message,
                        'originalMessagesIds' => [$messageId],
                    ],
                );
                $result[$topicName][] = $mergedMessage;
            }
        }

        return $result;
    }
}
