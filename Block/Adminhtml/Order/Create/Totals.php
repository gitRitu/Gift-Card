<?php

namespace Dotsquare\Giftcard\Block\Adminhtml\Order\Create;

use Dotsquare\Giftcard\Api\Data\Giftcard\QuoteInterface;
use Magento\Sales\Block\Adminhtml\Order\Create\Totals\DefaultTotals;

/**
 * Class Totals
 *
 * @package Dotsquare\Giftcard\Block\Adminhtml\Order\Create
 */
class Totals extends DefaultTotals
{
    /**
     * {@inheritdoc}
     */
    public function getValues()
    {
        $values = [];
        $giftcards = $this->getTotal()->getDsGiftcardCodes();
        /** @var QuoteInterface $giftcard */
        foreach ($giftcards as $giftcard) {
            if ($giftcard->isRemove()) {
                continue;
            }
            $values[] = [
                'code' => $giftcard->getGiftcardCode(),
                'label' => 'Gift Card (%1)',
                'amount' => $giftcard->getGiftcardAmount()
            ];
        }
        return $values;
    }
}
