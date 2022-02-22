<?php

namespace Dotsquare\Giftcard\Block\Adminhtml\Order\Totals;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\DataObject\Factory;
use Dotsquare\Giftcard\Api\Data\Giftcard\OrderInterface as GiftcardOrderInterface;
use Magento\Sales\Model\Order;

/**
 * Class Giftcard
 *
 * @package Dotsquare\Giftcard\Block\Adminhtml\Order\Totals
 */
class Giftcard extends Template
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * @param Context $context
     * @param Factory $factory
     * @param [] $data
     */
    public function __construct(
        Context $context,
        Factory $factory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->factory = $factory;
    }

    /**
     * Add Gift Card codes to total
     *
     * @return $this
     */
    public function initTotals()
    {
        $source = $this->getSource();
        if (!$source || !$source->getExtensionAttributes()
            || ($source->getExtensionAttributes() && !$source->getExtensionAttributes()->getDsGiftcardCodes())
        ) {
            return $this;
        }

        $giftcards = $source->getExtensionAttributes()->getDsGiftcardCodes();
        /** @var GiftcardOrderInterface $giftcard */
        foreach ($giftcards as $giftcard) {
            $this->getParentBlock()->addTotal(
                $this->factory->create(
                    [
                        'code'       => 'ds_giftcard_' . $giftcard->getGiftcardId(),
                        'strong'     => false,
                        'label'      => __('Gift Card (%1)', $giftcard->getGiftcardCode()),
                        'value'      => -$giftcard->getGiftcardAmount(),
                        'base_value' => -$giftcard->getBaseGiftcardAmount(),
                    ]
                )
            );
        }
        return $this;
    }

    /**
     * Retrieve totals source object
     *
     * @return Order|null
     */
    private function getSource()
    {
        $parentBlock = $this->getParentBlock();
        if ($parentBlock) {
            return $parentBlock->getSource();
        }
        return null;
    }
}
