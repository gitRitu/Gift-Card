<?php

namespace Dotsquare\Giftcard\Model\ResourceModel\Pool;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Code
 *
 * @package Dotsquare\Giftcard\Model\ResourceModel\Pool
 */
class Code extends AbstractDb
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('ds_giftcard_pool_code', 'id');
    }
}
