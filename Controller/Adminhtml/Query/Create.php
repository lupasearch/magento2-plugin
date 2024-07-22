<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Controller\Adminhtml\Query;

use LupaSearch\LupaSearchPlugin\Model\QueryManagerInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Cache\Type\Config;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;
use Throwable;

class Create extends Action
{
    /**
     * @var QueryManagerInterface
     */
    private $queryManager;

    /**
     * @var TypeListInterface
     */
    private $typeList;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        Context $context,
        QueryManagerInterface $queryManager,
        TypeListInterface $typeList,
        LoggerInterface $logger
    ) {
        parent::__construct($context);

        $this->queryManager = $queryManager;
        $this->typeList = $typeList;
        $this->logger = $logger;
    }

    public function execute(): ResultInterface
    {
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        try {
            $type = (string)$this->getRequest()->getParam('type');

            if (empty($type)) {
                throw new LocalizedException(__('Type undefined'));
            }

            $storeId = $this->getRequest()->getParam('store_id');

            if (null === $storeId) {
                throw new LocalizedException(__('Store ID undefined'));
            }

            $this->queryManager->generate($type, (int)$storeId);

            $this->typeList->cleanType(Config::TYPE_IDENTIFIER);
        } catch (Throwable $exception) {
            return $resultJson->setData(['success' => 0, 'message' => $exception->getMessage()]);
        }

        return $resultJson->setData(['success' => 1, 'message' => __('Query successfully generated.')]);
    }
}
