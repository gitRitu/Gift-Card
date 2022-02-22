<?php

namespace Dotsquare\Giftcard\Model\Sales\Totals\Calculator;

use Dotsquare\Giftcard\Model\Product\Type\Giftcard as GiftcardProductType;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Sales\Model\Order\Item as OrderItem;

/**
 * Class GiftCardExclude
 *
 * @package Dotsquare\Giftcard\Model\Sales\Totals\Calculator
 */
class GiftCardExclude
{
    /**
     * Exclude gift card row total from grand total
     *
     * @param QuoteItem[]|OrderItem[] $items
     * @param float $baseGrandTotal
     * @param float $grandTotal
     * @return array
     */
    public function calculate($items, $baseGrandTotal, $grandTotal)
    {
        /** @var QuoteItem|OrderItem $item */
        foreach ($items as $item) {
            if ($item->getProductType() == GiftcardProductType::TYPE_CODE) {
                $baseGrandTotal -= $item->getBaseRowTotal();
                $grandTotal -= $item->getRowTotal();
            }
        }

        return [$baseGrandTotal, $grandTotal];
    }
}
