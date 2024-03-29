<?php

namespace Dotsquare\Giftcard\Pricing\Price;

use Dotsquare\Giftcard\Api\Data\OptionInterface;
use Magento\Catalog\Pricing\Price\ConfiguredPrice as CatalogConfiguredPrice;

/**
 * Class ConfiguredPrice
 *
 * @package Dotsquare\Giftcard\Pricing\Price
 */
class ConfiguredPrice extends CatalogConfiguredPrice
{
    /**
     * Calculate configured price
     *
     * @return float
     */
    protected function calculatePrice()
    {
        $value = $this->getProduct()->getPrice();
        if ($this->getProduct()->hasCustomOptions()) {
            /** @var \Magento\Wishlist\Model\Item\Option $customOption */
            $amountOption = $this->getProduct()->getCustomOption(OptionInterface::AMOUNT);
            if ($amountOption) {
                $value = ($amountOption->getValue() ? $amountOption->getValue() : 0.);
            }
        }
        $value += parent::getOptionsValue();
        return $value;
    }

    /**
     * Price value of product with configured options
     *
     * @return bool|float
     */
    public function getValue()
    {
        return $this->item ? $this->calculatePrice() : max(0, $this->getBasePrice()->getValue());
    }
}
