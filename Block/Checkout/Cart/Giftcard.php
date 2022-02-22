<?php

namespace Dotsquare\Giftcard\Block\Checkout\Cart;

use Magento\Framework\View\Element\Template;

/**
 * Class Giftcard
 *
 * @package Dotsquare\Giftcard\Block\Checkout\Cart
 */
class Giftcard extends Template
{
    /**
     * Retrieve action url
     *
     * @return string
     */
    public function getActionUrl()
    {
        return $this->getUrl('dsgiftcard/cart/apply');
    }

    /**
     * Retrieve check Gift Card code url
     *
     * @return string
     */
    public function getCheckCodeUrl()
    {
        return $this->getUrl('dsgiftcard/card/checkCode');
    }
}
