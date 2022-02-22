<?php

namespace Dotsquare\Giftcard\Model\Source\Giftcard;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class CodeFormat
 *
 * @package Dotsquare\Giftcard\Model\Source\Giftcard
 */
class CodeFormat implements ArrayInterface
{
    /**#@+
     * Constants defined for Gift Card code format
     */
    const ALPHANUMERIC = 'alphanumeric';
    const ALPHABETIC = 'alphabetic';
    const NUMERIC = 'numeric';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::ALPHANUMERIC,
                'label' => __('Alphanumeric')
            ],
            [
                'value' => self::ALPHABETIC,
                'label' => __('Alphabetic')
            ],
            [
                'value' => self::NUMERIC,
                'label' => __('Numeric')
            ]
        ];
    }
}
