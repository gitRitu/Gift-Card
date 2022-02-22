<?php

namespace Dotsquare\Giftcard\Model\ResourceModel\Giftcard;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Invoice
 *
 * @package Dotsquare\Giftcard\Model\ResourceModel\Giftcard
 */
class Invoice extends AbstractDb
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('ds_giftcard_invoice', 'id');
    }
}
