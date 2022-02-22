<?php

namespace Dotsquare\Giftcard\Model\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Yesno
 *
 * @package Dotsquare\Giftcard\Model\Source
 */
class YesNo implements ArrayInterface
{
    /**#@+
     * Yes and No action values
     */
    const YES = 1;
    const NO = 0;
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::YES, 'label' => __('Yes')],
            ['value' => self::NO, 'label' => __('No')]
        ];
    }
}
