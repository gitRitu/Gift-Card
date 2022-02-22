<?php

namespace Dotsquare\Giftcard\Block\Customer;

use Magento\Framework\View\Element\Template;

/**
 * Class Giftcard
 *
 * @package Dotsquare\Giftcard\Block\Customer
 */
class Giftcard extends Template
{
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
