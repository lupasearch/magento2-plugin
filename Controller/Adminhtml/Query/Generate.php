<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Controller\Adminhtml\Query;

use LupaSearch\LupaSearchPlugin\Model\QueriesGeneratorInterface;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class Generate extends Action
{
    /**
     * @var QueriesGeneratorInterface
     */
    private $queriesGenerator;

    public function __construct(Context $context, QueriesGeneratorInterface $queriesGenerator)
    {
        parent::__construct($context);

        $this->queriesGenerator = $queriesGenerator;
    }

    public function execute(): ResultInterface
    {
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        try {
            $this->queriesGenerator->generateAll();
        } catch (Exception $exception) {
            return $resultJson->setData([
                'success' => 0,
                'message' => $exception->getMessage(),
            ]);
        }

        return $resultJson->setData([
            'success' => 1,
            'message' => __('Queries successfully generated.'),
        ]);
    }
}
