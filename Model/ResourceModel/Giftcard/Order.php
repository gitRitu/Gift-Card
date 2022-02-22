<?php

namespace Dotsquare\Giftcard\Model\ResourceModel\Giftcard;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Order
 *
 * @package Dotsquare\Giftcard\Model\ResourceModel\Giftcard
 */
class Order extends AbstractDb
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('ds_giftcard_order', 'id');
    }
}
