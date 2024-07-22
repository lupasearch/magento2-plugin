<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Button;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\Form\Element\AbstractElement;

use function uniqid;

class Ajax extends Field
{
    /**
     * @var string
     * @phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore
     */
    protected $_template = 'LupaSearch_LupaSearchPlugin::system/config/ajax.phtml';

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @phpcs:disable SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param array<mixed> $data
     */
    public function __construct(Context $context, RequestInterface $request, array $data = [])
    {
        parent::__construct($context, $data);

        $this->request = $request;
    }

    public function render(AbstractElement $element): string
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();

        return parent::render($element);
    }

    public function getFunctionName(): string
    {
        return $this->getId();
    }

    public function getAjaxUrl(): string
    {
        $params = [
            'store_id' => (int)$this->request->getParam('store'),
        ];

        return $this->getUrl(
            $this->getData('button_url'),
            $params,
        );
    }

    public function getButtonHtml(): string
    {
        $button = $this->getLayout()
            ->createBlock(Button::class)
            ->setData(
                [
                    'id' => uniqid('btn'),
                    'label' => $this->getData('button_label'),
                    'on_click' => 'window.AjaxConfig.' . $this->getFunctionName() . '(); return false;',
                ],
            );

        return $button->toHtml();
    }

    /**
     * @phpcs:disable PSR2.Methods.MethodDeclaration.Underscore
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        $originalData = $element->getOriginalData();
        $this->addData(
            [
                'id' => $element->getId(),
                'button_label' => __(!empty($originalData['button_label']) ? $originalData['button_label'] : 'Execute'),
                'button_url' => !empty($originalData['button_url']) ? $originalData['button_url'] : '',
            ],
        );

        return $this->_toHtml();
    }
}
