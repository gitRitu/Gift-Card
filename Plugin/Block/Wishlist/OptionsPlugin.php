<?php

namespace Dotsquare\Giftcard\Plugin\Block\Wishlist;

use Magento\Framework\View\Element\Template;

/**
 * Class Plugin
 *
 * @package Dotsquare\Giftcard\Plugin\Block\Wishlist
 */
class OptionsPlugin
{
    /**
     * Add Gift Cart options to wishlist widget
     *
     * @param Template $subject
     * @param [] $result
     * @return []
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetWishlistOptions(Template $subject, $result)
    {
        return array_merge($result, ['ds_giftcardInfo' => '[name^=ds_gc_]']);
    }
}
