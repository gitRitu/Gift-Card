<?php

namespace Dotsquare\Giftcard\Model\ResourceModel\Giftcard;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Quote
 *
 * @package Dotsquare\Giftcard\Model\ResourceModel\Giftcard
 */
class Quote extends AbstractDb
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('ds_giftcard_quote', 'id');
    }
}
