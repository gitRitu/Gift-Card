<?php

namespace Dotsquare\Giftcard\Block\Order\Item\Email;

use Magento\Framework\View\Element\Template\Context;
use \Magento\Sales\Block\Order\Email\Items\Order\DefaultOrder;
use Dotsquare\Giftcard\Model\Product\Option\Render as OptionRender;

/**
 * Class Renderer
 *
 * @package Dotsquare\Giftcard\Block\Order\Item\Email
 */
class Renderer extends DefaultOrder
{
    /**
     * @var OptionRender
     */
    private $optionRender;

    /**
     * @param Context $context
     * @param OptionRender $optionRender
     * @param [] $data
     */
    public function __construct(
        Context $context,
        OptionRender $optionRender,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->optionRender = $optionRender;
    }

    /**
     * {@inheritdoc}
     */
    public function getItemOptions()
    {
        return $this->optionRender->render(
            $this->getItem()->getProductOptions(),
            OptionRender::FRONTEND_SECTION
        );
    }
}
